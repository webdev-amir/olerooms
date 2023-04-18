<?php

namespace Modules\Agent\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Repositories\Frontend\Homepage\AgentRepositoryInterface as AgentHomeRepo;

class AgentHomeController extends Controller
{
    public function __construct(AgentHomeRepo $AgentHomeRepo)
    {
        $this->AgentHomeRepo = $AgentHomeRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function home()
    {
        $trustedCustomers = $this->AgentHomeRepo->getTrustedCustomData();
        $partners = $this->AgentHomeRepo->getPartnersCustomData();
        return view('agent::frontend.home', compact('trustedCustomers', 'partners'));
    }
}
