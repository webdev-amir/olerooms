<?php

namespace Modules\ScheduleVisit\Repositories\Backend;

use config, DB, Session;
use DataTables;
use Carbon\Carbon;
use Modules\ScheduleVisit\Entities\ScheduleVisit;
use Modules\Property\Entities\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ScheduleVisitRepository implements ScheduleVisitRepositoryInterface
{

    public $ScheduleVisit;
    protected $model = 'ScheduleVisit';

    function __construct(ScheduleVisit $ScheduleVisit, User $User, Property $Property)
    {
        $this->ScheduleVisit = $ScheduleVisit;
        $this->Users = $User;
        $this->Property = $Property;
    }

    public function getRecord($id)
    {
        return $this->ScheduleVisit->find($id);
    }

    public function getRecordBySlug($slug)
    {
        $record = $this->ScheduleVisit->findBySlug($slug);

        return $record;
    }

    public function getAllRecords($request)
    {
        $myvisits = $this->ScheduleVisit->whereNotIn('status', ScheduleVisit::notAcceptedStatus)->whereHas('scheduleVisitProperty', function ($query) {
        });

        if ($request->get('status')) {
            if ($request->get('status') == 'past_visit') {
                $myvisits = $myvisits->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date", '<', now()->format("Y-m-d"));
                });
            } else if ($request->get('status') == 'upcoming_visit') {
                $myvisits = $myvisits->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date", '>', now()->format("Y-m-d"));
                });
            } else if ($request->get('status') == 'active') {
                $myvisits = $myvisits->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date", '=', now()->format("Y-m-d"));
                });
            }else if ($request->get('status') == 'cancelled') {
                $myvisits = $myvisits->where("status", '=', 'cancelled');
            }
        }

        return $myvisits->latest()->paginate(\config::get('custom.default_pagination'));
    }

    public function visitDetailsRecord($slug)
    {
        $myvisitData = $this->ScheduleVisit->with(["scheduleVisitProperty", 'scheduleVisitStartingProperty'])->where('slug', $slug)
            ->where('status', '!=', 'request')
            ->first();
        return $myvisitData;
    }
}
