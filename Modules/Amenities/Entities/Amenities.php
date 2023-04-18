<?php

namespace Modules\Amenities\Entities;

use URL;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Amenities extends Model
{
    use Sluggable, SluggableScopeHelpers, SoftDeletes;

    protected $table = "amenities";

    protected $fillable = ['slug', 'name', 'image', 'status', 'created_at'];

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

    static public function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    //Getting picture path
    public function getPicturePathAttribute()
    {
        return Storage::disk('s3')->url('amenity/'.$this->image);
        $image = \URL::to('storage/app/public/amenity/'.$this->image);
        if(\Storage::disk('public')->exists('/amenity/' . $this->image )){
            $filename = $image;
        }else{
             $filename = \URL::to('img/noamenities.jpg');
        }
        return ($this->image && $this->image != 'noamenities.jpg') ? $filename : \URL::to('img/noamenities.jpg');
    }
}
