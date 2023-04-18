<?php

namespace Modules\Slider\Entities;

use URL;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
   	use Sluggable,SluggableScopeHelpers;

	protected $table = "slider";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','title','description','slider_order','banner_image','url','status','created_at'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'banner_image',
                'method'=>function ($string, $separator) {
                        return md5(microtime());
                    },
                'onUpdate'=> false
            ]
        ];
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }
    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }

	//Getting picture path
	public function getPicturePathAttribute()
    {
        return \Storage::disk('s3')->url('slider/'. $this->banner_image);
        //down code used for own system upload files
        return ($this->banner_image) ? URL::to('storage/app/public/slider/'.$this->banner_image) : NULL;
    }
}
