<?php

namespace Modules\Company\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Repositories\Frontend\Homepage\CompanyRepositoryInterface as CompanyHomeRepo;

class CompanyHomeController extends Controller
{
    public function __construct(CompanyHomeRepo $CompanyHomeRepo)
    {
        $this->CompanyHomeRepo = $CompanyHomeRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function home()
    {
        $trustedCustomers = $this->CompanyHomeRepo->getTrustedCustomData();
        $partners = $this->CompanyHomeRepo->getPartnersCustomData();
        return view('company::frontend.home', compact('trustedCustomers', 'partners'));
    }
}
