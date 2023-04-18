<?php

namespace Modules\Users\Repositories;

use App\Models\User;
use DB, Mail, Session;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Roles\Entities\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
// use Modules\EmailTemplates\Entities\EmailTemplate;
use Modules\Users\Entities\UserCompleteProfileVerification;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository;
use Modules\Booking\Entities\Booking;
use Modules\ScheduleVisit\Entities\ScheduleVisit;

class VendorsRepository implements VendorsRepositoryInterface
{

    public $User;

    function __construct(
        User $User,
        Role $Role,
        UserCompleteProfileVerification $Vendor,
        EmailNotificationsRepository $EmailNotificationsRepo,
        Booking $Booking,
        ScheduleVisit $ScheduleVisit
    ) {
        $this->User = $User;
        $this->Role = $Role;
        $this->Vendor = $Vendor;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
        $this->Booking = $Booking;
        $this->ScheduleVisit = $ScheduleVisit;
    }

    public function getRecord($id)
    {
        return $this->User->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->User->where('slug', $slug)->first();
    }

    public function getUsersRoleList()
    {
        return $this->Role->getUsersRolePluckList();
    }

    public function getAll($request, $role)
    {
        $users = $this->User->whereHas('roles', function (Builder $q) use ($role) {
            if ($role) {
                $q->where('slug', $role);
            }
        });
        if ($request->get('name')) {
            $users->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', "%" . $request->get('name') . "%")
                    ->orWhere('username', 'LIKE', "%" . $request->get('name') . "%");
            });
        }
 

        if ($request->get('verification_status') != '') {
            if ($request->get('verification_status') == 'pending') {
                $users = $users->whereHas('userCompleteProfilePending', function (Builder $q) {
                    // 
                });
            }

            if ($request->get('verification_status') == 'approved') {
                $users = $users->whereHas('userCompleteProfileVerifiredIfApproved', function (Builder $q) {
                    // 
                });
            }

            if ($request->get('verification_status') == 'rejected') {
                $users = $users->whereHas('userCompleteProfileVerifiredIfRejected', function (Builder $q) {
                    // 
                });
            }
        }



        if ($request->get('phone')) {
            $users->where('phone', 'LIKE', "%" . $request->get('phone') . "%");
        }
        if ($request->get('email')) {
            $users->where('email', $request->get('email'));
        }
 
        if ($request->get('status')) {
            if ($request->get('status') == 'active') {
                $users->where('status', 1);
            }
            if ($request->get('status') == 'inactive') {
                $users->where('status', 0);
            }
        }
        $to  = $request->get('to');
        $from  = $request->get('from');
        if (!empty($from)) {
            $from  = date("Y-m-d", strtotime($from));
            $users = $users->where('created_at', '>=', $from);
        }
        if (!empty($to)) {
            $to  = date("Y-m-d", strtotime($to . "+1 day"));
            $users = $users->where('created_at', '<=', $to);
        }
        return $users->orderBy('id', 'desc')->paginate(10);
    }

    public function store($request)
    {
        try {
            $role = $this->Role->where('slug', 'vendor')->first();
            if (!empty($role)) {
                $chkEmail = User::where(['email' => $request['email'], 'role_id' => $role->id])->first();
                if ($chkEmail) {
                    $response['message'] = 'Vendor email already exists!';
                    $response['type'] = 'error';
                    return $response;
                }
                $chkPhone = User::where(['phone' => $request['phone'], 'role_id' => $role->id])->first();
                if ($chkPhone) {
                    $response['message'] = 'Vendor phone number already exists!';
                    $response['type'] = 'error';
                    return $response;
                }
            }

            $filleable = $request->only('slug', 'username', 'name', 'email', 'phone', 'role_id', 'dob');

            $password  = $request['password'];
            $filleable['password'] = trim(Hash::make($request['password']));
            $filleable['email_verified_at'] = now();
            $filleable['role_id'] = $role->id;
            if ($request->get('image')) {
                $filleable['image'] = $request['image'];
            }
            if ($user = $this->User->create($filleable)) {
                $role = $this->Role->where('slug', 'vendor')->first();
                User::$guard_name = 'web';
                if ($role) {
                    //Assign Vendor Role
                    $user->assignRole([$role->id]);
                    $this->EmailNotificationsRepo->sendWelcomeEmailForVendor($user);
                }
            }
            $response['message'] = trans('flash.success.user_created_successfully');
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function changeStatus($request, $slug)
    {
        $user = $this->getRecordBySlug($slug);
        if ($user) {
            $id = $user->id;
            $change = $this->User->find($id);
            $active = $change->status;
            if ($id != null) {
                $bookingCount = $this->Booking->where('vendor_id', $id)->whereIn('status', ['in-progress', 'pending', 'request', 'confirmed'])->count();
                $scheduleVisitCount = $this->ScheduleVisit->whereHas('scheduleVisitProperty.property', function ($query) use ($id) {
                    $query->where("user_id", $id);
                })->where('status', 'request')->count();

                if ($active == 1 && ($bookingCount > 0 || $scheduleVisitCount > 0)) {
                    $type = 'error';
                    $message = "You can't deactivate this account. Property owner have booking in progress.";
                } else {
                    if ($active == 1) {

                        $update_arr = array('status' => 0);
                        $this->User->where('id', $id)->update($update_arr);
                    } else {
                        $update_arr = array('status' => 1);
                        $this->User->where('id', $id)
                            ->update($update_arr);
                    }
                    $message = trans('flash.success.user_status_updated_successfully');
                }
                $type = 'success';
            } else {
                $message =  trans('flash.error.oops_something_went_wrong_updating_record');
                $type = 'warning';
            }
        } else {
            $message =  trans('flash.error.oops_something_went_wrong_updating_record');
            $type = 'warning';
        }
        $response['status_code'] = 200;
        $response['message'] = $message;
        $response['type'] = $type;
        return $response;
    }

    public function saveProfilePictureMedia($request)
    {
        $filename = uploadWithResize($request->file('files'),  'users/');
        if ($request->get('user_id')) {
            $user = $this->User->find($request->get('user_id'));
            /*
            $oldFilename = $user->FileExistsPath;
            $oldFilenameThumb = $user->FileExistsThumbPath;
            $oldName = $user->image;
            */
            $user->image = $filename;

            if ($user->save()) {
                /*
                if ($oldName != 'noimage.jpg') {
                    if (\File::exists($oldFilename)) {
                        \File::delete($oldFilename);
                    }
                    if (\File::exists($oldFilenameThumb)) {
                        \File::delete($oldFilenameThumb);
                    }
                }
                */
            }
        }
        $response['status_code'] = 250;
        $response['status'] = true;
        $response['s3FullPath'] = \Storage::disk('s3')->url('users/' . $filename);
        $response['filename'] =  $filename;
        return $response;
    }

    public function updateUserPassword($request)
    {
        $this->resetPassword($this->getRecordBySlug($request->slug), $request->password);
        $response['message'] = trans('flash.success.password_has_been_changed');
        $response['type'] = 'success';
        $response['status_code'] = 200;
        $response['reset'] = 'true';
        return $response;
    }

    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();
    }

    public function changeDocumentVerificationStatus($request, $id)
    {
        $vendor = $this->Vendor->find($id);
        if ($vendor) {
            $id = $vendor->id;
            $active = $vendor->status;
            $status = $request->get('status') == 'approve' ? 'approved' : 'rejected';
            if ($id != null) {
                if ($status == 'approved') {
                    $update_arr = array('status' => 'approved', 'action_date' => now());
                    $vendor->where('id', $id)->update($update_arr);
                    $this->EmailNotificationsRepo->sendVendorVerificationApprovedStatusEmail($vendor, $status);
                } else {
                    $update_arr = array('status' => 'rejected', 'action_date' => now());
                    $vendor->where('id', $id)->update($update_arr);
                    $this->EmailNotificationsRepo->sendVendorVerificationDeclineStatusEmail($vendor, $status);
                }
                $message = trans('flash.success.status_updated_successfully');
                $type = 'success';
            } else {
                $message =  trans('flash.error.oops_something_went_wrong_updating_record');
                $type = 'warning';
            }
        } else {
            $message =  trans('flash.error.oops_something_went_wrong_updating_record');
            $type = 'error';
        }
        $response['status'] = true;
        $response['message'] = $message;
        $response['type'] = $type;
        return $response;
    }

    public function update($request, $id)
    {
        try {
            $filleable = $request->only('username', 'name', 'email', 'phone', 'dob');
            if ($request->get('password')) {
                $filleable['password'] = trim(Hash::make($request['password']));
            }
            if ($request->get('image')) {
                $filleable['image'] = $request['image'];
            }
            $user = $this->User->find($id);
            $user->fill($filleable);
            $user->save();
            $response['message'] = trans('flash.success.profile_updated_successfully');
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function destroy($id)
    {
        return $this->User->destroy($id);
    }
}
