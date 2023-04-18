<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Api\ApiRepositoryInterface as ApiRepo;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use App\Repositories\Frontend\FrontendRepositoryInterface as FrontendRepositoryRepo;
use Modules\Partners\Entities\Partners;
use Modules\Slider\Entities\Slider;
use Modules\TrustedCustomers\Entities\TrustedCustomers;
use Modules\City\Entities\City;
use Modules\Property\Entities\Property;
use Softon\Sms\Facades\Sms;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiRepo $ApiRepo, CommonRepo $CommonRepo, FrontendRepositoryRepo $FrontendRepositoryRepo)
    {
        $this->ApiRepo = $ApiRepo;
        $this->CommonRepo = $CommonRepo;
        $this->FrontendRepositoryRepo = $FrontendRepositoryRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return view('home');
    }

    public function downloadS3File(Request $request)
    {
        $this->CommonRepo->downloadS3File($request);
    }


    public function storePropertySessionId(Request $request)
    {
        session()->put('sessionPropertySlug', $request->property_slug);
        return session('sessionPropertySlug');
    }

    public function index()
    {
        $propertyTypes = \Cache::remember('propertyTypes', 300, function () {
            return  $this->CommonRepo->getPropertyTypesOptions();
        });
        $sliders = Slider::active()->latest()->take(2)->latest()->get();
        $trusted_customers = TrustedCustomers::active()->take(8)->latest()->get();
        $partners = Partners::active()->latest()->take(25)->get();
        $cities = City::where('status', '!=', 0)->orderBy('status','asc')->orderBy('name','asc')->take(10)->get();
        $dealoftheday = $this->FrontendRepositoryRepo->getDealOfTheDayForSlider();
        $featured = $this->FrontendRepositoryRepo->getFeaturedPropertyForSlider();
        return view('home', compact('trusted_customers', 'dealoftheday', 'featured', 'partners', 'sliders', 'cities', 'propertyTypes'));
    }

    public function cacheOptimize()
    {
        //\Artisan::call('route:cache'); //not wotk home url '/'
        \Artisan::call('config:cache');
        \Artisan::call('view:cache');
        \Artisan::call('event:clear');
        //\Artisan::call('optimize');
        //\Artisan::call('optimize:clear');
        dd('optimize successfully');
    }

    public function cacheClear()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        \Artisan::call('clear-compiled');
        \Artisan::call('config:clear');
        dd('Cleared cache successfully');
    } 

    public function testAlgo()
    {
        $n = 10;
        $arr = [2,4,2,6,1,7,8,9,2,1];//rating
        //sample output = 4
        return "Output Total Candies is:- " .$this->candies($n, $arr);
    }

    function candies($n, $arr) {
        $length = sizeof($arr);
        $output = 0;
        $finalCandy[0] = 1;
        for ($i = 1; $i < $n; $i++) {
            if($arr[$i]>$arr[$i-1]){
                 $finalCandy[$i] = $finalCandy[$i-1]+1; 
            }else{
               $finalCandy[$i] = 1;  
            }
        }
        //pr($finalCandy);
        $output = array_sum($finalCandy);
        return $output;
    }
}
