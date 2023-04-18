<?php

namespace Modules\Contactus\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Contactus extends Model
{
    use Sluggable;

	protected $table = "contact_us";

  	protected $fillable = ['slug','first_name','email','phone','message','created_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'first_name',
                'method'=>function ($string, $separator) {
                        return md5(microtime());
                    },
                'onUpdate' => false
            ]
        ];
    }

    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }
}
