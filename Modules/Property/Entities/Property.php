<?php

namespace Modules\Property\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Modules\Booking\Entities\Booking;
use Modules\City\Entities\Area;
use Modules\City\Entities\City;
use Modules\Coupon\Entities\Coupon;
use Modules\Review\Entities\Review;
use Modules\Users\Entities\UserWishlist;
use Modules\Settings\Entities\Settings;

class Property extends Model
{
    use HasFactory;

    use Sluggable, SluggableScopeHelpers;
    use SoftDeletes;

    protected $table = 'properties';
    public $type = 'property';

    protected $fillable = [
        'slug', 'user_id', 'property_type_id', 'state_id', 'city_id', 'area_id',
        'map_location', 'lat', 'long', 'full_address',
        'amount', 'security_deposit_amount', 'property_name', 'property_description', 'available_for',
        'total_seats', 'rented_seats', 'total_floors',
        'electricity_bill', 'amenities_ids', 'available_for_names', 'cover_image', 'video',
        'status', 'update_user', 'featured_property', 'upload_selfie', 'status_selfie', 'status_selfie_date', 'upload_agreement', 'status_agreement', 'status_agreement_date', 'deal_of_the_day', 'furnished_type',
        'bhk_type', 'floor_no', 'convenient_time', 'rooms', 'beds', 'guest_capacity', 'is_homestay_ac', 'is_publish', 'homestay_type', 'property_code', 'starting_amount', 'video_url', 'carpet_area', 'kitchen_modular', 'parking_space_avail', 'flat_no', 'rating_avg', 'search_address'
    ];


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'user_id',
                'method' => function ($string, $separator) {
                    return md5(microtime());
                },
                'onUpdate' => false
            ]
        ];
    }

    public function author()
    {
        return $this->belongsTo("App\Models\User", "user_id", "id")->withDefault();
    }

    public function propertyType()
    {
        return $this->belongsTo('Modules\PropertyType\Entities\PropertyType', 'property_type_id');
    }
    public function propertyRooms()
    {
        return $this->hasMany(PropertyRooms::class, "property_id");
    }

    public function propertyReviews()
    {
        return $this->hasMany(Review::class, "object_id")->where('status', 'publish');
    }

    public function propertyOffers()
    {
        return $this->hasMany(PropertyOffers::class, "property_id")->whereHas('coupon');
    }


    public function propertyTypeOffers()
    {
        return $this->hasMany(Coupon::class, "property_type_id", "property_type_id");
    }


    public function propertyDynamicRoomType()
    {
        return $this->hasOne(PropertyRooms::class, "property_id");
    }

    public function propertySingleRoomType()
    {
        return $this->hasOne(PropertyRooms::class, "property_id")->where('room_type', 'single')->with('propertySingleRoomImages');
    }

    public function propertyDoubleRoomType()
    {
        return $this->hasOne(PropertyRooms::class, "property_id")->where('room_type', 'double')->with('propertyDoubleRoomImages');
    }

    public function propertyTripleRoomType()
    {
        return $this->hasOne(PropertyRooms::class, "property_id")->where('room_type', 'triple')->with('propertyTripleRoomImages');
    }

    public function propertyQuadrupleRoomType()
    {
        return $this->hasOne(PropertyRooms::class, "property_id")->where('room_type', 'quadruple')->with('propertyQuadrupleRoomImages');
    }

    public function propertyStandardRoomType()
    {
        return $this->hasOne(PropertyRooms::class, "property_id")->where('room_type', 'standard');
    }

    public function propertyDeluxeRoomType()
    {
        return $this->hasOne(PropertyRooms::class, "property_id")->where('room_type', 'deluxe');
    }

    public function propertySuiteRoomType()
    {
        return $this->hasOne(PropertyRooms::class, "property_id")->where('room_type', 'suite');
    }

    public function propertySingleRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id")->where('room_type', 'single');
    }

    public function propertyDoubleRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id")->where('room_type', 'double');
    }

    public function propertyTripleRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id")->where('room_type', 'triple');
    }

    public function propertyQuadrupleRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id")->where('room_type', 'quadruple');
    }

    public function propertyStandardRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id")->where('room_type', 'standard');
    }

    public function propertyDeluxeRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id")->where('room_type', 'deluxe');
    }

    public function propertySuiteRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id")->where('room_type', 'suite');
    }

    public function propertyAllRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id")->where('room_type', 'all');
    }

    public function propertyPaymentInfo()
    {
        return $this->hasOne(PropertyPaymentInformation::class, "property_id");
    }

    public function propertyRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id");
    }

    public function propertyAmenities()
    {
        return $this->hasMany(PropertyAmenities::class, "property_id");
    }

    public function propertyAvailableFor()
    {
        return $this->hasMany(PropertyAvailableFor::class, "property_id");
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, "property_id");
    }

    public function city()
    {
        return $this->belongsTo('Modules\City\Entities\City', 'city_id');
    }

    public function area()
    {
        return $this->belongsTo('Modules\City\Entities\Area', 'area_id');
    }

    public function state()
    {
        return $this->belongsTo('Modules\City\Entities\State', 'state_id');
    }

    public function getPropertValueAttribute()
    {

        return ($this->propertyType) ? ucfirst($this->propertyType->name) : 'N/A';
    }


    public function getRatingAverageAttribute()
    {
        if ($this->propertyReviews->isNotEmpty()) {
            $ratings = $this->propertyReviews->pluck('rate_number')->toArray();
            $rateAvg = array_sum($ratings) / count($ratings);
            return ($this->propertyReviews) ? round($rateAvg, 1) : 0;
        }
        return 0;
    }

    public function getPropertStartingAmountAttribute()
    {
        return ($this->starting_amount != 0) ? $this->starting_amount : $this->amount;
    }



    public function getDummyLatAttribute()
    {
        $meters = 100;
        $coef = $meters * 0.0000089;
        return $this->lat + $coef;
    }



    public function getDummyLongAttribute()
    {
        $meters = 100;
        $coef = $meters * 0.0000089;
        return $this->long + $coef / cos(13.0406067 * 0.018);
    }


    public function getS3CoverImgDownloadPathAttribute()
    {
        return 'property/' . $this->user_id . '/rooms/' . $this->cover_image;
    }


    public function getCoverImgAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->user_id . '/rooms/' . $this->cover_image);
        //down code used for local system upload file only
        $image = \URL::to('storage/app/public/property/' . $this->user_id . '/rooms/' . $this->cover_image);
        if (\Storage::exists('/public/property/' . $this->user_id . '/rooms/' . $this->cover_image)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }
        return $filename;
        //return ($this->cover_image) ? $this->cover_image : url('/') . '/images/no-image.jpg';
    }

    public function getElectricityBillDownloadPathAttribute()
    {
        return 'property/' . $this->user_id . '/' . $this->electricity_bill;
    }


    public function getElectricityBillImgPathAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->user_id . '/' . $this->electricity_bill);
        //down code used for only local system uploads
        $image = \URL::to('storage/app/public/property/' . $this->user_id . '/' . $this->electricity_bill);
        if (\Storage::exists('/public/property/' . $this->user_id . '/' . $this->electricity_bill)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }


    public function getCoverImgThunbnailAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->user_id . '/rooms/thumbnail/' . $this->cover_image);
        //down code used for local system upload file only
        $image = \URL::to('storage/app/public/property/' . $this->user_id . '/rooms/thumbnail/' . $this->cover_image);
        if (\Storage::exists('/public/property/' . $this->user_id . '/rooms/thumbnail/' . $this->cover_image)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }

    public function getSelfieImgThunbnailAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->user_id . '/' . $this->upload_selfie);
        //down code used for only local system uploads
        $image = \URL::to('storage/app/public/property/' . $this->user_id . '/' . $this->upload_selfie);
        if (\Storage::exists('/public/property/' . $this->user_id . '/' . $this->upload_selfie)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }

    public function getAgreementImgThunbnailAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->user_id . '/' . $this->upload_agreement);
        //down code used for only local system uploads
        $image = \URL::to('storage/app/public/property/' . $this->user_id . '/' . $this->upload_agreement);
        if (\Storage::exists('/public/property/' . $this->user_id . '/' . $this->upload_agreement)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }



    public function getPropertyAgreementFromAdminDownloadPathAttribute()
    {
        $setting = Settings::where('slug', 'upload-document')->first();
        return 'settings/' . @$setting->val;
    }


    public function getPropertyAgreementFromAdminAttribute()
    {
        $setting = Settings::where('slug', 'upload-document')->first();

        return \Storage::disk('s3')->url('settings/' . @$setting->val);
        //down code used for only local system uploads
        $image = \URL::to('storage/app/public/settings/' . $setting->val);

        if (\Storage::exists('/public/settings/' . $setting->val)) {

            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }

    public function getS3MyPropertySelfieDownloadPathAttribute()
    {
        return ('property/' . $this->user_id . '/' . $this->upload_selfie);
    }

    // Download Selfie image for Admin side
    public function getMyPropertySelfieDownloadAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->user_id . '/' . $this->upload_selfie);
        $image = \URL::to('storage/app/public/property/' . $this->user_id . '/' . $this->upload_selfie);
        if (\Storage::exists('/public/property/' . $this->user_id . '/' . $this->upload_selfie)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }

    public function getMyPropertyAgreementDownloadPathAttribute()
    {
        return 'property/' . $this->user_id . '/' . $this->upload_agreement;
    }



    // Download Agreement for Admin side
    public function getMyPropertyAgreementDownloadAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->user_id . '/' . $this->upload_agreement);
        //down code used for only local system uploads
        $image = \URL::to('storage/app/public/property/' . $this->user_id . '/' . $this->upload_agreement);
        if (\Storage::exists('/public/property/' . $this->user_id . '/' . $this->upload_agreement)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }

    public function getAvailableForTypeAttribute()
    {
        $type = '';
        $availableForFilter = config('custom.property_available_for');
        if ($this->available_for) {
            $type = @$availableForFilter[$this->available_for];
        }
        return  $type ?? '';
    }

    public function getFurnishedTypeValueAttribute()
    {
        $type = '';
        $FurnishedTypeFilter = config('custom.furniture_filter');
        if ($this->furnished_type != null) {
            $type = $FurnishedTypeFilter[$this->furnished_type];
        }
        return  $type ?? '';
    }

    public static function search(Request $request)
    {

        $model_property = parent::query()->with(['city', 'propertyType'])->select("properties.*")->whereHas('author', function ($query) {
            $query->where('users.status', 1);
        });
        //skip property if vendor is not verified his profile details by admin
        $model_property->whereHas('author.userCompleteProfileVerifiredIfApproved', function ($query) use ($request) {
        });

        if ($request->get('coupon_code')) {
            $model_property->where(function ($query) use ($request) {
                $query->whereHas('PropertyOffers.coupon', function ($subquery1) use ($request) {
                    $subquery1->where('coupon.coupon_code', $request->get('coupon_code'));
                })->orWhereHas('propertyType.propertyGlobalOffers', function ($subquery2) use ($request) {
                    $subquery2->where('coupon_code', $request->get('coupon_code'));
                });
            });
        }

        if ($request->get('city_id')) {
            $model_property->where('city_id', trim($request->get('city_id')));
        }

        if ($request->get('available_for')) {
            $room_standard = explode(',', $request->get('available_for'));
            $model_property->whereHas('propertyAvailableFor', function ($query) use ($room_standard) {
                $query->whereIn('available_for', $room_standard);
            });
            // $model_property->where('available_for', trim($request->get('available_for')));
        }

        if ($request->get('state_id')) {
            $model_property->where('state_id', trim($request->get('state_id')));
        }

        if ($request->get('capacity')) {
            $model_property->where('guest_capacity', '>', trim($request->get('capacity')));
        }

        if ($request->get('area_id')) {
            $model_property->where('area_id', trim($request->get('area_id')));
        }

        if ($request->get('property_type')) {
            $model_property->where('property_type_id', trim($request->get('property_type')));
        }

        if ($request->get('searchKey')) {
            $model_property->where(function ($query) use ($request) {
                $query->where('property_name', 'LIKE', '%' . trim($request->get('searchKey')) . '%');
                $query->orWhere('property_code', 'LIKE', '%' . trim($request->get('searchKey')) . '%');
                $query->orWhere('map_location', 'LIKE', '%' . trim($request->get('searchKey')) . '%');
                $query->orWhere('search_address', 'LIKE', '%' . trim($request->get('searchKey')) . '%');
            });
        }

        if ($request->get('price_range')) {
            $price_range = $request->get('price_range');
            $pri_from = head(explode("-", $price_range));
            $pri_to = last(explode("-", $price_range));
            if (isset(explode("-", $price_range)[1])) {
                $model_property->whereBetween('starting_amount', [$pri_from, $pri_to]);
            } else {
                $model_property->where('starting_amount', '>=', $pri_from);
            }
        }

        if ($request->get('occupancy_type') || $request->get('occupancy_id')) {
            $occupancy = $request->get('occupancy_type') ?? $request->get('occupancy_id');
            $occupancy_type = explode(',', $occupancy);
            $seachType = [];
            foreach ($occupancy_type as $occupancy) {
                if (in_array($occupancy, ['single', 'double', 'triple', 'quadruple'])) {
                    $seachType[] = 'property' . ucfirst($occupancy) . 'RoomType';
                }
            }

            $model_property->where(function ($subQuery) use ($seachType) {
                $subQuery->whereHas($seachType[0]);
                if (count($seachType) > 1) {
                    for ($i = 1; $i <= count($seachType) - 1; $i++) {
                        $subQuery->orWhereHas($seachType[$i]);
                    }
                }
            });
        }

        if ($request->get('room_standard')) {
            $room_standard = explode(',', $request->get('room_standard'));

            $seachType = [];
            foreach ($room_standard as $occupancy) {
                if (in_array($occupancy, ['standard', 'deluxe', 'suite'])) {
                    $seachType[] = 'property' . ucfirst($occupancy) . 'RoomType';
                }
            }

            $model_property->where(function ($subQuery) use ($seachType) {
                $subQuery->whereHas($seachType[0]);
                if (count($seachType) > 1) {
                    for ($i = 1; $i <= count($seachType) - 1; $i++) {
                        $subQuery->orWhereHas($seachType[$i]);
                    }
                }
            });
        }

        // if ($request->get('bhk_type')) {
        //     $model_property->whereIn('bhk_type', explode(',', $request->get('bhk_type')));
        // }

        if ($request->get('bhk_type')) {
            $model_property->where('bhk_type', strtolower($request->get('bhk_type')));
        }


        if ($request->get('room_ac_type')) {
            $room_type =  $request->get('room_ac_type');
            if ($request->get('property_type') == 5) {
                if ($room_type == 'ac') {
                    $model_property->where('is_homestay_ac', 1);
                } else {
                    $model_property->where('is_homestay_ac', 0);
                }
            } else {
                if ($room_type == 'ac') {
                    $model_property->whereHas('propertyRooms', function (Builder $query) {
                        $query->where('is_ac', true);
                    });
                } else {
                    $model_property->whereHas('propertyRooms', function (Builder $query) {
                        $query->where('is_non_ac', true);
                    });
                }
            }
        }

        if ($request->get('check_in_date')) {
            //
        }
        if ($request->get('check_out_date')) {
            //
        }

        if ($request->get('guests')) {
            if ($request->get('property_type') == 5) {
                $model_property->where('guest_capacity', '>=', trim($request->get('guests')));
            }
        }

        if ($request->get('children')) {
            //
        }
        if ($request->get('adults')) {
            //
        }
        if ($request->get('available_size')) {
            //
        }

        if ($request->get('furniture_type')) {
            if ($request->get('furniture_type') == 'not_furnished') {
                $model_property->where('furnished_type', NULL);
            } else {
                $model_property->where('furnished_type', $request->get('furniture_type'));
            }
        }

        if ($request->get('rating')) {
            $model_property->where('rating_avg', '>=', $request->get('rating'));
        }

        $orderby = $request->input("orderby");

        switch ($orderby) {
            case "a-to-z":
                $model_property->orderBy('property_name', "asc");
                break;
            case "z-to-a":
                $model_property->orderBy('property_name', "desc");
                break;
            case "price-low-to-high":
                $model_property->orderBy('starting_amount', "asc");
                break;
            case "price-high-to-low":
                $model_property->orderBy('starting_amount', "desc");
                break;
            case "recommended":
                $model_property->orderBy('rating_avg', "desc");
                break;
            default:
                $model_property->orderBy("id", "desc");
        }



        if (!empty($request->query('limit'))) {
            $limit = $request->query('limit');
        } else {
            $limit = !empty(setting_item("space_page_limit_item")) ? setting_item("space_page_limit_item") : 9;
        }
        if ($request->get('map_value') && $request->get('map_value') == 'show_map') {
            return $model_property->where(['is_publish' => true, 'status' => 'publish'])->get();
        } else {
            return $model_property->where(['is_publish' => true, 'status' => 'publish'])->paginate($limit);
        }
    }

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function hasWishList()
    {
        return $this->hasOne(UserWishlist::class, 'object_id', 'id')->where('object_model', $this->type)->where('user_id', Auth::id() ?? 0);
    }

    public function isWishList()
    {
        if (auth()->user()) {
            if (!empty($this->hasWishList) and !empty($this->hasWishList->id)) {
                return 'active loading';
            }
        }
        return '';
    }

    public function getIsFavProductAttribute()
    {
        return (auth()->user()) ? (($this->hasWishList) ? true : false) : false;
    }



    public static function searchMap(Request $request)
    {
        $model_space = parent::query()->select("properties.*");
        $model_space->where("properties.status", "publish");
        $prtype_ids = $request->get('prtype_id');
        if ($request->get('property_type_id')) {
            $prtype_ids[] = $request->get('property_type_id');
        }
        if (!empty($prtype_ids)) {
            if (!is_array($prtype_ids))
                $prtype_ids = [$prtype_ids];
            $model_space->whereIn('property_type_id', $prtype_ids);
        }
        if ($request->get('lat') && $request->get('long')) {
            $coordinates['lat'] = $request->get('lat');
            $coordinates['long'] = $request->get('long');
            $radius = config('custom.default-search-radious-miles');
            if ($request->get('distance')) {
                $radius = $request->get('distance');
            }
            $model_space->isWithinMaxDistance($coordinates);
        } else {
            if (config('custom.is_loacation_required_for_search') && $request->query('_layout') != 'map') {
                $coordinates['lat'] = config('custom.default_latitude');
                $coordinates['long'] = config('custom.default_longitude');
                $radius = config('custom.default-search-radious-miles');
                if ($request->get('distance')) {
                    $radius = $request->get('distance');
                }
                $model_space->isWithinMaxDistance($coordinates, $radius);
            }
        }

        if ($request->get('prtype')) {
            $fieldSearch = $request->get('prtype');
            if (!empty($price_range = $request->query('price_range'))) {
                if (array_key_exists($fieldSearch, config('custom.price_filter_types'))) {
                    $pri_from = explode(";", $price_range)[0];
                    $pri_to = explode(";", $price_range)[1];
                    $raw_sql_min_max = "( (IFNULL(properties." . $fieldSearch . ",0) > 0 and properties." . $fieldSearch . " >= ? ) OR (IFNULL(properties." . $fieldSearch . ",0) <= 0 and properties." . $fieldSearch . " >= ? ) )
                                    AND ( (IFNULL(properties." . $fieldSearch . ",0) > 0 and properties." . $fieldSearch . " <= ? ) OR (IFNULL(properties." . $fieldSearch . ",0) <= 0 and properties." . $fieldSearch . " <= ? ) )";
                    $model_space->WhereRaw($raw_sql_min_max, [$pri_from, $pri_from, $pri_to, $pri_to]);

                    $orderby = $request->input("orderby");
                    switch ($orderby) {
                        case "price_low_high":
                            $model_space->orderBy($fieldSearch, "asc");
                            break;
                        case "price_high_low":
                            $model_space->orderBy($fieldSearch, "desc");
                            break;
                        default:
                            $model_space->orderBy("id", "desc");
                    }
                }
            }
        }

        if ($request->get('start') && $request->get('end')) {
            $startDate = date('Y-m-d', strtotime($request->get('start')));
            $endDate = date('Y-m-d', strtotime($request->get('end')));
            $model_space = $model_space->where('start_date', '<=', $startDate);
            $model_space = $model_space->where('end_date', '>=', $endDate);
        }

        if (!empty($request->query('limit'))) {
            $limit = $request->query('limit');
        } else {
            $limit = !empty(setting_item("space_page_limit_item")) ? setting_item("space_page_limit_item") : 9;
        }

        return $model_space->with(['propertyType'])->paginate($limit);
    }

    public function getPublishTitleAttribute()
    {
        return ($this->is_publish) ? 'Publish' : 'Un-publish';
    }

    public function getReversePublishTitleAttribute()
    {
        return ($this->is_publish) ? 'Un-publish' : 'Publish';
    }

    public function getRoomVideoAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->user_id . '/rooms/' . $this->video);
        //down code used for own system upload files
        $video = \URL::to('storage/app/public/property/' . $this->user_id . '/rooms/' . $this->video);
        if ($this->video && \Storage::exists('/public/property/' . $this->user_id . '/rooms/' . $this->video)) {
            $filename = $video;
        } else {
            $filename = \URL::to('img/novideo.png');
        }
        return $filename;
    }
    public function getS3RoomVideoDownloadPathAttribute()
    {
        return 'property/' . $this->user_id . '/rooms/' . $this->video;
    }

    public function getRoomVideoStatusAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->user_id . '/rooms/' . $this->video);

        $video = \URL::to('storage/app/public/property/' . $this->user_id . '/rooms/' . $this->video);
        if ($this->video && \Storage::exists('/public/property/' . $this->user_id . '/rooms/' . $this->video)) {
            $filename = $video;
        } else {
            $filename = NULL;
        }
        return $filename;
    }

    public function propertyTotalRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class, "property_id");
    }

    public function isBookable()
    {
        if ($this->status != 'publish' && $this->is_publish != true) return false;
        return true;
    }

    public function getYoutubeEmbededUrlAttribute()
    {
        if ($this->video_url) {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->video_url, $match);
            $youtube_id = @$match[1];
            if ($youtube_id) {
                return "https://www.youtube.com/embed/$youtube_id";
            }
            return NULL;
        }
        return NULL;
    }

    public function getCarpetAreaInSqAttribute()
    {
        if ($this->carpet_area) {
            return $this->carpet_area . '(sq)';
        }
    }
}
