<?php

namespace App\Repositories\Frontend;

use DB, Mail, Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Modules\StaticPages\Entities\StaticPages;
use Modules\PropertyType\Entities\PropertyType;
use Modules\Amenities\Entities\Amenities;
use Modules\City\Entities\City;
use Modules\City\Entities\State;
use App\Models\Country;
use Modules\Coupon\Entities\Coupon;
use Modules\Property\Entities\Property;

class FrontendRepository implements FrontendRepositoryInterface
{

    public function getStaticPageBySlug($slug)
    {
        return StaticPages::where('slug', $slug)->first();
    }

    public function getPropertyTypesForOptions()
    {
        return PropertyType::where('status', 1)->orderBy('sortby', 'asc')->get();
    }

    public function getamenitiesData()
    {
        return Amenities::where('status', 1)->orderBy('name', 'asc')->get();
    }

    public function getStateData()
    {
        return State::where('status', 1)->orderBy('name', 'asc')->get();
    }

    public function getCityData()
    {

        return City::where('status', 1)->orderBy('name', 'asc')->get();
    }

    public function getDealOfTheDayForSlider()
    {
        $dealoftheday = Coupon::where('status', '!=', 0)->orderBy('title', 'asc')->take(8)->get();
        return $dealoftheday;
    }

    public function getFeaturedPropertyForSlider()
    {
        return Property::with(['propertyType','propertyTotalRoomImages','city'])->where('status', 'publish')->where('is_publish', 1)->whereHas('author', function ($query) {
            $query->where('users.status', 1);
        })->whereHas('author.userCompleteProfileVerifiredIfApproved')->where('featured_property', '!=', 0)->orderBy('created_at', 'desc')->take(10)->get();
    }

    public function getstateListsForOptions()
    {
        $countryId = Country::where('shortname', 'IN')->first()->id;
        if ($countryId) {
            return State::where('country_id', $countryId)->orderBy('name', 'asc')->whereHas('city', function (Builder $q) {
            })->whereHas('city.areas', function (Builder $q) {
            })->pluck('name', 'id')->toArray();
        }
        return [];
    }

    public function getAutocompleteLocationsLists($request)
    {
        /*$locations = Property::with(['author'])->where('status', 'publish')->where('is_publish', 1)->whereHas('author', function ($query) {
            $query->where('users.status', 1);
        });
        if ($request->get('property_type_id')) {
            $locations->where('property_type_id',$request->get('property_type_id'));
        }
        */
        $locations = new Property;
        $searchTerm = $request->search;
        $req3Letters = substr($request->get('search'), 0, 3);
        $filterbyName = 'map_location';
        if (strtoupper($req3Letters) == 'OLE') {
            $filterbyName = 'property_code';
        } else {
            $filterbyName = 'map_location';
        }
        $cloneLocation = $locations;
        if($filterbyName != 'property_code'){
                $locations = $locations->where(function ($query) use ($searchTerm, $filterbyName) {
                    $query->where('search_address', 'LIKE', '%' .  $searchTerm  . '%');
                })->select('id','search_address as final_name')->groupBy('final_name')->take(8)->get();
                //->whereHas('author.userCompleteProfileVerifiredIfApproved')
            if(count($locations)==0){
                $locations = $cloneLocation->where(function ($query) use ($searchTerm, $filterbyName) {
                    $query->where('map_location', 'LIKE', '%' .  $searchTerm  . '%');
                })->select('id','map_location as final_name')->groupBy('final_name')->take(8)->get();
                //->whereHas('author.userCompleteProfileVerifiredIfApproved')
            }
        }else{
            $locations = $locations->where(function ($query) use ($searchTerm, $filterbyName) {
                $query->where($filterbyName, 'LIKE', '%' .  $searchTerm  . '%');
            })->select('id','property_code as final_name')->groupBy('final_name')->take(8)->get(); 
            //->whereHas('author.userCompleteProfileVerifiredIfApproved')  
        }
        $list_json = [];
        if (!empty($locations) and count($locations)) {
            foreach ($locations as $location) {
                $list_json[] = [
                    'id' => $location->id,
                    'title' => $location->final_name,
                ];
            }
            return $this->sendSuccess(['data' => $list_json]);
        }
        return $this->sendSuccess(['data' => $list_json], 'No Record Found');
    }

    public function sendSuccess($data = [], $message = '')
    {
        if (is_string($data)) {
            return response()->json([
                'message' => $data,
                'status' => true
            ]);
        }
        if (!isset($data['status'])) $data['status'] = 1;

        if ($message)
            $data['message'] = $message;

        return response()->json($data);
    }
}
