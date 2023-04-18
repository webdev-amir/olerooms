<?php

namespace Modules\NewsUpdates\Entities;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsUpdates extends Model
{
    use HasFactory;
    use Sluggable, SluggableScopeHelpers, SoftDeletes;


    protected $fillable = ['slug', 'title', 'post_type', 'author', 'description', 'image', 'status', 'published_at', 'created_at'];

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
        return \Storage::disk('s3')->url('newsupdates/' . $this->image);

        $image = \URL::to('storage/app/public/newsupdates/thumbnail/' . $this->image);
        if ($this->image && \Storage::exists('/public/newsupdates/thumbnail/' . $this->image)) {
            $filename = $image;
        } else {
            $filename = nonewsPicturePath();
        }
        return $filename;
    }

    public function getThumbPicturePathAttribute()
    {
        return \Storage::disk('s3')->url('newsupdates/thumbnail/' . $this->image);

        $image = \URL::to('storage/app/public/newsupdates/thumbnail/' . $this->image);
        if ($this->image && \Storage::exists('/public/newsupdates/thumbnail/' . $this->image)) {
            $filename = $image;
        } else {
            $filename = nonewsPicturePath();
        }
        return $filename;
    }
}
