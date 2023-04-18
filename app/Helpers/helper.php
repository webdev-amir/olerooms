<?php

use App\Models\User;
use Modules\Configuration\Entities\Configuration;
use Modules\Notifications\Entities\Notifications;
use Modules\PropertyType\Entities\PropertyType;
use Modules\Settings\Entities\Settings;
use Illuminate\Support\Facades\Storage;
use Edujugon\PushNotification\PushNotification;

if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('pr')) {
    /**
     * Access the print_r helper
     */
    function pr($data)
    {
        echo "<pre>";
        print_r($data);
        die;
    }
}

if (!function_exists('onerrorProImage')) {
    function onerrorProImage()
    {
        return \URL::to('img/avtar.png');
    }
}

if (!function_exists('onerrorReturnImage')) {
    function onerrorReturnImage()
    {
        return \URL::to('images/noimage.png');
    }
}

function get_guard()
{
    if (Auth::guard('admin')->check()) {
        return "admin";
    } elseif (Auth::guard('web')->check()) {
        return "web";
    } else {
        return '';
    }
}
function sendPushNotificationForBooking($filleable, $tokenss)
{
    $push = new PushNotification('fcm');
    $push->setMessage([
        'notification' => [
            'title' => $filleable['title'],
            'body'  =>  $filleable['body'],
            'sound' => 'default',
            'click_action' => 'FCM_PLUGIN_ACTIVITY'
        ],

        'data' => [
            'booking_slug' => $filleable['slug'],
            'type' => $filleable['type']
        ]
    ])
        ->setApiKey(env('FCM_SERVER_KEY'))
        ->setDevicesToken($tokenss->device_token)
        ->send()
        ->getFeedback();
}

function sendPushNotificationForScheduling($filleable, $tokenss)
{
    $push = new PushNotification('fcm');
    $push->setMessage([
        'notification' => [
            'title' => $filleable['title'],
            'body'  =>  $filleable['body'],
            'sound' => 'default',
            'click_action' => 'FCM_PLUGIN_ACTIVITY'
        ],

        'data' => [
            'schedule_id' => $filleable['id'],
            'type' => $filleable['type']
        ]
    ])
        ->setApiKey(env('FCM_SERVER_KEY'))
        ->setDevicesToken($tokenss->device_token)
        ->send()
        ->getFeedback();
}

/*
** Display Button With Role
*/
if (!function_exists('displayButton')) {
    function displayButton($buttonName = array())
    {
        $return = [];
        if (is_array($buttonName) &&  count($buttonName) > 0) {
            foreach ($buttonName as $key => $value) {
                $route = $value[0]; // modelName.function 
                $routeKey = isset($value[1]) ? $value[1] : [];
                $class = $routeKey[0];
                $return[$key] = buttonHtml($key, route($route, $routeKey), $class);
            }
        }
        return $return;
    }
}

/*
** Button With Html
*/
if (!function_exists('changeErrorForAppResponse')) {
    /**
     * return change Error For App Response.
     *
     * @return array
     */
    function changeErrorForAppResponse($errors)
    {
        return $errors->first(); //for first && single error
        //return implode('', $errors); //for all && multiple error in single message
    }
}

if (!function_exists('buttonHtml')) {
    function buttonHtml($key, $link, $class)
    {
        $array = [
            "area" => "&nbsp;&nbsp;<a href='" . $link . "' title='Area' class='tooltips' data-toggle='tooltip' data-placement='top'><i class='fa fa-flag'></i></a>",
            "city" => '&nbsp;&nbsp;<a href="' . $link . '"><img class="sidebar_icons" style="width: 20px;" src="http://192.168.0.57/oleroom_new/img/sidebar/city.png"></a>',
            "edit" => "<a href='" . $link . "' title='Edit' class='tooltips' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>",
            "Active" => '&nbsp;&nbsp;<span class="margin-r-5"> <a id="Inactive_' . $class . '" data-placement="top" data-toggle="tooltip" class="danger tooltips" title="Inactive" rel="Inactive" name="' . $link . '" href="javascript:;" OnChange="return ConfirmDeleteLovi(this.id,this.rel,this.name);" onClick="return AjaxActionTableDrow(this);" data-title="Inactive" data-action="' . $link . '"><i class="fa fa-ban" aria-hidden="true"></i></a></span>',
            "Inactive" => '&nbsp;&nbsp;<span class="margin-r-5"> <a id="Active_' . $class . '" data-toggle="tooltip" class="success tooltips"  title="Active"  rel="Active" name="' . $link . '" href="javascript:;" data-placement="top"  OnChange="return ConfirmDeleteLovi(this.id,this.rel,this.name);" onClick="return AjaxActionTableDrow(this);" data-title="Active" data-action="' . $link . '"><i class="fa fa-check" aria-hidden="true"></i></a></span>',
            "Cancelled" => '&nbsp;&nbsp;<span class="margin-r-5"> <a id="Cancel_' . $class . '" data-toggle="tooltip" class="success tooltips"  title="Cancel"  rel="Cancel" name="' . $link . '" href="javascript:;" data-placement="top"  OnChange="return ConfirmDeleteLovi(this.id,this.rel,this.name);" onClick="return AjaxActionTableDrow(this);" data-title="Cancel" data-action="' . $link . '"><i class="fa fa-times text-danger" aria-hidden="true"></i></a></span>',
            "add" => '&nbsp;&nbsp;<a href="' . $link . '" class="tooltips" data-toggle="tooltip" data-placement="top"><i class="fa fa-eye"></i></a>',
            "delete" => '
                <form method="POST" action="' . $link . '" accept-charset="UTF-8" style="display:inline" class="dele_' . $class . '">
                    <input name="_method" value="DELETE" type="hidden">
                    ' . csrf_field() . '
                        <span>
                             &nbsp;<a href="javascript:;" id="dele_' . $class . '" data-toggle="tooltip" title="Delete" type="button"  data-placement="top" name="Delete" class="delete_action tble_button_st tooltips" Onclick="return ConfirmDeleteLovi(this.id,this.name,this.name);" ><i class="fa fa-trash-o" title="Delete"></i>
                            </a>
                         </span>
                </form>',
            "deleteAjax" => '&nbsp;<a href="javascript:;" id="dele_' . $class . '" data-toggle="tooltip" title="Delete" data-title="Delete" type="button"  data-placement="top" class="delete_ajax tble_button_st tooltips"  data-action="' . $link . '" onClick="return AjaxActionTableDrow(this);"><i class="fa fa-trash-o" title="Delete"></i></a>',
            "view" => '&nbsp;&nbsp;&nbsp;<span class="margin-r-5"><a data-toggle="tooltip"  class="" title="View" href="' . $link . '"><i class="fa fa-eye" aria-hidden="true"></i></a> </span>',
            "images" => '&nbsp;&nbsp;<span class="margin-r-5"><a data-toggle="tooltip" title="View Images" href="' . $link . '"><i class="fa fa-picture-o" aria-hidden="true"></i></a></span>',
            "pages" => '<span class="margin-r-5"><a data-toggle="tooltip"  class="btn btn-info small-btn" title="View book pages" href="' . $link . '"><i class="fa fa-file-text-o" aria-hidden="true"></i></a></span>',
            "permission" => '<span class="f-left margin-r-5"> &nbsp;<a class="tble_button_st tooltips" data-toggle="tooltip" data-placement="top" title="Set Permission" href="' . $link . '"><i class="fa fa-cog" aria-hidden="true"></i></a></span>',
            "restore" => '<span class="margin-r-5"><a id="restore_' . $class . '"  data-toggle="tooltip" data-placement="top" class="warning tooltips" title="Restore" rel="Restore" name="' . $link . '" href="javascript:;" Onclick="return ConfirmDeleteLovi(this.id,this.rel,this.name);"><i class="fa fa-database" aria-hidden="true"></i></a></span>',
            "addon" => " &nbsp; <a href='" . $link . "' title='Addon' class='tooltips' data-toggle='tooltip' data-placement='top'><i class='fa fa-plus'></i> </a>",
            "bookingRejected" => '&nbsp;&nbsp;<span class="margin-r-5"> <a id="Reject_' . $class . '" data-toggle="tooltip" class="success tooltips"  title="Decline" rel="Decline" name="' . $link . '" href="javascript:;" data-placement="top"  OnChange="return ConfirmDeleteLovi(this.id,this.rel,this.name);" onClick="return AjaxActionTableDrow(this);" data-title="Decline" data-action="' . $link . '"><i class="fa fa-times-circle-o text-danger" aria-hidden="true"></i></a></span>',
            "bookingCancelled" => '&nbsp;&nbsp;<span class="margin-r-5"> <a id="Cancel__' . $class . '" data-toggle="tooltip" class="success tooltips" title="Accept" rel="Accept" name="' . $link . '" href="javascript:;" data-placement="top"  OnChange="return ConfirmDeleteLovi(this.id,this.rel,this.name);" onClick="return AjaxActionTableDrow(this);" data-title="Accept" data-action="' . $link . '"><i class="fa fa-check text-success" aria-hidden="true"></i></a></span>',
        ];

        if (isset($array[$key])) {
            return $array[$key];
        }
        return '';
    }
}

/*
** Array In check key exist or not 
*/
if (!function_exists('keyExist')) {
    function keyExist($array = array(), $key)
    {
        if (isset($array[$key])) {
            return $array[$key];
        } else {
            return '';
        }
    }
}

if (!function_exists('getStatusAI')) {
    function getStatusAI($status)
    {
        $getStatusArray = getStatusArray();
        if (isset($getStatusArray[$status])) {
            return $getStatusArray[$status];
        }
        return '';
    }
}

/*
** Get Status Array
*/
if (!function_exists('getStatusArray')) {

    function getStatusArray()
    {
        $return = ['1' => 'Active', '0' => 'Inactive', 'cancelled' => 'Cancelled', 'rejected' => 'Rejected'];
        return $return;
    }
}

if (!function_exists('uploadOnS3Bucket')) {
    /**
     * Access the upload helper
     */
    function uploadOnS3Bucket($fileName, $path)
    {
        $file = $fileName;
        $destinationPath = $path;
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        //Origional Image upload on s3 Bucket
        if ($extension == 'svg') {
            Storage::disk('s3')->put($destinationPath . $fileName, file_get_contents($file), ['mimetype' => 'image/svg+xml']);
        } else {
            Storage::disk('s3')->put($destinationPath . $fileName, file_get_contents($file));
        }
        return $fileName;
    }
}

function uploadWithResize($fileName, $path, $height = 271, $width = 287)
{
    $image = $fileName;
    $ext = $image->getClientOriginalExtension();
    $imageName = time() . '.' . $image->getClientOriginalExtension();
    $destinationPath = $path;
    $destinationThumbPath = $path . 'thumbnail/';
    if ($ext != 'svg') {
        //Image resize then upload
        $resizeimage = Image::make($image)->fit($width, $height)->stream();
        Storage::disk('s3')->put($destinationThumbPath . $imageName, $resizeimage->__toString(), 'public');
    }
    //Origional Image upload on s3 Bucket
    if ($ext == 'svg') {
        Storage::disk('s3')->put($destinationPath . $imageName, file_get_contents($image), ['mimetype' => 'image/svg+xml']);
    } else {
        Storage::disk('s3')->put($destinationPath . $imageName, file_get_contents($image));
    }
    return $imageName;
}

if (!function_exists('upload')) {
    /**
     * Access the upload helper
     */
    function upload($fileName, $path)
    {
        $file = $fileName;
        $destinationPath = $path;
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $file->move($destinationPath, $fileName);
        return $fileName;
    }
}

if (!function_exists('uploadWithResizeOld')) {
    /**
     * Access the uploadWithResizeOld helper
     */
    function uploadWithResizeOld($fileName, $path, $height = 271, $width = 287)
    {
        $image = $fileName;
        $ext = $image->getClientOriginalExtension();
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = $path;
        $destinationThumbPath = $path . 'thumbnail';
        if (!File::isDirectory($destinationThumbPath)) {
            File::makeDirectory($destinationThumbPath, 0777, true, true);
        }
        if ($ext != 'svg') {
            $img = \Image::make($image->getRealPath());
            $img->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            })->save($destinationThumbPath . '/' . $imageName);
        }
        $image->move($destinationPath, $imageName);
        return $imageName;
    }
}

if (!function_exists('setActiveMainMenu')) {
    /**
     * currunt menu active
     */
    function setActiveMainMenu($path)
    {
        return Request::is($path) ? 'active' :  '';
    }
}

if (!function_exists('setActive')) {
    /**
     * Access the setActive helper
     */
    function setActive($path)
    {
        return Request::is($path . '*') ? 'active' :  '';
    }
}

if (!function_exists('numberformatWithCurrency')) {
    function numberformatWithCurrency($amount, $point = 0)
    {
        if (empty($amount)) $amount = 0;
        return config('custom.currency-sign') . number_format($amount, $point);
    }
}

/*
** Array In check key exist or not 
*/
if (!function_exists('notifiCount')) {
    function notifiCount()
    {
        $count = Notifications::where([['user_id', auth()->id()], ['read_at', null]])->count();
        return $count ? $count : 0;
    }
}

function mapScripts()
{
    $html = '';
    switch (setting_item('map_provider')) {
        case "gmap":
            $html .= sprintf("<script src='https://maps.googleapis.com/maps/api/js?key=%s&libraries=places'></script>", setting_item('map_gmap_key'));
            $html .= sprintf("<script src='%s'></script>", url('theme/libs/infobox.js'));
            break;
        case "osm":
            $html .= sprintf("<script src='%s'></script>", url('libs/leaflet1.4.0/leaflet.js'));
            $html .= sprintf("<link rel='stylesheet' href='%s'>", url('libs/leaflet1.4.0/leaflet.css'));
            break;
    }
    $html .= sprintf("<script src='%s'></script>", url('module/core/js/map-engine.js?_ver=' . config('app.version')));
    return $html;
}

function setting_item($item, $default = '', $isArray = false)
{
    $res = Settings::item($item, $default);

    if ($isArray and !is_array($res)) {
        $res = (array) json_decode($res, true);
    }

    return $res;
}

function getPropertyTypeBySlug($slug = '')
{
    $res = PropertyType::where('slug', $slug)->first();
    return $res;
}

function addDarkClass()
{
    $routeName = \Route::currentRouteName();
    if ($routeName != 'home' && $routeName != 'propertyowner.home' && $routeName != 'agent.home' && $routeName != 'agent.login' && $routeName != 'company.home' && $routeName != 'company.login' && $routeName != 'login' && $routeName != 'customer.login' && $routeName != 'register' && $routeName != 'vendor.login' && $routeName != 'customer.password.request' && $routeName != 'owner.password.request' && $routeName != 'company.password.request' && $routeName != 'agent.password.request' && $routeName != 'owner.password.reset' && $routeName != 'company.password.reset' && $routeName != 'agent.password.reset' && $routeName != 'vendor.completeProfileVerification' && $routeName != 'customer.MobileLoginOTPScreen' && $routeName != 'customer.MobileVerify' && $routeName != 'customer.password.reset' && $routeName != 'pages.show' && $routeName != 'frontend.faq') {
        return 'bgDark';
    }
}

function availableSearchFilter($propertyType)
{
    $filter = [];
    switch ($propertyType) {
        case "1": // Hostel/PG
            $filter = [
                'state_filter',
                'city_filter',
                'location_filter',
                'property_type_filter',
                'occupancy_filter',
                'room_ac_type_filter',
                'available_for_filter',
                'price_filter',
                'rating_filter'
            ];
            break;
        case "2": //Flat
            $filter = [
                'state_filter',
                'city_filter',
                'location_filter',
                'property_type_filter',
                'room_type_filter',
                'available_for_filter',
                'bhk_type_filter',
                'price_filter',
                'rating_filter'
            ];
            break;
        case "3": //Guest/Hotel
            $filter = [
                'state_filter',
                'city_filter',
                'location_filter',
                'property_type_filter',
                'room_standard_filter',
                'room_ac_type_filter',
                'price_filter',
                'rating_filter'
            ];
            break;
        case "4": //Hostel/PG(One Day)
            $filter = [
                'state_filter',
                'city_filter',
                'location_filter',
                'property_type_filter',
                'occupancy_filter',
                'room_ac_type_filter',
                'available_for_filter',
                'price_filter',
                'rating_filter'
            ];
            break;
        case "5": //Home Stay  
            $filter = [
                'state_filter',
                'city_filter',
                'location_filter',
                'property_type_filter',
                'room_ac_type_filter',
                'room_type_filter',
                'available_for_filter',
                'price_filter',
                'rating_filter',
                'no_of_room_filter',
                'guests_capacity'
            ];
            break;
        default:
            $filter = [];
    }

    return $filter;
}

function noLogoPicturePath()
{
    return  \URL::to('img/nologo.png');
}
function nonewsPicturePath()
{
    return  \URL::to('images/noimage.png');
}

function display_dob_date($time)
{

    if ($time) {
        if (is_string($time)) {
            $time = strtotime($time);
        }

        if (is_object($time)) {
            return $time->format(get_date_format_dob());
        }
    } else {
        $time = strtotime(today());
    }

    return date(get_date_format_dob(), $time);
}

function get_date_format_dob()
{
    return 'm/d/Y';
}

function display_date($time)
{
    if ($time) {
        if (is_string($time)) {
            $time = strtotime($time);
        }

        if (is_object($time)) {
            return $time->format(get_date_format());
        }
        return date(get_date_format(), $time);
    } else {
        $time = 'N/A';
    }
    return $time;
}

function get_date_format()
{
    return 'd/m/Y';
}

function display_time($time)
{
    if ($time) {
        if (is_string($time)) {
            $time = strtotime($time);
        }

        if (is_object($time)) {
            return $time->format(get_time_format());
        }
    } else {
        $time = strtotime(today());
    }

    return date(get_time_format(), $time);
}

function get_time_format()
{
    return 'h:i A';
}

function get_date_week_month_name($date)
{
    if ($date) {
        return \Carbon\Carbon::parse($date)->format('l, jS F, Y');
    }
    return '';
}


function get_date_month_name($date)
{
    if ($date) {
        return \Carbon\Carbon::parse($date)->format('j F, Y');
    }
    return '';
}

function booking_status_to_text($status)
{
    switch ($status) {
        case "request":
            return __('Reserve Request');
            break;
        case "in-progress":
            return __('In-Progress');
            break;
        case "unpaid":
            return __('Unpaid');
            break;
        case "paid":
            return __('Paid');
            break;
        case "processing":
            return __('Processing');
            break;
        case "completed":
            return __('Completed');
            break;
        case "confirmed":
            return __('Confirmed');
            break;
        case "cancelled":
            return __('Cancelled');
            break;
        case "cancel":
            return __('Cancel');
            break;
        case "pending":
            return __('Pending');
            break;
        case "succeeded":
            return __('Completed');
            break;
        case "fail":
            return __('Failed');
            break;
        default:
            return ucfirst($status ?? '');
            break;
    }
}

function defaultPaymentGateway()
{
    return config('booking.default_payment_gateway');
}

function defaultCurrency()
{
    return config('booking.default_payment_currency');
}

//Get Configuration value by key
if (!function_exists('getConfig')) {
    function getConfig($slug)
    {
        $config = Configuration::where('slug', '=', $slug)->first();
        if (!empty($config)) {
            return $config->config_value;
        } else {
            return '';
        }
    }
}

function isMobilOrDesktop()
{
    $agent = preg_match(
        "/(android|avantgo|blackberry|bolt|boost|cricket|docomo
        |fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
        $_SERVER["HTTP_USER_AGENT"]
    );
    if ($agent) {
        return 'mobile';
    } else {
        return 'desktop';
    }
}

function isCityLoopTrueFalse()
{
    if (isMobilOrDesktop() == 'mobile') {
        return 'true';
    }
    return 'true';
}

function requestErrorApiResponse($request)
{
    $erro['status_code'] = 422;
    foreach ($request as $error) {
        $erro['message'] = $error[0];
    }
    return $erro;
}

//check Version Status
if (!function_exists('checkVersionStatus')) {
    function checkVersionStatus($device_type, $reqVersion)
    {
        $result = [];
        if ($device_type && $reqVersion) {
            $deviceVersion = getConfig(strtolower($device_type) . '-version');
            $force_update = getConfig(strtolower($device_type) . '-force-update');
            $result['forceUpdate'] = (int) $force_update;
            $result['updateAvailable'] = 0;
            if ($deviceVersion > $reqVersion) {
                $result['updateAvailable'] = 1;
            }
            return $result;
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Platform and version are required.', []);
        }
    }
}

if (!function_exists('paginationFormat')) {
    /**
     * return data according paginate.
     *
     * @return array
     */
    function paginationFormat($request)
    {
        $res['lastPage'] = $request->lastPage();
        $res['total'] = $request->total();
        $res['nextPageUrl'] = ($request->nextPageUrl()) ? $request->nextPageUrl() : "";
        $res['prevPageUrl'] = ($request->previousPageUrl()) ? $request->previousPageUrl() : "";
        $res['currentPage'] = $request->currentPage();
        return $res;
    }
}
if (!function_exists('getSliderCountForTrust')) {
    /**
     * return data according getSliderCountForTrust.
     *
     * @return array
     */
    function getSliderCountForTrust()
    {
        if (isMobilOrDesktop() == 'mobile') {
            return 1;
        }
        return 3;
    }
}


if (!function_exists('get_mime_type')) {

    function get_mime_type($filename)
    {
        $idx = explode('.', $filename);
        $count_explode = count($idx);
        $idx = strtolower($idx[$count_explode - 1]);

        $mimet = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',


            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        if (isset($mimet[$idx])) {
            return $mimet[$idx];
        } else {
            return 'application/octet-stream';
        }
    }
}

if (!function_exists('checkRoleUsingSegment')) {
    function checkRoleUsingSegment()
    {
        $rolename = 'customer';
        if (request()->segment(1) == 'customer') {
            $rolename = 'customer';
        } else if (request()->segment(1) == 'agent') {
            $rolename = 'agent';
        } else if (request()->segment(1) == 'company') {
            $rolename = 'company';
        } else if (request()->segment(1) == 'owner') {
            $rolename = 'vendor';
        } else if (request()->segment(1) == 'admin') {
            $rolename = 'admin';
        }
        return $rolename;
    }
}

if (!function_exists('calculateAgentCorporateRewardPoints')) {
    function calculateAgentCorporateRewardPoints($totalAmount = 0, $codeType)
    {
        if (!($codeType)) {
            return  0;
        }
        if ($codeType == 'agent') {
            $pointPrct = setting_item('agent-points-percetage');
            $rewards = $totalAmount * ($pointPrct / 100);
        } else {
            $pointPrct = setting_item('company-points-percetage');
            $rewards = $totalAmount * ($pointPrct / 100);
        }
        return round($rewards);
    }
}

if (!function_exists('getAgentIdByAgentCode')) {
    function getAgentIdByAgentCode($agentCode)
    {
        if (!($agentCode)) {
            return  false;
        }
        $agentData = User::where('agent_code', $agentCode)->orWhere('company_code', $agentCode)->withTrashed()->first();
        return $agentData->id;
    }
}


if (!function_exists('createAgentCorpCode')) {
    function createAgentCorpCode($name, $id)
    {
        $DataCode = strtoupper(substr($name, 0, 3)) . str_pad($id, 4, '0', STR_PAD_LEFT);
        return 'OLE' . $DataCode;
    }
}
