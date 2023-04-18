<?php

namespace Modules\Api\Repositories\Customer\Home;

use DB;
use Validator;
use App\Models\User;
use Modules\Property\Entities\Property;
use Modules\PropertyType\Entities\PropertyType;
use Modules\City\Entities\City;
use Modules\Slider\Entities\Slider;
use Modules\Coupon\Entities\Coupon;
use Modules\City\Entities\State;
use Modules\City\Entities\Area;
use App\Repositories\Frontend\FrontendRepositoryInterface as FrontendRepository;

class HomeRepository implements HomeRepositoryInterface
{

  function __construct(User $User, Property $Property, PropertyType $PropertyType, City $City, Slider $Slider, Coupon $Coupon, Area $Area, FrontendRepository $FrontendRepository)
  {
    $this->User = $User;
    $this->Property = $Property;
    $this->PropertyType = $PropertyType;
    $this->City = $City;
    $this->Area = $Area;
    $this->Slider = $Slider;
    $this->Coupon = $Coupon;
    $this->FrontendRepository = $FrontendRepository;
    $this->propertyClass = Property::class;
  }

  public function searchProperty($request)
  {
    try {
      $default_property_type = config('custom.default_propery_type_search');
      $search_property_type = $request->get('property_type') ?? $default_property_type;
      $response['status_code'] = 200;
      $response['message'] = 'property search result';
      $default_property_type = config('custom.default_propery_type_search');
      $search_property_type = $request->get('property_type') ?? $default_property_type;
      $filters = availableSearchFilter($search_property_type);
      $properties = call_user_func([$this->propertyClass, 'search'], $request);
      if (!$request->get('map_value') || $request->get('map_value') != 'show_map') {
        $response = paginationFormat($properties);
      }
      $response['status_code'] = 200;
      $response['message'] = 'Property data listing.';

      if (count($properties) > 0) {
        foreach ($properties as $key => $list) {
          $response['data'][$key]['id'] = $list->id;
          $response['data'][$key]['slug'] = $list->slug;
          $response['data'][$key]['property_type_id'] = $list->property_type_id;
          $response['data'][$key]['property_cover_image'] = $list->CoverImgThunbnail;
          $response['data'][$key]['property_code'] = ucfirst($list->property_code);
          $response['data'][$key]['city'] = $list->city->name;
          $response['data'][$key]['property_type_name'] = $list->propertyType->name;
          $response['data'][$key]['rating'] = $list->RatingAverage;
          $response['data'][$key]['isFavorite'] = $list->IsFavProduct;
          $response['data'][$key]['price'] = numberformatWithCurrency($list->PropertStartingAmount);
          $response['data'][$key]['lat'] = $list->DummyLat;
          $response['data'][$key]['long'] = $list->DummyLong;
        }
      } else {
        $response['status_code'] = 200;
        $response['message'] = 'There is no record found.';
        $response['data'] = array();
      }
    } catch (\Exception $th) {
      $response['status_code'] = 402;
      $response['message'] = 'Something went wrong';
    }
    return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
  }

  public function searchPropertyFilter($request)
  {
    try {
      $default_property_type = config('custom.default_propery_type_search');
      $search_property_type = $request->get('property_type') ?? $default_property_type;
      // $filters = availableSearchFilter($search_property_type);
      // $stateLists = $this->FrontendRepository->getstateListsForOptions();
      // $cityList = $this->FrontendRepository->getCityData();
      $propertyTypes = $this->FrontendRepository->getPropertyTypesForOptions();
      foreach ($propertyTypes as $key => $propertyType) {
        $response['propertyTypeFilter'][$key]['id'] = $propertyType->id;
        $response['propertyTypeFilter'][$key]['slug'] = $propertyType->slug;
        $response['propertyTypeFilter'][$key]['name'] = $propertyType->name;
        $response['propertyTypeFilter'][$key]['image'] = $propertyType->image;
      }

      $priceFilters = config('custom.price_filter');
      $i = 0;
      foreach ($priceFilters as $key => $priceFilter) {
        $response['pricefilter'][$i]['name'] = $key;
        $response['pricefilter'][$i]['value'] = $priceFilter;
        $i++;
      }

      $ratingFilters = config('custom.rating_filter');
      $i = 0;
      foreach ($ratingFilters as $key => $ratingFilter) {
        $response['ratingfilter'][$i]['name'] = $key;
        $response['ratingfilter'][$i]['value'] = $ratingFilter;
        $i++;
      }

      $furnitureFilters = config('custom.furniture_filter');
      $i = 0;
      foreach ($furnitureFilters as $key => $furnitureFilter) {
        $response['furniturefilter'][$i]['name'] = $key;
        $response['furniturefilter'][$i]['value'] = $furnitureFilter;
        $i++;
      }

      $availableForFilters = config('custom.property_available_for');
      $i = 0;
      foreach ($availableForFilters as $key => $availableForFilter) {
        $response['availableforfilter'][$i]['name'] = $key;
        $response['availableforfilter'][$i]['value'] = $availableForFilter;
        $i++;
      }
      if (in_array($search_property_type, [1,  4])) {
        $occupancyFilters = config('custom.occupancy_filter');
        $i = 0;
        foreach ($occupancyFilters as $key => $occupancyFilter) {
          $response['occupancyfilter'][$i]['name'] = $key;
          $response['occupancyfilter'][$i]['value'] = $occupancyFilter;
          $i++;
        }
      }

      if ($search_property_type == 2) {
        $bhkTypeFilters = config('custom.bhk_type');
        $i = 0;
        foreach ($bhkTypeFilters as $key => $bhkTypeFilter) {
          $response['bhktypefilter'][$i]['name'] = $key;
          $response['bhktypefilter'][$i]['value'] = $bhkTypeFilter;
          $i++;
        }
      }

      if (in_array($search_property_type, [1, 3, 4, 5])) {
        $roomTypeFilters = config('custom.room_ac_type_filter');
        $i = 0;
        foreach ($roomTypeFilters as $key => $roomTypeFilter) {
          $response['roomtypefilters'][$i]['name'] = $key;
          $response['roomtypefilters'][$i]['value'] = $roomTypeFilter;
          $i++;
        }
      }

      if ($search_property_type == 3) {
        $roomStandardFilters = config('custom.room_standard_filter');
        $i = 0;
        foreach ($roomStandardFilters as $key => $roomStandardFilter) {
          $response['roomstandardfilter'][$i]['name'] = $key;
          $response['roomstandardfilter'][$i]['value'] = $roomStandardFilter;
          $i++;
        }
      }




      $sortBy = config('custom.search_sort_by');
      $i = 0;
      foreach ($sortBy as $key => $sortBy1) {
        $response['sort_by'][$i]['name'] = $sortBy1;
        $response['sort_by'][$i]['value'] = $key;
        $i++;
      }

      $response['status_code'] = 200;
      $response['message'] = 'Property search filter result';
    } catch (\Exception $th) {
      $response['status_code'] = 402;
      $response['message'] = 'Something went wrong';
    }
    return response()->json($response, $response['status_code'])
      ->withHeaders(checkVersionStatus($request->headers
        ->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
  }

  public function getHomepageData($request)
  {
    try {
      if ($request->lat && $request->long) {
        $featureProperties = $this->Property::select(
          "properties.*",
          DB::raw("6371 * acos(cos(radians(" . $request->lat . ")) 
                        * cos(radians(properties.lat)) 
                        * cos(radians(properties.long) - radians(" . $request->long . ")) 
                        + sin(radians(" . $request->lat . ")) 
                        * sin(radians(properties.lat))) AS distance")
        )
          ->with('propertyType', 'city')
          ->where('status', 'publish')
          ->where('is_publish', true)
          ->where('featured_property', '!=', 0)
          ->orderBy('status', 'asc')
          ->take(2)
          ->get();
      } else {
        $featureProperties = $this->Property::with('propertyType', 'city')->where('status', 'publish')->where('is_publish', true)->where('featured_property', '!=', 0)->orderBy('status', 'asc')->take(10)->get();
      }

      $response['status_code'] = 200;
      $response['message'] = 'Home page data';
      $response['data']['featureProperties'] = array();
      $response['data']['cities'] = array();
      $response['data']['property_cartegory'] = array();
      $response['data']['specialDeals'] = array();
      if (count($featureProperties) > 0) {
        foreach ($featureProperties as $key => $list) {
          $response['data']['featureProperties'][$key]['id'] = $list->id;
          $response['data']['featureProperties'][$key]['slug'] = $list->slug;
          $response['data']['featureProperties'][$key]['property_type_id'] = $list->property_type_id;
          $response['data']['featureProperties'][$key]['property_cover_image'] = $list->CoverImgThunbnail;
          $response['data']['featureProperties'][$key]['property_code'] = ucfirst($list->property_code);
          $response['data']['featureProperties'][$key]['city'] = $list->city->name;
          $response['data']['featureProperties'][$key]['property_type_name'] = $list->propertyType->name;
          $response['data']['featureProperties'][$key]['rating'] = $list->RatingAverage;
          $response['data']['featureProperties'][$key]['isFavorite'] = $list->IsFavProduct;
          $response['data']['featureProperties'][$key]['price'] = numberformatWithCurrency($list->PropertStartingAmount);
        }
      }
      $cities =  $this->City->where('status', '!=', 0)->orderBy('name')->get();
      if (count($cities) > 0) {
        foreach ($cities as $key => $list) {
          $response['data']['cities'][$key]['id'] = $list->id;
          $response['data']['cities'][$key]['name'] = $list->name;
          $response['data']['cities'][$key]['image_path'] = $list->ThumbPicturePath;
          $response['data']['cities'][$key]['status'] = $list->status;
        }
      }
      $property_cartegory = $this->PropertyType->where('status', 1)->orderBy('sortby', 'asc')->limit(6)->get();
      if (count($property_cartegory) > 0) {
        foreach ($property_cartegory as $key => $list) {
          $response['data']['property_cartegory'][$key]['id'] = $list->id;
          $response['data']['property_cartegory'][$key]['slug'] = $list->slug;
          $response['data']['property_cartegory'][$key]['name'] = $list->name;
          $response['data']['property_cartegory'][$key]['image_path'] = $list->PicturePath;
        }
      }
      $banners = $this->Slider->active()->latest()->take(2)->latest()->get();
      if (count($banners) > 0) {
        foreach ($banners as $key => $list) {
          $response['data']['banners'][$key]['url'] = $list->url;
          $response['data']['banners'][$key]['image_path'] = $list->PicturePath;
        }
      }
      $specialDeals = $this->Coupon->where('status', '!=', 0)->orderBy('title', 'asc')->take(8)->get();
      if (count($specialDeals) > 0) {
        foreach ($specialDeals as $key => $list) {
          $response['data']['specialDeals'][$key]['id'] = $list->id;
          $response['data']['specialDeals'][$key]['slug'] = $list->slug;
          $response['data']['specialDeals'][$key]['title'] = $list->title;
          $response['data']['specialDeals'][$key]['coupon_code'] = $list->coupon_code;
          $response['data']['specialDeals'][$key]['propertyType'] = $list->propertyType->name;
          $response['data']['specialDeals'][$key]['description'] = $list->description;
          $response['data']['specialDeals'][$key]['image_path'] = $list->PicturePath;
        }
      }
    } catch (\Exception $th) {
      dd($th);
      $response['status_code'] = 402;
      $response['message'] = 'Something went wrong';
    }
    return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
  }

  public function getState($request)
  {
    try {
      $stateList = State::where('status', 1)->orderBy('name', 'asc')->get();
      $response['status_code'] = 200;
      $response['message'] = 'State list';
      $response['data'] = $stateList;
    } catch (\Exception $th) {
      $response['status_code'] = 402;
      $response['message'] = 'Something went wrong';
    }
    return response()->json($response, $response['status_code'])
      ->withHeaders(checkVersionStatus($request->headers
        ->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
  }

  public function getCity($request)
  {
    $validator = Validator::make($request->all(), [
      'state_id' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
    }
    $cityList = $this->City->where('status', 1)->where('state_id', $request->state_id)
      ->orderBy('name', 'asc')->get();
    if (count($cityList) > 0) {
      $response['status_code'] = 200;
      $response['message'] = 'City list';
      $response['data'] = $cityList;
    } else {
      $response['status_code'] = 200;
      $response['message'] = 'City not found';
      $response['data'] = [];
    }
    return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
  }

  public function getLocation($request)
  {
    $validator = Validator::make($request->all(), [
      'city_id' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
    }
    $locationList = $this->Area->where('status', 1)->where('city_id', $request->city_id)
      ->orderBy('name', 'asc')->get();
    if (count($locationList) > 0) {
      $response['status_code'] = 200;
      $response['message'] = 'Location list';
      $response['data'] = $locationList;
    } else {
      $response['status_code'] = 200;
      $response['message'] = 'Location not found';
      $response['data'] = [];
    }
    return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
  }
}
