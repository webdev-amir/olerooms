<?php

namespace App\Repositories\Api;

use App\Models\User;
use DB, Mail;
use config, File;
use Validator;
use Carbon\Carbon;
use Newsletter;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\StaticPages\Entities\StaticPages;
use Modules\Configuration\Entities\Configuration;
use Modules\Notifications\Entities\Notifications;
use Modules\City\Entities\City;
use Modules\City\Entities\Area;

class ApiRepository implements ApiRepositoryInterface
{

    function __construct(StaticPages $Pages, Configuration $Configuration)
    {
        $this->Pages = $Pages;
        $this->Configuration = $Configuration;
    }

    public function subscrivedMailchimp($request)
    {
        if (!Newsletter::isSubscribed($request->input('email'))) {
            $result = Newsletter::subscribe($request->input('email'));
            if ($result) {
                $status['status'] = 'success';
                $status['message'] = trans('flash.success.newsletter_subscribe_successfully');
            } else {
                $status['status'] = 'error';
                $status['message'] = trans('flash.success.newsletter_subscribe_failed');
            }
        } else {
            $status['status'] = 'error';
            $status['message'] = trans('flash.success.newsletter_already_subscribe');
        }
        return $status;
    }

    public function getSocialLinksData()
    {
        $records = Configuration::whereIn('slug', ['facebook', 'instagram', 'twitter'])->get();
        $response['status_code'] = 200;
        $response['message'] = 'Social Link Data';
        if (!empty($records)) {
            foreach ($records as $item) {
                $response['data'][$item['slug']] = array('title' => $item['config_title'], 'value' => $item['config_value']);
            }
        }
        return response()->json($response, $response['status_code'])->setStatusCode($response['status_code']);
    }

    public function getMapContactDetails()
    {
        $records = Configuration::whereIn('slug', ['admincontact', 'adminemail', 'mapaddress'])->get();
        $response['status_code'] = 200;
        $response['message'] = 'Configuration Data';
        if (!empty($records)) {
            foreach ($records as $item) {
                $response['data'][$item['slug']] = array('title' => $item['config_title'], 'value' => $item['config_value']);
            }
        }
        return response()->json($response, $response['status_code'])->setStatusCode($response['status_code']);
    }

    public function getCmsPagesData($slug, $request)
    {
        $record = $this->Pages->where('slug', $slug)->select('slug', 'static_pages.name_en', 'static_pages.description_en', 'updated_at')->first();
        $response['status_code'] = 200;
        $response['message'] = 'Cms Page Data';
        if ($record) {
            $response['data']['slug'] = $record->slug;
            $response['data']['title'] = $record->name_en;
            $response['data']['description'] = $record->description_en;
            $response['data']['last_update'] = $record->updated_at->format(config::get('custom.backed_campaign_formate'));
        } else {
            $response['status_code'] = 404;
            $response['message'] = 'There is no record found.';
            $response['data'] = array();
        }

        return response()->json($response, $response['status_code'])->setStatusCode($response['status_code']);
    }

    public function getUserNotifications()
    {
        $response['data'] = Notifications::where([['user_id', auth()->id()]])->orderBy('id', 'desc')->take(10)->get();
        $response['status_code'] = 200;
        $response['message'] = 'User Notifications';
        return response()->json($response, $response['status_code'])->setStatusCode($response['status_code']);
    }


    public function getStateCities($request)
    {
        $data['cities'] = City::where("state_id",$request->state_id)->where('status',1)
                ->whereHas('areas', function (Builder $q) { })->get(["name","id"]);
        return response()->json($data);
    }

    public function getCitiesArea($request)
    {
        $data['areas'] = Area::where("city_id",$request->city_id)->where('status',1)
                    ->get(["name","id"]);
        return response()->json($data);
    }
}
