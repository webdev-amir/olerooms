<?php

namespace Modules\Property\Http\Controllers;

use Session, View, Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Property\Entities\Property;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use Modules\Property\Http\Requests\PropertyMediaRequest;

use Modules\Property\Repositories\Backend\PropertyRepositoryInterface as PropertyRepo;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct(PropertyRepo $PropertyRepo, CommonRepo $CommonRepo)
    {
        //$this->middleware(['ability','auth','prevent-back-history'])->except('getConfirmBox');
        $this->PropertyRepo = $PropertyRepo;
        $this->CommonRepo = $CommonRepo;
    }


    public function index(Request $request)
    {
        $records = $this->PropertyRepo->getAllRecords($request);
        if ($request->ajax()) {
            return Response::json(array('page' => $request->get('page'), 'body' => json_encode(View::make('property::includes.ajax_property_list', compact('records'))->withModel('property')->render())));
        }
        $propertyTypePluck = $this->CommonRepo->getPropertyTypesPluck();
        return view('property::index', compact('records', 'propertyTypePluck'))->withModel('property');
    }

    public function userIndex(Request $request, $id)
    {
        $records = $this->PropertyRepo->getUserRecord($request, $id);
        if ($request->ajax()) {
            return Response::json(array('body' => json_encode(View::make('property::includes.ajax_space_list', compact('records'))->withModel('property')->render())));
        }
        $propertyTypePluck = $this->CommonRepo->getPropertyTypesPluck();
        return view('property::index', compact('records', 'propertyTypePluck'))->withModel('property');
    }

    public function status(Request $request)
    {
        $response = $this->PropertyRepo->changeStatus($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return back();
    }

    public function showProperty(Request $request, $slug)
    {
        $data = $this->PropertyRepo->showProperty($slug);
        if (empty($data)) {
            Session::flash('error', 'Property not found.');
            return redirect()->route('property.index');
        }
        if ($request->ajax()) {
            return response()->json($data);
        }
        return view('property::show', compact('data'))->withModel('property');
    }


    public function saveMedia(PropertyMediaRequest $request, $user_id)
    {
        try {
            $response = $this->PropertyRepo->savePropertyPictureMedia($request, $user_id);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    /**
     * Change featured property.
     * return boolean value 
     */

    public function featuredProperty(Request $request)
    {
        $propertyCount = Property::where('featured_property', '=', 1)->get();
        $count = $propertyCount->count();
        $value =  $request->featured_property;
        $data = $this->PropertyRepo->getRecord($request->id);

        if ($data && $data->status == 'reject') {
            $response['message'] = "You can't select rejected property for Featured Property";
            $response['type'] = 'error';
        } else {
            if (($count <= 10 && $value == 0) || ($count < 10 && $value == 1)) {
                if ($data) {
                    $response = $this->PropertyRepo->updateFeaturedProperty($request->id, $value);
                    $response['type'] = 'success';
                    $response['message'] = 'Featured Property Updated';
                }
            } else {
                $response['message'] = 'Only 10 Featured Property Can Publish Please Change Any Other to Update This Property Featured ';
                $response['type'] = 'error';
            }
        }

        return response()->json($response);
    }

    public function DealoftheDay(Request $request)
    {
        $data =  $this->PropertyRepo->getRecord($request->id);
        if ($data) {
            $response = $this->PropertyRepo->updateDealoftheDay($request->id, $request->deal_of_the_day);
            $response['type'] = 'success';
            $response['message'] = 'Deal of the Day Updated';
        } else {
            $response['type'] = 'error';
            $response['message'] = 'Cannot Found the Deal of the Day';
        }
        return response()->json($response);
    }
}
