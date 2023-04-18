<?php

namespace Modules\Property\Http\Controllers\Frontend;

use Session, View, Response, config;
use Illuminate\Http\Request;
use Modules\Property\Entities\Property;
use Modules\Property\Http\Requests\StorePropertySessionRequest;
use Modules\Property\Http\Requests\PropertyRoomImagesRequest;
use App\Repositories\Frontend\FrontendRepositoryInterface as FrontendRepository;
use Modules\Property\Repositories\Frontend\MyPropertyRepositoryInterface as MyPropertyRepository;
use App\Http\Controllers\Controller;
use Modules\Property\Http\Requests\UpdatePropertySessionRequest;
use Modules\Property\Http\Requests\PropertyRoomVideoRequest;
use Modules\Property\Http\Requests\PropertyAgreementMediaRequest;
use Modules\PropertyType\Entities\PropertyType;

class MyPropertyController extends Controller
{
    protected $propertyClass;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FrontendRepository $FrontendRepository, MyPropertyRepository $MyPropertyRepository)
    {
        $this->middleware(['auth', 'prevent-back-history'])->except('show');
        $this->FrontendRepository = $FrontendRepository;
        $this->MyPropertyRepository = $MyPropertyRepository;
        $this->propertyClass = Property::class;
    }

    public function create(Request $request)
    {
        $propertyTypes = \Cache::remember('propertyTypes', 300, function () {
            return  $this->FrontendRepository->getPropertyTypesForOptions();
        });
        $amenitiesData = $this->FrontendRepository->getamenitiesData();
        $stateLists = \Cache::remember('stateLists', 300, function () {
            return  $this->FrontendRepository->getstateListsForOptions();
        });
        $row = new $this->propertyClass();
        $data = [
            'row'           => $row,
            'translation' => [],
            'space_location' => [],
            'location_category' => [],
            'attributes'    => [],
        ];
        if (session()->has('session_property_entry')) {
            if ($request->get('session') == 'false') {
                Session::forget('session_property_entry');
            }
        }
        if ($request->get('reedit')) {
            Session::put('session_property_entry', $request->get('reedit'));
        }

        $sessionData = $this->MyPropertyRepository->getSessionEntryFormData();
        $sessionAllData = $this->MyPropertyRepository->getSessionEntryAllData();
        if ($sessionData && $sessionData->step_1->property_type != '') {
            $propertyTypeData = PropertyType::where('slug', $sessionData->step_1->property_type)->first();
        } else {
            $propertyTypeData = [];
        }
        $sessionData;
        return view('property::frontend.manageProperty.create', compact('sessionData', 'sessionAllData', 'propertyTypes', 'amenitiesData', 'stateLists', 'propertyTypeData'), $data);
    }

    public function storeProperty(StorePropertySessionRequest $request)
    {
        $response = $this->MyPropertyRepository->storePropertyProcessSteps($request);
        return Response::json($response);
    }

    public function propertyAddSuccess(Request $request, $slug = '')
    {
        $property =  $this->MyPropertyRepository->getRecordBySlug($slug);
        return view('property::frontend.manageProperty.steps.create.step_5', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $slug)
    {
        $data =  $this->MyPropertyRepository->getRecordBySlug($slug);
        if ($data) {
            $propertyTypes = \Cache::remember('propertyTypes', 300, function () {
                return  $this->FrontendRepository->getPropertyTypesForOptions();
            });
            $amenitiesData = $this->FrontendRepository->getamenitiesData();
            $stateLists = \Cache::remember('stateLists', 300, function () {
                return  $this->FrontendRepository->getstateListsForOptions();
            });
            $sessionData = $data;
            $sessionAllData = $data;
            return view('property::frontend.manageProperty.edit', compact('sessionData', 'sessionAllData', 'propertyTypes', 'amenitiesData', 'stateLists'));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('manageProperty.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdatePropertySessionRequest $request, $id)
    {
        $response = $this->MyPropertyRepository->updatePropertyProcessSteps($request, $id);
        return Response::json($response);
    }

    public function show(Request $request, $slug)
    {
        $property = $this->MyPropertyRepository->getRecordBySlug($slug);
        if ($property) {
            $similarProperty = $this->MyPropertyRepository->getSimilarProperty($property);
            return view('property::frontend.manageProperty.details', compact('property', 'similarProperty'));
        }
        abort(404);
    }

    public function uploadRoomImages(PropertyRoomImagesRequest $request,$user_id)
    {
        try {
            $response = $this->MyPropertyRepository->uploadRoomImages($request,$user_id);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function uploadRoomVideo(PropertyRoomVideoRequest $request,$user_id)
    {
        try {
            $response = $this->MyPropertyRepository->uploadRoomVideo($request,$user_id);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function uploadAgreement(PropertyAgreementMediaRequest $request,$user_id)
    {
        try {
            $response = $this->MyPropertyRepository->propertyUploadAgreementMedia($request,$user_id);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
