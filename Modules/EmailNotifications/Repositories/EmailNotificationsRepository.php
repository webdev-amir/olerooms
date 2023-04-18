<?php

namespace Modules\EmailNotifications\Repositories;

use Carbon\Carbon;
use DB,
    Mail,
    Session,
    Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Modules\EmailTemplates\Entities\EmailTemplate;
use App\Repositories\Common\CommonRepository;
use Exception;
use Modules\Booking\Entities\Booking;
use Modules\Notifications\Repositories\NotificationRepositoryInterface as NotificationRepositoryInterface;

class EmailNotificationsRepository implements EmailNotificationsRepositoryInterface
{

    function __construct(NotificationRepositoryInterface $NotificationRepositoryInterface, EmailTemplate $EmailTemplate, CommonRepository $CommonRepo)
    {
        $this->CommonRepo = $CommonRepo;
        $this->EmailTemplate = $EmailTemplate;
        $this->NotificationsRepository = $NotificationRepositoryInterface;
    }

    /*
     * Send Welcome Email to User
     * */

    public function sendWelcomeEmailForUser($user, $password)
    {
        $route = route('auth.login');
        $emailtemplate = EmailTemplate::where('slug', 'create-customer-welcome')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $body = str_replace('[username]', $user->FullName, $body);
        $body = str_replace('[email]', $user->email, $body);
        $body = str_replace('[password]', $password, $body);
        $body = str_replace('[loginurl]', $route, $body);
        $jobData = [
            'content' => $body,
            'user' => $user,
            'to' => $user->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    /*
     * Send Welcome Email to Vendor
     * */

    public function sendWelcomeEmailForVendor($user)
    {
        $route = route('vendor.login');
        $emailtemplate = EmailTemplate::where('slug', 'create-vendor-welcome')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $body = str_replace('[username]', $user->FullName, $body);
        $body = str_replace('[loginurl]', $route, $body);
        $jobData = [
            'content' => $body,
            'user' => $user,
            'to' => $user->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }



    /*
     * Send Welcome Email to Agent
     * */
    public function sendWelcomeEmailToAgent($user,$password)
    {
        $route = route('agent.login');
        $emailtemplate = EmailTemplate::where('slug', 'create-agent-welcome')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $search_fields = ['[username]', '[email]','[password]', '[loginurl]'];
        $replace_data = [$user->FullName,$user->email, $password, $route];
        $body = str_replace($search_fields, $replace_data, $body);
        $jobData = [
            'content' => $body,
            'user' => $user,
            'to' => $user->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData))->afterResponse();
        
    }


    /*
     * Send Welcome Email to Agent
     * */
    public function sendWelcomeEmailToCompany($user,$password)
    { 
        $route = route('company.login');
        $emailtemplate = EmailTemplate::where('slug', 'create-company-welcome')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $search_fields = ['[username]', '[email]','[password]', '[loginurl]'];
        $replace_data = [$user->FullName, $user->email, $password, $route];
        $body = str_replace($search_fields,$replace_data, $body);
        $jobData = [
            'content' => $body,
            'user' => $user,
            'to' => $user->email,
            'subject' => $subject
        ];
        $data = dispatch(new \App\Jobs\SendEmailJob($jobData))->afterResponse();
    }

    /*
     * Send Vendor Register Notify Mail To Admin
     * */

    public function sendVendorRegisterNotifyMailToAdmin($user)
    {
        $adminemail = $this->CommonRepo->getConfigValue('adminemail');
        $emailtemplate = EmailTemplate::where('slug', 'vendor-register-notify-admin')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $body = str_replace('[vendor_name]', $user->FullName, $body);
        $jobData = [
            'content' => $body,
            'user' => $user,
            'to' => $adminemail,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    public function sendTestMailandNotificationCronEmail()
    {
        try {
            $adminemail = $this->CommonRepo->getConfigValue('adminemail');
            $emailtemplate = $this->EmailTemplate->where('slug', 'send-test-mail-and-notification-cron')->first();
            $body = $emailtemplate->body;
            $search_fields = ['[username]'];
            $replace_data = ['Test Admin'];
            $body = str_replace($search_fields, $replace_data, $body);
            $jobData = [
                'content' => $body,
                'to' => $adminemail,
                'subject' => $emailtemplate->subject
            ];
            dispatch(new \App\Jobs\SendEmailJob($jobData));
            $notificationData = [
                'user_id' => 1,
                'data' => json_encode([
                    'notification' => [
                        'message' => $emailtemplate->subject,
                        'full_content' => $body,
                        'name' => 'Test Admin'
                    ]
                ])
            ];
            $this->NotificationsRepository->addNotification($notificationData);
            $response['message'] = "Test Cron worked Successfully.";
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = 'Something went wrong!';
            $response['type'] = 'error';
        }
        return $response;
    }


    public function sendAccountDeletedInfoEmail($email)
    {
        $data = [
            'content' => "Your account has been deleted. To retrieve your account login within 15 days.",
            'to' => $email,
            'subject' => 'Account Deleted - OLEROOMS'
        ];

        dispatch(new \App\Jobs\SendEmailJob($data));
    }

    /*
     * Send Document Verfication document decline status Email to Vendor
     * */

    public function sendVendorVerificationDeclineStatusEmail($vendor, $status)
    {
        $emailtemplate = EmailTemplate::where('slug', 'vendor-document-verification-decline')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $name = $vendor->user->name;
        $email = $vendor->user->email;
        $route_link = route('vendor.dashboard');
        $search_fields = ['[username]', '[status]', '[link]'];
        $replace_data = [$name, $status, $route_link];
        $body = str_replace($search_fields, $replace_data, $body);
        $VendorEmailData = [
            'content' => $body,
            'to' => $email,
            'subject' => $subject
        ];

        dispatch(new \App\Jobs\SendEmailJob($VendorEmailData));

        $notificationData = [
            'user_id' => $vendor->user->id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'message' => 'Your document verification is ' . $status . ' by Admin.',
                    'name' => $vendor->user->name
                ]
            ])
        ];
        $this->NotificationsRepository->addNotification($notificationData);
    }

    /*
     * Send Document Verfication document approved status Email to Vendor
     * */

    public function sendVendorVerificationApprovedStatusEmail($vendor, $status)
    {
        $emailtemplate = EmailTemplate::where('slug', 'vendor-document-verification-accept')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $name = $vendor->user->name;
        $email = $vendor->user->email;
        $route_link = route('vendor.dashboard');
        $search_fields = ['[username]', '[status]', '[link]'];
        $replace_data = [$name, $status, $route_link];
        $body = str_replace($search_fields, $replace_data, $body);
        $VendorEmailData = [
            'content' => $body,
            'to' => $email,
            'subject' => $subject
        ];

        dispatch(new \App\Jobs\SendEmailJob($VendorEmailData));

        $notificationData = [
            'user_id' => $vendor->user->id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'message' => 'Your document verification have ' . $status . ' by Admin.',
                    'name' => $vendor->user->name
                ]
            ])
        ];
        $this->NotificationsRepository->addNotification($notificationData);
    }

    /*
     * Send Property added mail to admin
     * */

    public function sendAddPropertyEmailForAdmin($user, $property)
    {
        $adminemail = $this->CommonRepo->getConfigValue('adminemail');
        $emailtemplate = EmailTemplate::where('slug', 'property-added-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1">
        <tbody>
            <tr>
                <td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%"> </td>
            </tr>
            <tr>
                <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property ID</span><br><span><b style="color:#002554;">' . $property->id . '</b></span> </td>
                <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($property->status) . '</b></span> </td>
            </tr>
            <tr>
                <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $property->PropertValue . '</b></span> </td>
              
            </tr>';
        $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[username]', $user->FullName, $body);
        $jobData = [
            'content' => $body,
            'user' => $user,
            'to' => $adminemail,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    /*
     * Send Property updated mail to admin
     * */

    public function sendEditPropertyEmailtoAdmin($user, $property)
    {
        $adminemail = $this->CommonRepo->getConfigValue('adminemail');
        $emailtemplate = EmailTemplate::where('slug', 'property-updated-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1">
        <tbody>
            <tr>
                <td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%"> </td>
            </tr>
            <tr>
                <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property ID</span><br><span><b style="color:#002554;">' . $property->id . '</b></span> </td>
                <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($property->status) . '</b></span> </td>
            </tr>
            <tr>
                <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $property->PropertValue . '</b></span> </td>
              
            </tr>';
        $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[username]', $user->FullName, $body);
        $jobData = [
            'content' => $body,
            'user' => $user,
            'to' => $adminemail,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    public function sendDeleteAccountMailandNotification($user)
    {
        try {
            $emailtemplate = $this->EmailTemplate->where('slug', 'delete-account')->first();
            $body = $emailtemplate->body;
            $search_fields = ['[username]'];
            $replace_data = [$user->name];
            $body = str_replace($search_fields, $replace_data, $emailtemplate->body);
            $jobData = [
                'content' => $body,
                'to' => $user->email,
                'subject' => $emailtemplate->subject
            ];
            dispatch(new \App\Jobs\SendEmailJob($jobData));
            $notificationData = [
                'user_id' => $user->id,
                'data' => json_encode([
                    'notification' => [
                        'message' => $emailtemplate->subject,
                        'full_content' => $body,
                        'name' => $user->name
                    ]
                ])
            ];
            $this->NotificationsRepository->addNotification($notificationData);
            $response['message'] = "User Account Deleted Successfully.";
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = 'Something went wrong!';
            $response['type'] = 'error';
        }
        return $response;
    }

    /*
     * Send Vendor Selfie or Agreement Staus Email by Admin
     * */

    public function sendVendorSelfiAgreementPropertyStatusEmail($property, $status_type, $status, $status_type_date)
    {
        $emailtemplate = EmailTemplate::where('slug', 'property-status-email')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $property_name = $property->property_name;
        $search_fields = ['[name]', '[username]', '[status_type]', '[status]', '[property_name]', '[status_type_date]'];
        $replace_data = ['Admin', $property->author->name, $status_type, $status, $property_name, $status_type_date];
        $body = str_replace($search_fields, $replace_data, $body);
        $contactEmail = $property->author->email;
        $VendorPropertyStatusEmailData = [
            'content' => $body,
            'to' => $contactEmail,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($VendorPropertyStatusEmailData));

        $notificationData = array(
            'user_id' => $property->author->id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'link' => route('vendor.myproperty'),
                    'image' => auth()->user()->PicturePath,
                    'message' => 'Your Property ' . $status_type . ' has been ' . $status . ' by Admin.'
                ]
            ]),
        );
        $this->NotificationsRepository->addNotification($notificationData);
    }

    public function sendSchduleVisitBookingEmail($vendor, $booking)
    {
        // pr();
        $emailtemplate = EmailTemplate::where('slug', 'schedule-visit')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $name = $vendor->FullName;
        $email = $vendor->email;
        $data = $booking['customer_name'] . ' booked property ' . $booking['property_name'] . ' for Booking Id #' . $booking['schedule_code'] . '.';

        $search_fields = ['[username]', '[DATA]'];
        $replace_data = [$name, $data];
        $body = str_replace($search_fields, $replace_data, $body);
        $VendorEmailData = [
            'content' => $body,
            'to' => $email,
            'subject' => $subject
        ];

        dispatch(new \App\Jobs\SendEmailJob($VendorEmailData));
        $notificationData = [
            'user_id' => $vendor->id,
            'create_user' => $booking['customer_id'],
            'data' => json_encode([
                'notification' => [
                    'image' => $booking['customer_image_path'],
                    'link' => route('vendor.dashboard.myvisit.details', $booking['slug']),
                    'message' => $booking['customer_name'] . ' booked property ' . $booking['property_name'] . ' for schedule visit.',
                    'name' => $vendor->FullName
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    public function sendSchduleVisitBookingEmailUser($order, $booking)
    {
        $notificationData = [
            'user_id' => $order->user_id,
            'create_user' => $order->customer->id,
            'type' => 'scheduling',
            'type_id' =>$order->id,
            'data' => json_encode([
                'notification' => [
                    'image' => $order->customer->PicturePath,
                    'link' => route('customer.dashboard.myvisit.details', $order->slug),
                    'message' => 'Property - ' . $booking['property_name'] . ' request sent to owner for schedule visit.',
                    'name' => $booking['customer_name']
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    public function sendPropertyBookingEmailVendor($bookingOrder)
    {
        $emailtemplate = EmailTemplate::where('slug', 'booking-payment-notify-vendor')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $name = $bookingOrder->Property->author->name;
        $email = $bookingOrder->Property->author->email;   
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;"> #' . $bookingOrder->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($bookingOrder->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Title</span><br><span><b style="color:#002554;">' . $bookingOrder->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($bookingOrder->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $bookingOrder->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $bookingOrder->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . $bookingOrder->check_in_date . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($bookingOrder->check_out_date ? $bookingOrder->check_out_date : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $search_fields = ['[username]', '[DATA]'];
        $replace_data = [$name, $data];
        $body = str_replace($search_fields, $replace_data, $body);
        $VendorEmailData = [
            'content' => $body,
            'to' => $email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($VendorEmailData))->afterResponse();
        $notificationData = [
            'user_id' => $bookingOrder->vendor_id,
            'data' => json_encode([
                'notification' => [
                    'image' => $bookingOrder->customer->PicturePath,
                    'link' => route('vendor.myproperty'),
                    'message' => $bookingOrder->name . ' requested for booking property ' . $bookingOrder->property->property_name . '.',
                    'name' => $bookingOrder->vendor->FullName
                ]
            ])
        ];
        $this->NotificationsRepository->addNotification($notificationData);
        $this->sendPropertyBookingRequestEmailAdmin($bookingOrder);
    }


    public function sendPropertyBookingRequestEmailAdmin($bookingOrder)
    {
        $adminemail = $this->CommonRepo->getConfigValue('adminemail');
        $emailtemplate = EmailTemplate::where('slug', 'booking-payment-notify-vendor')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
       
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;"> #' . $bookingOrder->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($bookingOrder->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Title</span><br><span><b style="color:#002554;">' . $bookingOrder->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($bookingOrder->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $bookingOrder->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $bookingOrder->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . $bookingOrder->check_in_date . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($bookingOrder->check_out_date ? $bookingOrder->check_out_date : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $search_fields = ['[username]', '[DATA]'];
        $replace_data = ['ADMIN', $data];
        $body = str_replace($search_fields, $replace_data, $body);
        $AdminEmailData = [
            'content' => $body,
            'to' => $adminemail,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($AdminEmailData))->afterResponse();
        
    }
    public function sendPropertyBookingEmailUser($bookingOrder)
    {
        $emailtemplate = EmailTemplate::where('slug', 'booking-payment-notify-user')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
       
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;"> #' . $bookingOrder->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($bookingOrder->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Title</span><br><span><b style="color:#002554;">' . $bookingOrder->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($bookingOrder->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $bookingOrder->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $bookingOrder->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . $bookingOrder->check_in_date . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($bookingOrder->check_out_date ? $bookingOrder->check_out_date : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $search_fields = ['[username]', '[property_name]', '[DATA]'];
        $replace_data = [$bookingOrder->name, $bookingOrder->property->property_name, $data];
        $body = str_replace($search_fields, $replace_data, $body);
        $userEmailData = [
            'content' => $body,
            'to' => $bookingOrder->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($userEmailData))->afterResponse();
        /*
        $notificationData = [
            'user_id' => $bookingOrder->user_id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'image' => $bookingOrder->customer->PicturePath,
                    'link' => route('customer.dashboard.mybookings.details', $bookingOrder->slug),
                    'message' => 'Property - ' . $bookingOrder->property->property_name . ' booking request sent to owner.',
                    'name' => $bookingOrder->name
                ]
            ])
        ];
        $this->NotificationsRepository->addNotification($notificationData);
        */
    }

    /*
    ** send booking confirmation mail for user by vendor
    */
    public function sendBookingConfirmedEmailForUser($booking)
    {
        $emailtemplate = EmailTemplate::where('slug', 'booking-request-accepted')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $userRequest = '“Your Booking has been confirmed by Property Owner ' . $booking->vendor->FullName . '”.';
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;"> #' . $booking->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($booking->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Title</span><br><span><b style="color:#002554;">' . $booking->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($booking->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $booking->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $booking->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . display_date($booking->check_in_date) . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($booking->check_out_date ? display_date($booking->check_out_date) : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[hostname]', $booking->vendor->FullName, $body);
        $body = str_replace('[username]', $booking->name, $body);
        $body = str_replace('[user_request]', $userRequest, $body);
        $jobData = [
            'content' => $body,
            'to' => $booking->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));

        if(!$booking->booking_reject_confirm_type){

        $notificationData = [
            'user_id' => $booking->user_id,
            'create_user' => $booking->vendor->id,
            'type' => 'booking',
            'type_id' =>$booking->slug,
            'data' => json_encode([
                'notification' => [
                    'image' => $booking->vendor->PicturePath,
                    'link' => route('customer.dashboard.mybookings.details', $booking->slug),
                    'message' => 'Your booking has been confirmed by ' . $booking->vendor->FullName . '.',
                    'name' => $booking->name
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    }

    /*
    ** send booking confirmation mail for vendor by vendor
    */
    public function sendBookingConfirmedEmailForVendor($booking)
    {
        $emailtemplate = EmailTemplate::where('slug', 'booking-request-accepted')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $userRequest = '“You confirmed Booking request by ' . $booking->name . '”.';
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;"> #' . $booking->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($booking->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Title</span><br><span><b style="color:#002554;">' . $booking->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($booking->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $booking->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $booking->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . display_date($booking->check_in_date) . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($booking->check_out_date ? display_date($booking->check_out_date) : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[username]', $booking->name, $body);
        $body = str_replace('[hostname]', $booking->vendor->FullName, $body);
        $body = str_replace('[user_request]', $userRequest, $body);
        $jobData = [
            'content' => $body,
            'to' => $booking->vendor->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));

        $notificationData = [
            'user_id' => $booking->vendor_id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' => route('customer.dashboard.mybookings.details', $booking->slug),
                    'message' => 'You confirmed Booking request by ' . $booking->name . '.',
                    'name' =>  $booking->name
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    /*
    ** send booking reject email for user by vendor
    */
    public function sendBookingRejectedEmailForUser($booking)
    {
        $emailtemplate = EmailTemplate::where('slug', 'booking-request-rejected')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $userRequest = '“Your Booking has been rejected by property owner ' . $booking->vendor->FullName . '”.';
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;"> #' . $booking->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($booking->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Title</span><br><span><b style="color:#002554;">' . $booking->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($booking->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $booking->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $booking->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . display_date($booking->check_in_date) . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($booking->check_out_date ? display_date($booking->check_out_date) : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[hostname]', $booking->vendor->FullName, $body);
        $body = str_replace('[username]', $booking->name, $body);
        $body = str_replace('[user_request]', $userRequest, $body);
        $jobData = [
            'content' => $body,
            'to' => $booking->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
        if(!$booking->booking_reject_confirm_type){
        $notificationData = [
            'user_id' => $booking->user_id,
            'data' => json_encode([
                'notification' => [
                    'link' => route('customer.dashboard.mybookings.details', $booking->slug),
                    'image' => $booking->vendor->PicturePath,
                    'message' => 'Your booking has been rejected by ' . $booking->vendor->FullName . '.',
                    'name' => $booking->vendor->FullName
                    ]
                    ])
                ];
            $this->NotificationsRepository->addRealTimeNotifications($notificationData);
        }
    }

    /*
    ** send booking reject email for vendor by vendor
    */
    public function sendBookingRejectedEmailForVendor($booking)
    {
        $emailtemplate = EmailTemplate::where('slug', 'booking-request-rejected')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $userRequest = '“You rejected Booking request by ' . $booking->name . '”.';
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;"> #' . $booking->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($booking->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Title</span><br><span><b style="color:#002554;">' . $booking->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($booking->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $booking->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $booking->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' .display_date( $booking->check_in_date ). '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($booking->check_out_date ? display_date($booking->check_out_date) : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[username]', $booking->name, $body);
        $body = str_replace('[hostname]', $booking->vendor->FullName, $body);
        $body = str_replace('[user_request]', $userRequest, $body);
        $jobData = [
            'content' => $body,
            'to' => $booking->vendor->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));

        $notificationData = [
            'user_id' => $booking->vendor_id,
            'data' => json_encode([
                'notification' => [
                    'link' => route('customer.dashboard.mybookings.details', $booking->slug),
                    'image' => auth()->user()->PicturePath,
                    'message' => 'You rejected Booking request by ' . $booking->name . '.',
                    'name' => auth()->user()->FullName
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    /*
     * * user send booking cancellation request to admin
     */

    public function sendBookingCancellationRequestEmail($user, $booking)
    {
        $adminemail = $this->CommonRepo->getConfigValue('adminemail');

        $emailtemplate = EmailTemplate::where('slug', 'booking-cancellation-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $userRequest = 'A new Booking cancellation request has been sent by ' . $user->FullName . ' for Booking Id #' . $booking->code . '.';
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;">#' . $booking->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($booking->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Name</span><br><span><b style="color:#002554;">' . $booking->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($booking->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $booking->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $booking->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . display_date($booking->check_in_date) . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($booking->check_out_date ? display_date($booking->check_out_date) : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';

        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[bookingid]', $booking->code, $body);
        $body = str_replace('[hostname]', 'Admin', $body);
        $body = str_replace('[user_request]', $userRequest, $body);

        $jobData = [
            'content' => $body,
            'to' => $adminemail,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    /*
     * * user send schedule visit cancellation request to admin
     */

    public function sendVisitCancellationRequestEmail($user, $booking)
    {
        $adminemail = $this->CommonRepo->getConfigValue('adminemail');
        $emailtemplate = EmailTemplate::where('slug', 'schedule-visit-cancellation-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $authorname = 'Admin';

        $data = 'A new Schedule Visit cancellation request has been sent by ' . $user->FullName . ' for Booking Id #' . $booking->schedule_code . '.';

        $search_fields = ['[hostname]', '[DATA]'];
        $replace_data = [$authorname, $data];
        $body = str_replace($search_fields, $replace_data, $body);
        $userEmailData = [
            'content' => $body,
            'to' => $adminemail,
            'subject' => $subject
        ];

        dispatch(new \App\Jobs\SendEmailJob($userEmailData));
    }

    /*
     * Send Review Notification and Mail to Vendor 
     * */

    public function sendReviewNotificationMailVendor($booking)
    {
        $emailtemplate = EmailTemplate::where('slug', 'review-mail-notify-vendor')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $authorname = $booking->Property->author->name;
        $email = $booking->Property->author->email;
        $search_fields = ['[author_name]', '[user_name]', '[property_name]'];
        $replace_data = [$authorname, auth()->user()->name, $booking->Property->property_name];
        $body = str_replace($search_fields, $replace_data, $body);
        $userEmailData = [
            'content' => $body,
            'to' => $email,
            'subject' => $subject
        ];

        dispatch(new \App\Jobs\SendEmailJob($userEmailData));
        $notificationData = [
            'user_id' => $booking->Property->author->id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' => route('manageProperty.show', [$booking->Property->slug]),
                    'message' => auth()->user()->name . ' has posted a review on ' . $booking->Property->property_name . '.',
                    'name' => $booking->name
                ]
            ])
        ];
        $this->NotificationsRepository->addNotification($notificationData);
    }

    /*
     * Send Review Mail to Admin 
     * */

    public function sendReviewMailAdmin($booking)
    {
        $emailtemplate = EmailTemplate::where('slug', 'review-mail-admin')->first();
        $adminemail = $this->CommonRepo->getConfigValue('adminemail');
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $search_fields = ['[user_name]', '[property_name]'];
        $replace_data = [auth()->user()->name, $booking->Property->property_name];
        $body = str_replace($search_fields, $replace_data, $body);

        $userAdminData = [
            'content' => $body,
            'to' => $adminemail,
            'subject' => 'New review added by user'
        ];
        dispatch(new \App\Jobs\SendEmailJob($userAdminData));
    }

    /*
     * * user send booking cancellation request to vendor
     */

    public function sendBookingCancellationRequestEmailToVendor($user, $booking)
    {
        $emailtemplate = EmailTemplate::where('slug', 'booking-cancellation-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $userRequest = 'A new Booking cancellation request has been sent by ' . $user->FullName . ' for Booking Id #' . $booking->code . '.';
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;">#' . $booking->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($booking->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Name</span><br><span><b style="color:#002554;">' . $booking->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($booking->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $booking->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $booking->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . display_date($booking->check_in_date) . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($booking->check_out_date ? display_date($booking->check_out_date) : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[bookingid]', $booking->code, $body);
        $body = str_replace('[hostname]', $booking->vendor->FullName, $body);
        $body = str_replace('[user_request]', $userRequest, $body);

        $jobData = [
            'content' => $body,
            'to' => $booking->vendor->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));

        $notificationData = [
            'user_id' => $booking->vendor_id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' => route('vendor.myproperty'),
                    'message' => $user->FullName . ' has been sent cancellation request for ' . $booking->property->property_name . ' Property.',
                    'name' => $user->FullName
                ]
            ])
        ];
        $this->NotificationsRepository->addNotification($notificationData);
    }

    /*
    ** admin accepted/declined against booking cancellation request sent by user
    */
    public function sendBookingCancellationRequestEmailToUserByAdmin($booking, $status)
    {
        $emailtemplate = EmailTemplate::where('slug', 'booking-cancellation-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $userRequest = 'Admin ' . $status . ' your cancellation request for Booking Id #' . $booking->code . '.';
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;">#' . $booking->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($booking->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Name</span><br><span><b style="color:#002554;">' . $booking->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($booking->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $booking->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $booking->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . display_date($booking->check_in_date) . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($booking->check_out_date ? display_date($booking->check_out_date) : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[bookingid]', $booking->code, $body);
        $body = str_replace('[hostname]', $booking->vendor->FullName, $body);
        $body = str_replace('[user_request]', $userRequest, $body);

        $jobData = [
            'content' => $body,
            'to' => $booking->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));

        $notificationData = [
            'user_id' => $booking->user_id,
            'create_user' => auth()->user()->id,
            'type' => 'booking',
            'type_id' =>$booking->slug,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' =>  route('customer.dashboard.mybookings.details', $booking->slug),
                    'message' => 'Admin ' . $status . ' your cancellation request for ' . $booking->property->property_name . ' Property.',
                    'name' =>  $booking->name
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    /*
     * * admin accepted/declined against booking cancellation request sent by user to vendor
     */

    public function sendBookingCancellationRequestEmailToVendorByAdmin($booking, $status)
    {
        $emailtemplate = EmailTemplate::where('slug', 'booking-cancellation-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $userRequest = 'Admin ' . $status . ' cancellation request sent by ' . $booking->user->FullName . ' for Booking Id #' . $booking->code . '.';
        $data = '<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">Booking Information </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking ID</span><br><span><b style="color:#002554;">#' . $booking->code . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">Status:</span><br><span><b>' . ucfirst($booking->status) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Property Name</span><br><span><b style="color:#002554;">' . $booking->property->property_name . '</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Booking Amount</span><br><span><b style="color:#002554;">' . numberformatWithCurrency($booking->total, 2) . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Property Type</span><br><span><b style="color:#002554;">' . $booking->property->propertyType->name . '</b></span></td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Email</span><br><span><b style="color:#002554;">' . $booking->email . '</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif; vertical-align: top;"><span style="font-size:12px;color:#777777;">Check-in Date</span><br><span><b style="color:#002554;">' . display_date($booking->check_in_date) . '</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">Check-Out Date</span><br><span><b style="color:#002554;">' . ($booking->check_out_date ? display_date($booking->check_out_date) : 'N/A') . '</b></span> </td></tr>';
        $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[DATA]', $data, $body);
        $body = str_replace('[bookingid]', $booking->code, $body);
        $body = str_replace('[hostname]', $booking->vendor->FullName, $body);
        $body = str_replace('[user_request]', $userRequest, $body);

        $jobData = [
            'content' => $body,
            'to' => $booking->vendor->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));

        $notificationData = [
            'user_id' => $booking->vendor_id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' => route('vendor.myproperty'),
                    'message' => 'Admin ' . $status . ' cancellation request sent by ' . $booking->user->FullName . ' for ' . $booking->property->property_name . ' Property.',
                    'name' => $booking->user->FullName
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    /*
     * * user send schedule visit cancellation request to vendor
     */

    public function sendVisitCancellationRequestEmailToVendor($user, $booking, $visitProperty)
    {
        $emailtemplate = EmailTemplate::where('slug', 'schedule-visit-cancellation-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $authorname = $visitProperty->vendor->FullName;
        $email = $visitProperty->vendor->email;

        $data = 'A new Schedule Visit cancellation request has been sent by ' . $user->FullName . ' for Booking Id #' . $booking->schedule_code . '.';

        $search_fields = ['[hostname]', '[DATA]'];
        $replace_data = [$authorname, $data];
        $body = str_replace($search_fields, $replace_data, $body);
        $userEmailData = [
            'content' => $body,
            'to' => $email,
            'subject' => $subject
        ];

        dispatch(new \App\Jobs\SendEmailJob($userEmailData));

        $notificationData = [
            'user_id' => $visitProperty->user_id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' => route('vendor.dashboard.myvisit.details', $booking->slug),
                    'message' => $user->FullName . ' has been sent Schedule Visit cancellation request for ' . $visitProperty->property->property_name . '.',
                    'name' => $user->FullName
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    /*
     * * admin accepted/declined visit cancellation request sent by user to user
     */

    public function sendVisitCancellationRequestEmailUser($booking, $status)
    {
        $emailtemplate = EmailTemplate::where('slug', 'schedule-visit-cancellation-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $authorname = $booking->customer->FullName;
        $email = $booking->customer->email;

        $data = 'Admin ' . $status . ' your Schedule Visit request for #' . $booking->schedule_code . '.';

        $search_fields = ['[hostname]', '[DATA]'];
        $replace_data = [$authorname, $data];
        $body = str_replace($search_fields, $replace_data, $body);
        $userEmailData = [
            'content' => $body,
            'to' => $email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($userEmailData));

        $notificationData = [
            'user_id' => $booking->user_id,
            'create_user' => auth()->user()->id,
            'type' => 'scheduling',
            'type_id' =>$booking->id,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' =>  route('customer.dashboard.myvisit.details', $booking->slug),
                    'message' => 'Admin ' . $status . ' your Schedule Visit request for #' . $booking->schedule_code . '.',
                    'name' => auth()->user()->FullName
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    /*
     * * admin accepted/declined visit cancellation request sent by user to vendor
     */

    public function sendVisitCancellationRequestEmailVendor($booking, $status, $visitProperty)
    {
        $emailtemplate = EmailTemplate::where('slug', 'schedule-visit-cancellation-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $authorname = $visitProperty->vendor->FullName;
        $email = $visitProperty->vendor->email;

        $data = 'Admin ' . $status . ' cancelled request sent by ' . $booking->customer->FullName . ' for ' . $booking->schedule_code . '.';

        $search_fields = ['[hostname]', '[DATA]'];
        $replace_data = [$authorname, $data];
        $body = str_replace($search_fields, $replace_data, $body);
        $userEmailData = [
            'content' => $body,
            'to' => $email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($userEmailData));

        $notificationData = [
            'user_id' => $visitProperty->user_id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' => route('vendor.dashboard.myvisit.details', $booking->slug),
                    'message' => 'Admin ' . $status . ' Schedule Visit cancelled request sent by ' . $booking->customer->FullName . ' for ' . $visitProperty->property->property_name . '.',
                    'name' => auth()->user()->FullName
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }

    public  function sendCreateUserEmail($request, $user)
    {
        if($user->hasRole('customer')){
            $verifyRoute = 'verification.verify';
        }elseif($user->hasRole('agent')){
            $verifyRoute = 'agent.verification.verify';
        }elseif($user->hasRole('company')){
            $verifyRoute = 'company.verification.verify';
        }else{
            $verifyRoute = 'verification.verify';
        }
        $verifyUrl = \URL::temporarySignedRoute($verifyRoute,
        \Illuminate\Support\Carbon::now()->addMinutes(\Illuminate\Support\Facades 
        \Config::get('auth.verification.expire', 60)),['id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),]
        );
        $emailtemplate = EmailTemplate::where('slug', 'activate-user')->first();
        $search_fields = ['[username]','[activationlink]','#'];
        $replace_data = [$user->name, $verifyUrl ,$verifyUrl];
        $body = str_replace($search_fields, $replace_data, $emailtemplate->body);
        $jobData = [
            'content' => $body, 
            'to' => $user->email,
            'subject' => $emailtemplate->subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    public  function sendVerifyEmail($request, $user)
    {
        if($user->hasRole('customer')){
            $verifyRoute = 'verification.verify';
        }elseif($user->hasRole('agent')){
            $verifyRoute = 'agent.verification.verify';
        }elseif($user->hasRole('company')){
            $verifyRoute = 'company.verification.verify';
        }elseif($user->hasRole('vendor')){
            $verifyRoute = 'owner.verification.verify';
        }else{
            $verifyRoute = 'verification.verify';
        }
        $verifyUrl = \URL::temporarySignedRoute($verifyRoute,
        \Illuminate\Support\Carbon::now()->addMinutes(\Illuminate\Support\Facades 
        \Config::get('auth.verification.expire', 60)),['id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),]
        );
        $VerifyEmailAddressLink = "<a href=".$verifyUrl." >Verify Email Address</a>";
        $VerifyEmailLink = "<a href=".$verifyUrl." >".$verifyUrl."</a>";
        $emailtemplate = EmailTemplate::where('slug', 'verify-email-address')->first();
        $search_fields = ['[username]','[VerifyEmailLink]','[VerifyEmailAddressLink]',];
        $replace_data = [$user->name, $VerifyEmailLink,$VerifyEmailAddressLink];
        $body = str_replace($search_fields, $replace_data, $emailtemplate->body);
        $jobData = [
            'content' => $body,
            'to' => $user->email,
            'subject' => $emailtemplate->subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    public function sendContactUsEmail($contact,$contactEmail)
    {
        $emailtemplate = EmailTemplate::where('slug', 'contact-us-email')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $search_fields = ['[username]','[name]','[email]','[phone]','[message]'];
        $replace_data = ['Admin',$contact->first_name,$contact->email,$contact->phone,$contact->message];
        $body = str_replace($search_fields, $replace_data, $body);
        $jobData = [
            'content' => $body,
            'to' => $contactEmail,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    public function sendNotificationAgentsForPointEarned($wallet){
        $notificationData = [
            'user_id' => $wallet->user_id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' => '',
                    'message' => 'Congratulations! You have earned '.$wallet->amount.' points from Booking:- '.$wallet->booking_code
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);

    }

    public function sendCreditRedeemStatusEmail($redeem)
    {
        $emailtemplate = EmailTemplate::where('slug', 'credit-redeem-email')->first();
        $subject = $emailtemplate->subject;
        $body    = $emailtemplate->body;
        $message = 'Your Credit Redeem Request  '.ucfirst($redeem->status).' of amount '.numberformatWithCurrency($redeem->amount) ;
        if($redeem->status=='rejected'){
            $message = 'Your Credit Redeem Request  '.ucfirst($redeem->status).' of amount '.numberformatWithCurrency($redeem->amount).', Your Requested amount reverse into your wallet Amount';
        }
        
        $data ='<table style="border-collapse:collapse;line-height:18px;" width="100%" cellspacing="0" cellpadding="10" border="1"><tbody><tr><td colspan="2" style="background:#002554;font-size:16px;border:1px solid #002554;color:#fff;font-family:Arial, sans-serif;" width="100%">'.trans('wallet::menu.sidebar.credit_redeem_info').' </td>
            </tr><tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">'.trans('wallet::menu.sidebar.form.amount').'</span><br><span><b style="color:#002554;">'.numberformatWithCurrency($redeem->amount,2).'</b></span> </td>
            <td style="color:#002554;font-family:Arial, sans-serif;" width="50%"><span style="font-size:12px;color:#777777;">'.trans('wallet::menu.sidebar.form.status').':</span><br><span><b>'.trans($redeem->status).'</b></span> </td>
            </tr>
            <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">'.trans('wallet::menu.sidebar.form.request_date').'</span><br><span><b style="color:#002554;">'.$redeem->created_at->format(\Config::get('custom.default_date_formate')).'</b></span> </td>';
          if($redeem->status=='completed'){
            $data .= '<td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">'.trans('wallet::menu.sidebar.form.redeem_date').'</span><br><span><b style="color:#002554;">'.$redeem->completed_date->format(\Config::get('custom.default_date_formate')).'</b></span> </td></tr>
             <tr><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">'.trans('wallet::menu.sidebar.form.transection-id').'</span><br><span><b style="color:#002554;">'.$redeem->transactionid.'</b></span> </td><td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">'.trans('wallet::menu.sidebar.form.comments').'</span><br><span><b style="color:#002554;">'.$redeem->comments.'</b></span> </td></tr>';
          }
          if($redeem->status=='rejected'){
            $data .= '<td style="color:#002554;font-family:Arial, sans-serif;"><span style="font-size:12px;color:#777777;">'.trans('wallet::menu.sidebar.form.rejected_date').'</span><br><span><b style="color:#002554;">'.$redeem->rejected_date->format(\Config::get('custom.default_date_formate')).'</b></span> </td></tr><tr><td colspan="2" style="color:#002554;font-family:Arial, sans-serif;text-align:center;"><span style="font-size:12px;color:#777777;">'.trans('wallet::menu.sidebar.form.comments').'</span><br><span><b style="color:#002554;">'.$redeem->comments.'</b></span> </td></tr>';
          }
          $data .= '</tbody></table><br><br><br>';
        $body = str_replace('[USERNAME]', $redeem->user->FullName, $body);
        $body = str_replace('[MESSAGE]', $message, $body);
        $body = str_replace('[DATA]', $data, $body);
        $jobData = [
            'content' => $body,
            'to' => $redeem->user->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));


        $notificationData = [
            'user_id' => $redeem->user_id,
            'create_user' => auth()->user()->id,
            'data' => json_encode([
                'notification' => [
                    'image' => auth()->user()->PicturePath,
                    'link' => '',
                    'message' =>  $message
                ]
            ])
        ];
        $this->NotificationsRepository->addRealTimeNotifications($notificationData);
    }
}
