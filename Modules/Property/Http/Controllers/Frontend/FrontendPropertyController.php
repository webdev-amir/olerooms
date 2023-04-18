<?php

namespace Modules\Property\Http\Controllers\Frontend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Property\Entities\Property;
use Modules\Property\Repositories\Backend\PropertyRepositoryInterface as PropertyRepo;
use Modules\PropertyType\Entities\PropertyType;
use App\Repositories\Frontend\FrontendRepositoryInterface as FrontendRepository;
use Session, View, Auth, Response, config;

class FrontendPropertyController extends Controller
{
    protected $propertyClass;

    public function __construct(PropertyRepo $PropertyRepo, FrontendRepository $FrontendRepository)
    {

        $this->middleware(['auth', 'verified', 'prevent-back-history'], ['except' => [
            'index', 'mapSearch', 'getAutocompleteLocationsLists'
        ]]);
        $this->PropertyRepo = $PropertyRepo;
        $this->FrontendRepository = $FrontendRepository;
        $this->propertyClass = Property::class;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $default_property_type = config('custom.default_propery_type_search');
        $search_property_type = $request->get('property_type') ?? $default_property_type;
        $filters = availableSearchFilter($search_property_type);
        $propertyTypes = \Cache::remember('propertyTypes', 300, function () {
            return  $this->FrontendRepository->getPropertyTypesForOptions();
        });

        $stateLists = \Cache::remember('stateLists', 300, function () {
            return  $this->FrontendRepository->getstateListsForOptions();
        });

        $cityList = \Cache::remember('cityList', 300, function () {
            return  $this->FrontendRepository->getCityData();
        });
        $list = call_user_func([$this->propertyClass, 'search'], $request);

        if ($request->map_value == 'show_map') {
            $markers = [];
            if (!empty($list)) {
                foreach ($list as $row) {
                    
                    $markers[] = [
                        "id"      => $row->id,
                        "title"   => $row->title,
                        "lat"     => (float)($row->DummyLat),
                        "lng"     => (float)($row->DummyLong),
                        "infobox" => view('property::frontend.layouts.search.loop-gird', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render(),
                        'marker' => url('img/map-marker.png'),
                    ];
                }
            }

            $data = [
                'rows' => $list,
                'property_types' => $propertyTypes,
                'tour_location' => [],
                'markers' => $markers,
                "blank" => 1,
                "seo_meta" => []
            ];

            return view('property::frontend.search-map', $data);
        }



        $priceFilter = config('custom.price_filter');
        $ratingFilter = config('custom.rating_filter');
        $flatSizeFilter = config('custom.flat_size_filter');
        $furnitureFilter = config('custom.furniture_filter');
        $availableForFilter = config('custom.property_available_for');
        $occupancyFilter = config('custom.occupancy_filter');
        $bhkTypeFilter = config('custom.bhk_type');
        $roomTypeFilter = config('custom.room_ac_type_filter');
        $roomStandardFilter = config('custom.room_standard_filter');
        $capacityListFilter = config('custom.capacity_filter');
        $sortBy = config('custom.search_sort_by');

        $data = [
            'rows' => $list,
            'allowedFilters' => $filters,
            'propertyTypeFilter' => $propertyTypes,
            'cityListFilter' => $cityList,
            'stateListFilter' => $stateLists,
            'priceFilter' => $priceFilter,
            'availableForFilter' => $availableForFilter,
            'furnitureFilter' => $furnitureFilter,
            'ratingFilter' => $ratingFilter,
            'flatSizeFilter' => $flatSizeFilter,
            'occupancyFilter' => $occupancyFilter,
            'bhkTypeFilter' => $bhkTypeFilter,
            'roomTypeFilter' => $roomTypeFilter,
            'roomStandardFilter' => $roomStandardFilter,
            'capacityListFilter' => $capacityListFilter,
            'sortBy' => $sortBy,

        ];

        if ($request->ajax()) {
            return Response::json(array('html_data' => json_encode(View::make('frontend.includes.search.ajax_properties_list', $data)->render())));
        }

        return view('property::frontend.search-property', $data);
    }

    public function mapSearch(Request $request)
    {
        $propertyTypes = \Cache::remember('propertyTypes', 300, function () {
            return  PropertyType::where('status', 1)->get();
        });
        $is_ajax = $request->query('_ajax');
        $list = call_user_func([$this->propertyClass, 'searchMap'], $request);
        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {
                $markers[] = [
                    "id"      => $row->id,
                    "title"   => $row->title,
                    "lat"     => (float)$row->lat,
                    "lng"     => (float)$row->long,
                    // "gallery" => $row->getGallery(true),
                    "infobox" => view('property::frontend.layouts.search.loop-gird', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render(),
                    'marker' => url('images/icons/png/pin.png'),
                    // 'marker' => get_file_url(setting_item("space_icon_marker_map"), 'full') ?? url('images/icons/png/pin.png'),
                ];
            }
        }

        $data = [
            'rows' => $list,
            'property_types' => $propertyTypes,
            'tour_location' => [],
            // 'property_min_max_price' => $this->propertyClass::getMinMaxPrice(),
            'markers' => $markers,
            "blank" => 1,
            "seo_meta" => []
        ];

        if ($is_ajax) {
            return $this->sendSuccess([
                'html'    => view('property::frontend.layouts.search-map.list-item', $data)->render(),
                "markers" => $data['markers']
            ]);
        }

        return view('property::frontend.search-map', $data);
    }

    public function createProperty(Request $request)
    {
        $propertyTypes = \Cache::remember('propertyTypes', 300, function () {
            return  PropertyType::where('status', 1)->get();
        });
        $sessionData = $this->PropertyRepo->getSessionEntryFormData();
        $sessionAllData = $this->PropertyRepo->getSessionEntryAllData();
        return view('property::frontend.manageProperty.create', compact('sessionData', 'sessionAllData'));
    }

    public function storePropertySession(Request $request)
    {
        $response = $this->PropertyRepo->storePropertyProcessSteps($request);
        return response()->json($response);
    }

    public function getAutocompleteLocationsLists(Request $request)
    {
        $response = $this->FrontendRepository->getAutocompleteLocationsLists($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
    }
}
