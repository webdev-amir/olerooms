<?php

namespace Modules\Property\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class PropertyRooms extends Model
{
    use HasFactory;
    use Sluggable, SluggableScopeHelpers;

    protected $table = "property_rooms";
    protected $fillable = [
        'slug',
        'property_id',
        'room_type',
        'is_ac',
        'is_non_ac',
        'ac_total_seats',
        'ac_rented_seats',
        'ac_amount',
        'non_ac_total_seats',
        'non_ac_rented_seats',
        'non_ac_amount',
        'ac_is_food_included',
        'non_ac_is_food_included',
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'id',
                'method' => function ($string, $separator) {
                    return md5(microtime());
                },
                'onUpdate' => false
            ]
        ];
    }

    public function property()
    {
        return $this->belongsTo("Modules\Property\Entities\Property", "property_id");
    }

    public function propertySingleRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class,"property_id","property_id")->where('room_type','single');
    }

    public function propertyDoubleRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class,"property_id","property_id")->where('room_type','double');
    }

    public function propertyTripleRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class,"property_id","property_id")->where('room_type','triple');
    } 

    public function propertyQuadrupleRoomImages()
    {
        return $this->hasMany(PropertyRoomImages::class,"property_id","property_id")->where('room_type','quadruple');
    }
}
