<?php

namespace Modules\PropertyOwnerDashboard\Repositories\Frontend\Homepage;

use Carbon\Carbon;
use DB, Mail, Session, Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\Partners\Entities\Partners;
use Modules\TrustedCustomers\Entities\TrustedCustomers;

class PropertyOwnerRepository implements PropertyOwnerRepositoryInterface
{
    public function getTrustedCustomData()
    {
        return TrustedCustomers::active()->latest()->get();
    }
    public function getPartnersCustomData()
    {
        return Partners::active()->latest()->get();
    }
}
