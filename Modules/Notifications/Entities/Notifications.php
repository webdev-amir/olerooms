<?php

namespace Modules\Notifications\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Modules\Booking\Entities\Booking;
use Modules\ScheduleVisit\Entities\ScheduleVisit;

class Notifications extends Model
{

    protected $fillable = [
        'user_id', 'data', 'read_at', 'create_user','type_id','type'
    ];

    protected $appends =['created_date'];

    public function getUserInfo()
    {
        return $this->belongsTo("App\Models\User", "user_id", "id");
    }

    public function getCreateUserInfo()
    {
            return $this->belongsTo("App\Models\User", "create_user", "id");
    }

    public function save(array $options = [])
    {
        $check = parent::save($options); // TODO: Change the autogenerated stub
        if ($check) {
            Cache::forget("review_" . $this->object_model . "_" . $this->object_id);
        }
        return $check;
    }

    public function getCreatedDateAttribute()
    {
        return date('d M Y h:i:s A', strtotime($this->created_at));
    }
    
    public function getBookingInfo()
    {
             return $this->belongsTo(Booking::class, 'type_id', 'slug');
    }
   
    public function getScheduleInfo()
    {
             return $this->belongsTo(ScheduleVisit::class, 'type_id', 'id');
    }



}