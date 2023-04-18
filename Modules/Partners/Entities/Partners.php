<?php

namespace Modules\Partners\Entities;

use URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Partners extends Model
{
    use Sluggable, SoftDeletes;
    
    protected $table = "partners";

    protected $fillable = ['name','slug','description','image','status'];

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

    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    //Getting picture path
    public function getPicturePathAttribute()
    {
        return \Storage::disk('s3')->url('partners/'. $this->image);
        //down code used for own system upload files
        return ($this->image) ? URL::to('storage/app/public/partners/'.$this->image) : NULL;
    }
}
