<?php

namespace Modules\City\Entities;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use Sluggable, HasFactory;

    protected $fillable = ['name','slug','city_id','status'];

    protected $table  = 'city_areas';
    
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }
    public function city()
    {
        return $this->belongsTo("Modules\City\Entities\City", "city_id", "id")->withDefault();
    }
}
