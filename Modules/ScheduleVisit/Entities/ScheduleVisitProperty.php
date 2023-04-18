<?php

namespace Modules\ScheduleVisit\Entities;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Modules\Property\Entities\Property;

class ScheduleVisitProperty extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;

    protected $table = 'schedule_visit_property';
    
    protected $fillable = ['slug','user_id','property_id','schedule_visits_id','visit_date','visit_time','visit_date_time'];
    
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

    public function property()
    {
        return $this->belongsTo('Modules\Property\Entities\Property', 'property_id')->withTrashed();
    }
    
   

    public function getVisitingDateAndTimeAttribute()
    {
       return display_date($this->visit_date).' at '. display_time($this->visit_time);
    }

    public function vendor()
    {
        return $this->hasOne("App\Models\User", "id", 'user_id')->withTrashed();
    }
}
