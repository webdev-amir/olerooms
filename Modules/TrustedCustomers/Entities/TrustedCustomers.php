<?php

namespace Modules\TrustedCustomers\Entities;

use URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class TrustedCustomers extends Model
{
    use Sluggable;

    protected $table = "trusted_customers";

    protected $fillable = ['slug', 'name', 'designation', 'description', 'image', 'rating', 'status'];

    protected $deleted = 'deleted_at';

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

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    static public function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    //Getting picture path
    public function getPicturePathAttribute()
    {
        return \Storage::disk('s3')->url('trustedcustomers/'. $this->image);

        $image = \URL::to('storage/app/public/trustedcustomers/' . $this->image);
        if (\Storage::exists('/public/trustedcustomers/' . $this->image)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }
        return $filename;
    } 
}
