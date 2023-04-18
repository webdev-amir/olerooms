<?php

namespace Modules\Users\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Session, View, Response, config, Auth;
use App\Http\Controllers\Controller;
use Modules\Users\Entities\UserWishlist;
use Modules\Property\Repositories\Frontend\MyPropertyRepositoryInterface as MyPropertyRepository;

class UserWishListController extends Controller
{
    protected $userWishListClass;
    public function __construct(MyPropertyRepository $MyPropertyRepository)
    {
        $this->middleware(['auth', 'prevent-back-history']);
        $this->userWishListClass = UserWishlist::class;
        $this->MyPropertyRepository = $MyPropertyRepository;
    }

    public function index(Request $request)
    {
        $records = $this->MyPropertyRepository->getMyWishlist($request);
        // pr($records);
        if ($request->ajax()) {
            return Response::json(array('body' => json_encode(View::make('property::frontend.wishlist.ajax_my_wishlist', compact('records'))->withModel('loans')->render())));
        }
        return view('property::frontend.wishlist.index', compact('records'));
    }

    public function handleWishList(Request $request)
    {
        $object_id = $request->input('object_id');
        $object_model = $request->input('object_model');
        if (empty($object_id)) {
            return $this->sendError(__("Property ID is required"));
        }
        if (empty($object_model)) {
            return $this->sendError(__("Property type is required"));
        }
        if(auth()->user()->role_id == 3){
            $response['message'] = trans('flash.error.please_login_with_customer');
            $response['type'] = 'error';
            return $response;
        }
        $meta = $this->userWishListClass::where("object_id", $object_id)
            ->where("object_model", $object_model)
            ->where("user_id", Auth::id())
            ->first();
        if (!empty($meta)) {
            $meta->delete();
            return $this->sendSuccess(['class' => ""]);
        }
        $meta = new $this->userWishListClass($request->input());
        $meta->user_id = Auth::id();
        $meta->save();
        return $this->sendSuccess(['class' => "active loading"]);
    }

    public function remove(Request $request)
    {
        $meta = $this->userWishListClass::where("object_id", $request->input('id'))
            ->where("object_model", $request->input('type'))
            ->where("user_id", Auth::id())
            ->first();
        if (!empty($meta)) {
            $meta->delete();
            return redirect()->back()->with('success', __('Delete success!'));
        }
        return redirect()->back()->with('success', __('Delete fail!'));
    }
}
