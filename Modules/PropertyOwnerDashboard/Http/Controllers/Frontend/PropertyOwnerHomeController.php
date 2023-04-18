<?php

namespace Modules\PropertyOwnerDashboard\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\Api\ApiRepositoryInterface as ApiRepo;
use Modules\PropertyOwnerDashboard\Repositories\Frontend\Homepage\PropertyOwnerRepositoryInterface as PropertyOwnerHomeRepo;

class PropertyOwnerHomeController extends Controller
{
    public function __construct(ApiRepo $ApiRepo, PropertyOwnerHomeRepo $PropertyOwnerHomeRepo)
    {
        $this->ApiRepo = $ApiRepo;
        $this->PropertyOwnerHomeRepo = $PropertyOwnerHomeRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function home()
    {
        $trustedCustomers = $this->PropertyOwnerHomeRepo->getTrustedCustomData();               
        $partners = $this->PropertyOwnerHomeRepo->getPartnersCustomData();               
        return view('propertyownerdashboard::frontend.home', compact('trustedCustomers','partners'));
    }
}
