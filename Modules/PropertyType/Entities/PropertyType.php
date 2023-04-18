<?php

namespace Modules\PropertyType\Entities;

use URL;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Coupon\Entities\Coupon;

class PropertyType extends Model
{
    use Sluggable, SoftDeletes;

    protected $table = "property_types";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'name', 'description', 'image', 'status', 'created_at', 'is_partial'
    ];

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
                'onUpdate' => false
            ]
        ];
    }

    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }


    static public function getIdBySlug($slug)
    {
        $property_type =  static::where('slug', $slug)->first();
        return $property_type->id;
    }

    //Getting picture path
    public function getPicturePathAttribute()
    {
        return \Storage::disk('s3')->url('propertytype/' . $this->image);
        //down code used for own system upload file
        return ($this->image) ? URL::to('storage/app/public/propertytype/' . $this->image) : NULL;
    }

    //Getting picture path
    public function getAdminCommissionTextAttribute()
    {
        if ($this->commission_type == 'percentage') {
            $text = $this->commission . '% of every booking will be charged by Ole Rooms';
        } else {
            $text = numberformatWithCurrency($this->commission) . ' of every booking will be charged by Ole Rooms';
        }
        return $text;
    }

    public function propertyGlobalOffers()
    {
        return $this->hasMany(Coupon::class, "property_type_id")->where('is_global_apply', 1)->where([['end_date', '>=', now()], ['start_date', '<=', now()]]);
    }
}
