<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use App\Models\User;
use Modules\Booking\Entities\Booking;
use Modules\ScheduleVisit\Entities\ScheduleVisit;

class Payment extends Model
{
    use Sluggable,SluggableScopeHelpers;
   
    protected $table      = 'payments';

    protected $fillable = ['slug','type','type_id','order_id','bank','wallet','bank_transaction_id','entity','payment_gateway','transaction_id','fee','amount','tax','method','currency','status','email','contact','logs','create_user','ip_address'];
    
    const COMPLETED    = 'Succeeded'; 

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'transaction_id',
                'method'=>function ($string, $separator) {
                        return md5(microtime());
                    },
                'onUpdate'=>false
            ]
        ];
    }

    public function getLogsDataAttribute()
    {
        return json_decode($this->logs);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'create_user')->withTrashed();
    }

    public function author(){
        return $this->belongsTo(User::class,'create_user')->withTrashed();
    }

    public function Booking(){
        return $this->belongsTo(Booking::class,'type_id')->withTrashed();
    }
    public function ScheduleVisit(){
         return $this->belongsTo(ScheduleVisit::class,'type_id')->withTrashed();
    }

    public function getStatusNameAttribute()
    {
        return booking_status_to_text($this->status);
    }

    public function getFullTranDateAttribute()
    {
        return \Carbon\Carbon::parse($this->TranDate)->format(\Config::get('custom.default_date_formate'));
    }
}
