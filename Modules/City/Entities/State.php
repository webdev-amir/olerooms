<?php

namespace Modules\City\Entities;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public $timestamps = false;

    protected $fillable = ['name','status','stateCode'];

    protected $table  = 'states';
    
    public function country()
    {
        return $this->belongsTo("Modules\City\Entities\Country", "country_id", "id")->withDefault();
    }

    public function city()
    {
        return $this->hasMany(City::class,"state_id")->where('status',1);
    }
}
