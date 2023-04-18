<?php

namespace Modules\Review\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Booking\Entities\Booking;
use Modules\Property\Entities\Property;

class Review extends Model
{
    use HasFactory;
    protected $table = 'review';
    protected $fillable = ['user_id', 'object_id', 'booking_id', 'rate_number', 'object_model', 'content', 'reply_content', 'author_ip', 'title', 'publish_date', 'update_user', 'lang','status','replied_at'];


    public function user()
    {
       return $this->belongsTo("App\Models\User", "user_id", "id")->withTrashed();
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id')->withTrashed();
    }

    public function vendor()
    {
        return $this->belongsTo("App\Models\User", $this->property->user_id)->withTrashed();
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'object_id')->withTrashed();
    }
}
