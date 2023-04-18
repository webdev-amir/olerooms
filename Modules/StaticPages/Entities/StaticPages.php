<?php

namespace Modules\StaticPages\Entities;

use URL;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Modules\NewsUpdates\Entities\NewsUpdates;

class StaticPages extends Model
{
    use Sluggable,SluggableScopeHelpers;

	protected $table = "static_pages";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','name_en','description_en','meta_keyword_en','meta_description_en','banner_image','banner','created_at','banner_heading'
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
                'source' => 'name_en',
                'onUpdate'=>false
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
        return static::where('slug',$slug)->first();
    }
    
    public function getNameAttribute()
    {
        return (\App::isLocale('ja')) ? $this->name_en : $this->name_en;
    } 

    public function getDescriptionAttribute()
    {
        return (\App::isLocale('ja')) ? $this->description_en : $this->description_en;
    }

    public function getMetaKeywordAttribute()
    {
        return (\App::isLocale('ja')) ? $this->meta_keyword_en : $this->meta_keyword_en;
    }

    public function getMetaDescriptionAttribute()
    {
        return (\App::isLocale('ja')) ? $this->meta_description_en : $this->meta_description_en;
    }

    /**
     * The function return the full picture path by setter attributes.
     * 
     * @param array $slug 
     */
    public function getBannerPathAttribute()
    {
        return \Storage::disk('s3')->url('staticpages/' . $this->banner_image);
        
        return ($this->banner_image) ? \URL::to(config('staticpages.path.download_banner_image').$this->banner_image) : NULL;
    }
}
