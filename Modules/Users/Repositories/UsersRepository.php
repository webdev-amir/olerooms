<?php

namespace Modules\Users\Repositories;

use App\Models\User;
use DB, Mail, Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Roles\Entities\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository;

class UsersRepository implements UsersRepositoryInterface
{

    public $User;

    function __construct(
        User $User,
        Role $Role,
        EmailNotificationsRepository $EmailNotificationsRepo
    ) {
        $this->User = $User;
        $this->Role = $Role;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
    }

    public function getRecord($id)
    {
        return $this->User->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->User->where('slug', $slug)->withTrashed()->first();
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
        if ($request->get('email')) {
            $users->where('email', $request->get('email'));
        }

        if ($request->get('phone')) {
            $users->where('phone', 'LIKE', "%" . $request->get('phone') . "%");
        }

        if ($request->get('sort')) {
            $users->orderBy($request->get('sort'), $request->get('direction'));
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
        return $users->orderBy('id', 'desc')->withTrashed()->paginate(10);
    }

    public function store($request)
    {
        try {
            $role = $this->Role->where('slug', 'customer')->first();
            if (!empty($role)) {
                $chkEmail = User::where(['email' => $request['email'], 'role_id' => $role->id])->first();
                if ($chkEmail) {
                    $response['message'] = 'Customer email already exists!';
                    $response['type'] = 'error';
                    return $response;
                }
                $chkPhone = User::where(['phone' => $request['phone'], 'role_id' => $role->id])->first();
                if ($chkPhone) {
                    $response['message'] = 'Customer phone number already exists!';
                    $response['type'] = 'error';
                    return $response;
                }
            }

            $filleable = $request->only('slug', 'username', 'name', 'email', 'phone', 'address', 'city', 'dob', 'gender', 'marital_status', 'role_id');
            $password  = $request['password'];
            $filleable['password'] = trim(Hash::make($request['password']));
            $filleable['email_verified_at'] = now();
            $filleable['role_id'] = $role->id;
            if ($request->get('image')) {
                $filleable['image'] = $request['image'];
            }
            if ($user = $this->User->create($filleable)) {
                $role = $this->Role->where('slug', 'customer')->first();
                User::$guard_name = 'web';
                if ($role) {
                    //Assign Customer Role
                    $user->assignRole([$role->id]);
                    $this->EmailNotificationsRepo->sendWelcomeEmailForUser($user, $password);
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
            $change = $this->User->withTrashed()->find($id);
            $active = $change->status;
            if ($id != null) {
                if ($active == 1) {
                    $update_arr = array('status' => 0);
                    $this->User->where('id', $id)->update($update_arr);
                } else {
                    $update_arr = array('status' => 1);
                    $this->User->where('id', $id)
                        ->update($update_arr);
                }
                $message = trans('flash.success.user_status_updated_successfully');
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
        $filename = uploadWithResize($request->file('files'), 'users/');
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

    public function update($request, $id)
    {
        try {
            $filleable = $request->only('username', 'name', 'email', 'phone', 'address', 'city', 'dob', 'gender', 'marital_status');
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

    public function destroy($id)
    {
        return $this->User->destroy($id);
    }
}
