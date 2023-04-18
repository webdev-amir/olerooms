<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Coupon extends Model
{
    use Sluggable, SoftDeletes;

    protected $table = "coupon";

    protected $fillable = ['title', 'slug', 'amount', 'offer_type', 'coupon_code', 'property_type_id', 'description', 'image', 'start_date', 'end_date', 'status','is_global_apply'];
    protected $appends = ['start_date_coupon', 'end_date_coupon'];
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
                'source' => 'title',
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
        return Storage::disk('s3')->url('coupon/thumbnail/' . $this->image);
        $image = \URL::to('storage/app/public/coupon/thumbnail/' . $this->image);
        if (\Storage::exists('/public/coupon/thumbnail/' . $this->image)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/no-image.jpg');
        }

        return $filename;
    }

    public function propertyType()
    {
        return $this->belongsTo('Modules\PropertyType\Entities\PropertyType', 'property_type_id');
    }
    //Getting picture path
    public function getStartDateCouponAttribute()
    {
        $date = display_date($this->start_date);
        return $date;
    }

    public function getEndDateCouponAttribute()
    {
        $date = display_date($this->end_date);
        return $date;
    }
}
