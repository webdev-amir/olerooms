<?php

namespace Modules\ScheduleVisit\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Entities\Payment;
use Modules\ScheduleVisit\Entities\ScheduleVisitProperty;

class ScheduleVisit extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;

    protected $fillable = ['slug', 'user_id', 'schedule_code', 'payment_id', 'cancel_request_date', 'cancellation_reason', 'status', 'amount', 'total', 'commission', 'commission_type', 'schedule_visit_cancelled_date', 'schedule_visit_cancelled_reject_date'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'user_id',
                'method' => function ($string, $separator) {
                    return md5(microtime());
                },
                'onUpdate' => false
            ]
        ];
    }

    const notAcceptedStatus = [
        'request'
    ];

    public function getBookingPaymentSuccessDetailUrl()
    {
        return route('schedulevisit.success', ['slug' => $this->slug]);
    }


    public function getBookingPaymentSuccessDetailUrlCompany()
    {
        return route('company.schedulevisit.success', ['slug' => $this->slug]);
    }

    public function getCancellationStatusAttribute()
    {
        $status = $this->schedule_visit_cancelled_reject_date ? 'rejected' : ($this->schedule_visit_cancelled_date ? 'accepted' : 'N/A');
        return $status;
    }

    public function getCancellationStatusDateAttribute()
    {
        $status = $this->schedule_visit_cancelled_reject_date ? date('d M Y, g:i a', strtotime($this->schedule_visit_cancelled_reject_date)) : ($this->schedule_visit_cancelled_date ? date('d M Y, g:i a', strtotime($this->schedule_visit_cancelled_date))  : 'N/A');
        return $status;
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id');
    }

     public function paymentCaptured()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id')->where('status','captured');
    }

    public function scheduleVisitProperty()
    {
        return $this->hasMany(ScheduleVisitProperty::class, 'schedule_visits_id');
    }

    public function scheduleVisitPropertyWithPropertyDetails()
    {
        return $this->hasMany(ScheduleVisitProperty::class, 'schedule_visits_id')->with('property');
    }

    public function scheduleVisitPropertyForUser()
    {
        return $this->hasOne(ScheduleVisitProperty::class, 'schedule_visits_id');
    }

    public function scheduleVisitPropertyForVendor()
    {
        return $this->hasMany(ScheduleVisitProperty::class, 'schedule_visits_id')->where('user_id', auth()->user()->id);
    }

    public function getTotalPropertyAttribute()
    {
        return $this->scheduleVisitProperty()->count();
    }

    public function customer()
    { 
        return $this->belongsTo("App\Models\User", "user_id", "id")->withTrashed();
    }

    public function author()
    {
        return $this->belongsTo("App\Models\User", "user_id", "id")->withDefault();
    }

    public function scheduleVisitStartingProperty()
    {
        return $this->hasOne(ScheduleVisitProperty::class, 'schedule_visits_id')->orderBy('visit_date_time', 'asc');
    }

    public function getCancellationBeforeDateAttribute()
    {
        if ($this->BookingRemainingHours > setting_item('schedule-cancelled-before-time') && $this->scheduleVisitStartingProperty->visit_date_time > now()) {
            return true;
        }
        return false;
    }

    public function getBookingRemainingHoursAttribute()
    {
        $start =   strtotime(\Carbon\Carbon::today());
        $end   =   strtotime($this->scheduleVisitStartingProperty->visit_date_time);
        $diff = $end - $start;
        $hours = $diff / (60 * 60);
        return $hours;
    }
}
