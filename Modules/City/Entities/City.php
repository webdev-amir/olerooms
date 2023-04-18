<?php

namespace Modules\City\Entities;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'image', 'status','state_id'];

    protected $table  = 'cities';

    public function author()
    {
        return $this->belongsTo("Modules\City\Entities\State", "state_id", "id")->withDefault();
    }

    public function state()
    {
        return $this->belongsTo("Modules\City\Entities\State", "state_id", "id")->withDefault();
    }

    public function areas()
    {
        return $this->hasMany(Area::class, "city_id")->where('status', 1);
    }

    //Get picture thumnail path
    public function getThumbPicturePathAttribute()
    {
        return \Storage::disk('s3')->url('city/thumbnail/' . $this->image);

        $image = \URL::to('storage/app/public/city/thumbnail/' . $this->image);
        if ($this->image && \Storage::exists('/public/city/thumbnail/' . $this->image)) {
            $filename = $image;
        } else {
            $filename = \URL::to('img/nocity.svg');
        }
        return $filename;
    }
}
