<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommonRepo $CommonRepo)
    {
      $this->middleware(['ability','auth']);
      $this->CommonRepo = $CommonRepo;
    }

    public function index(Request $request) 
    {
        $usersCount = $this->CommonRepo->getUserCountsByRoles($request,'customer');
        $vendorsCount = $this->CommonRepo->getUserCountsByRoles($request,'vendor');
        $bookingsCount = $this->CommonRepo->getBookingsCount($request);
        $paymentsCount = $this->CommonRepo->getPaymentsCount($request);
        $cancelledBookingsCount = $this->CommonRepo->getCancelledBookingsCount($request);
        $unapprovedVendorsCount = $this->CommonRepo->getUserCountsByRoles($request,'vendor','pending');

        return view('dashboard::index',compact('usersCount','vendorsCount','bookingsCount','paymentsCount','cancelledBookingsCount','unapprovedVendorsCount'));
    }
}
