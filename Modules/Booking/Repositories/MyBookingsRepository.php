<?php

namespace Modules\Booking\Repositories;

use Carbon\Carbon;
use DB, Mail, Session, Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
//use Modules\Payments\Entities\Payments;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Illuminate\Database\Eloquent\Builder;
use App\Providers\RouteServiceProvider;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepository;
use Modules\Notifications\Repositories\NotificationRepositoryInterface as NotificationRepositoryInterface;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepo;
use Modules\Notifications\Entities\Notifications;
use Modules\Property\Entities\Property;
use Modules\Property\Entities\PropertyOffers;
use Modules\Coupon\Entities\Coupon;
use Modules\Booking\Entities\Booking;
use Illuminate\Support\Facades\URL;

use Modules\PropertyType\Entities\PropertyType;

class MyBookingsRepository implements MyBookingsRepositoryInterface
{
    function __construct(
        EmailNotificationsRepository $EmailNotificationsRepository,
        NotificationRepositoryInterface $NotificationRepositoryInterface,
        EmailNotificationsRepo $EmailNotificationsRepo,
        Property $Property,
        Booking $Booking,
        PropertyOffers $PropertyOffers
        //Payments $Payments,
    ) {
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
        $this->NotificationsRepository = $NotificationRepositoryInterface;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
        $this->Property = $Property;
        $this->Booking = $Booking;
        $this->PropertyOffers = $PropertyOffers;
    }

    public function getMyPaymentsHistory($request)
    {
        $payments =  $this->Payments->with(['order', 'payplan', 'order.plan', 'order.plan.userPlan'])->where('id', '!=', 0)->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->whereHas('order', function (Builder $q) {
            $q->where('user_id', auth()->user()->id);
            $q->where('is_paid', 1);
        });
        return $payments->paginate(10);
    }

    public function getDashboardRecord()
    {
        $response['propertyCount'] = $this->Property->where('user_id', auth()->user()->id)->count();
        return $response;
    }

    public function getAllNotifications($request)
    {
        $notifications = Notifications::where('user_id', auth()->id());
        Notifications::where('user_id', auth()->id())->update(array('read_at' => now()));
        if ($request->get('readStatus')) {
            $notifications = $notifications->where('read_at', $request('readStatus'));
        }
        return $notifications->orderBy('created_at', 'DESC')->paginate(\config::get('custom.default_pagination'));
    }

    public function submitUserProfileVerificationData($request)
    {
        if (!auth()->user()->is_profileVerifired()) {
            $userProfileVerify = auth()->user()->userCompleteProfileVerifired();
            $userProfileVerify->create($request->all());
             $response['status_code'] = 200;
             $response['type'] = 'success';
             $response['url'] = route(RouteServiceProvider::VENDOR_HOME_ROUTE);
             $response['message'] = 'Profile Verification request send successfully, It will take 2-3 days for review your documets';
        }else{
               $userProfileVerify = auth()->user()->userCompleteProfileVerifired;
            if($userProfileVerify->status == 'pending'){
                $response['status_code'] = 200;
                $response['type'] = 'warning';
                $response['url'] = route(RouteServiceProvider::VENDOR_HOME_ROUTE);
                $response['message'] = 'Profile Verification request already in progess';
            }elseif($userProfileVerify->status == 'approved'){
                $response['status_code'] = 200;
                $response['type'] = 'warning';
                $response['url'] = route(RouteServiceProvider::VENDOR_HOME_ROUTE);
                $response['message'] = 'Your profile already verified by admin';
            }
        }
         return $response;
    }

    public function updateUserProfileDetails($request)
    {
        $filleable = $request->only('name');
        if ($request->image) {
            $filleable['image'] = $request->image;
        }

        if (auth()->user()->update($filleable)) {
            if(auth()->user()->userCompleteProfileVerifired){
                if ($request->logo_image) {
                    $profileVerify = auth()->user()->userCompleteProfileVerifired;
                    $profileVerify->logo_image = $request->logo_image;
                    $profileVerify->save();
                }
            }
            return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully')];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function deactivateAccount($request)
    {
        auth()->user()->deactivate_at = now();
        if (auth()->user()->save()) {
            session()->flush();
            Auth::logout();
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Account Deactivated Successfully','url'=>route('vendor.login'),'modelClose'=>'deactivateAccount'];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function deleteAccount($request)
    {      

        $user = auth()->user();

        if (auth()->user()->delete()) {
            $this->EmailNotificationsRepo->sendDeleteAccountMailandNotification($user);
            session()->flush();
            Auth::logout();            
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Account deleted successfully','url'=>route('vendor.login'),'modelClose'=>'deleteAcoount'];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }
    public function storePropertyOffer($request)
    {   
        $chk = $this->PropertyOffers->where('property_id',$request->property_id)->where('coupon_id',$request->coupon_id)->first();
        if($chk){
            $this->PropertyOffers->where('id',$chk->id)->delete();
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Offer removed successfully','url'=>route('vendor.myproperty'),'modelClose'=>'applyOffer'];
        }else{
            $insert['property_id'] = $request->property_id;
            $insert['coupon_id'] = $request->coupon_id;
            $this->PropertyOffers->create($insert);
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Offer applied successfully','url'=>route('vendor.myproperty'),'modelClose'=>'applyOffer'];
        }
    }

    public function getRecord($id)
    {
        return $this->Property->find($id);
    }

    public function getAllPropertyTypes()
    {
        return $property_type = PropertyType::where('status', 1)->get();
    }

    public function getAllCoupons($request){
       $base_url = URL::to('storage/app/public/coupon/');
        $coupon = Coupon::where('status', 1)->get();
        
        foreach($coupon as $key => $val){
            /* if(file_exists($base_url."/".$val->image)){
                $val->image = $base_url."/".$val->image;
            }else{
                $val->image = url('/').'/images/no-image.jpg';
            } */
            $val->image = $val->PicturePath;
            $appliedOffers = PropertyOffers::where('property_id',$request->property_id)->where('coupon_id',$val->id)->count();
            if($appliedOffers > 0){
                $val->is_offer_applied = 1;
            }else{
                $val->is_offer_applied = 0;
            }
        }
        return $coupon;
    }
    

    public function getAllMyBookingsRecord($request)
    {
        $bookings = $this->Booking->where('user_id', 138)->with(['propertyRooms','propertyAmenities','propertyOffers','city']);
        $to  = date("Y-m-d",strtotime($request->get('to')."+1 day"));
        $from  = date("Y-m-d",strtotime($request->get('from')));
        if(!empty($from)){
            $bookings = $bookings->where('created_at', '>=', $from);
        }
        if(!empty($to)){
            $bookings = $bookings->where('created_at', '<=', $to);
        }

        // if ($request->get('sortby')) {
        //     $bookings = $bookings->where('property_type_id', $request->get('sortby'));
        // }

        return $bookings->latest()->paginate(\config::get('custom.default_pagination'));
    }

    public function mypropertyChangeStatus($request)
    {
        $property = $this->getRecord($request->get('id'));
        if ($property) {
            $id = $property->id;
            $change = $this->Property->find($id);
            $publish = (int) $change->is_publish;
            if ($publish == 0) {
                $update_arr = array('is_publish' => true);
                $message = 'Property Published successfully';
                $this->Property->where('id', $id)->update($update_arr);
            } else {
                $update_arr = array('is_publish' => false);
                $this->Property->where('id', $id)
                    ->update($update_arr);
                $message = 'Property Un-published successfully';
            }
            $type = 'success';
        } else {
            $message =  trans('flash.error.oops_something_went_wrong_updating_record');
            $type = 'warning';
        }

        $response['status'] = true;
        $response['message'] = $message;
        $response['type'] = $type;
        return $response;
    }

    public function mypropertyUploadSelfieMedia($request)
    {
         $filename = uploadWithResize($request->file('files'),'myproperty/');
         $response['status'] = true;
         $response['filename'] = $filename;
         $response['status_code'] = 200;
         return $response;
    }

    public function mypropertyUploadAgreementMedia($request)
    {
         $filename = uploadOnS3Bucket($request->file('files'),'myproperty/');
         $response['status'] = true;
         $response['filename'] = $filename;
         $response['status_code'] = 250;
         return $response;
    }

    public function updateUploadSelfieOrAgreement($request)
    {
        try {
            $filleable = $request->only('upload_selfie,upload_agreement');          
            $record = $this->getRecord($request->get('property_id'));
            if($request->get('upload_selfie')){
                $update_arr = array('upload_selfie' => $request->get('upload_selfie'), 'status_selfie'=>'pending');
                $modalId = 'upload_selfie';    
            }else{
                $update_arr = array('upload_agreement' => $request->get('upload_agreement'), 'status_agreement'=>'pending');
                $modalId = 'upload_agrement';
            }
            
            $record->update($update_arr);

            $response['status'] = true;
            $response['upload_agreement'] = true;
            $response['message'] = 'Uploaded successfully';
            $response['type'] = 'success';
            $response['modelClose'] = $modalId;
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
        return $this->Property->destroy($id);
    }
}
