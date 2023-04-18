<?php

namespace Modules\Teams\Entities;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teams extends Model
{
    use HasFactory;
    use Sluggable, SluggableScopeHelpers, SoftDeletes;


    protected $fillable = ['slug', 'name', 'team_type', 'designation', 'description', 'image', 'status','order_number','linkedin_url','created_at'];

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
        return \Storage::disk('s3')->url('teams/' . $this->image);
        return ($this->image) ? URL::to('storage/app/public/teams/' . $this->image) : NULL;
    }
}
