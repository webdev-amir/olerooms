<?php

namespace Modules\StaticPages\Http\Controllers\Frontend;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\NewsUpdates\Entities\NewsUpdates;
use Modules\StaticPages\Repositories\Frontend\FrontendStaticPagesRepositoryInterface as FrontStaticPagesRepo;
use Modules\Faq\Entities\Faq;
use Modules\Partners\Entities\Partners;
use DB;
use Modules\Teams\Entities\Teams;
use View;

class FrontStaticPageController extends Controller
{

    public function __construct(FrontStaticPagesRepo $FrontStaticPagesRepo)
    {
        $this->FrontStaticPagesRepo = $FrontStaticPagesRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function howItWork(Request $request)
    {
        $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug('how-it-works');
        return view('staticpages::frontend.how_it_works', compact('pageInfo'));
    }

    public function faq(Request $request)
    {
        $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug('faq');
        $faqs = Faq::select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('status', 1)->orderBy('id', 'desc')->get();
        return view('staticpages::frontend.faq', compact('pageInfo', 'faqs'));
    }


    public function News(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->get('type');
            $records = $this->FrontStaticPagesRepo->getNewsUpdates($request);
            return response()->json(array('body' => json_encode(View::make('staticpages::frontend.ajax_my_newsupdate_list', compact('type', 'records'))->render())));
        }
        $type  = ($request->get('type')) ? $request->get('type') : '';
        $records = $this->FrontStaticPagesRepo->getNewsUpdates($request);
        return view('staticpages::frontend.news', compact('records', 'type'));
    }

    public function NewsDetail(Request $request, $slug)
    {
        $records = $this->FrontStaticPagesRepo->getNewsUpdatesDetails($slug);
        return view('staticpages::frontend.newsdetail', compact('records'));
    }

    public function aboutUs(Request $request)
    {
        $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug('about-ole-rooms');
        $partners = Partners::active()->latest()->take(25)->get();
        $teams_core = Teams::where(['team_type' => 'Core'])->orderBy('order_number','asc')->take(50)->get();
        $teams_exe =  Teams::where(['team_type' => 'Executive'])->orderBy('order_number','asc')->take(50)->get();
        return view('staticpages::frontend.about_us', compact('pageInfo', 'partners', 'teams_exe','teams_core'));
    }

    public function lifeatoleRooms($slug="life-at-ole-rooms", Request $request)
    {
        $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug($slug);
        if (is_null($pageInfo))
            // use either one of the two lines below. I prefer the second now
            // return Event::first('404');
            App::abort(404);

        return view('staticpages::frontend.lifeatolerooms', compact('pageInfo'));
    }

    public function show($slug=NULL, Request $request)
    {
        $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug($slug);
        if (is_null($pageInfo))
            // use either one of the two lines below. I prefer the second now
            // return Event::first('404');
            App::abort(404);

        return view('staticpages::frontend.show', compact('pageInfo'));
    }
}
