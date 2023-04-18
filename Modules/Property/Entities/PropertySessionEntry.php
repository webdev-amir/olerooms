<?php

namespace Modules\Property\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Modules\PropertyType\Entities\PropertyType;

class PropertySessionEntry extends Model
{
    use Sluggable;

    protected $table = "property_session_entry";

    protected $fillable = ['slug', 'user_id', 'property_type', 'steps', 'current_step', 'step_data', 'created_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'user_id',
                'method' => function ($string, $separator) {
                    return md5(microtime());
                },
                'onUpdate' => false
            ]
        ];
    }

    static public function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }



    public function getStepsDataAttribute()
    {
        return json_decode($this->step_data);
    }

    public function getStepsFirstAttribute()
    {
        return $this->StepsData->step_1;
    }

    public function getStepsSecondAttribute()
    {
        return $this->StepsData->step_2;
    }

    public function getStepsThirdAttribute()
    {
        return $this->StepsData->step_3;
    }

    public function getStepsFourthAttribute()
    {
        return $this->StepsData->step_4;
    }

    public function getCoverImagePathAttribute()
    {
        return ($this->StepsThird->cover_image) ? \Storage::disk('s3')->url('property/' . auth()->id() . '/rooms/' . $this->StepsThird->cover_image) : NULL;
    }

    public function getSelfieImagePathAttribute()
    {
        return ($this->StepsSecond->upload_selfie) ? \Storage::disk('s3')->url('property/' . auth()->id() . '/' . $this->StepsSecond->upload_selfie) : NULL;
    }

    public function getAgreementPathAttribute()
    {
        return ($this->StepsSecond->upload_agreement) ? \Storage::disk('s3')->url('property/' . auth()->id() . '/' . $this->StepsSecond->upload_agreement) : NULL;
    }

    public function getRoomVideoAttribute()
    {
        return ($this->StepsThird->video) ? \Storage::disk('s3')->url('property/' . auth()->id() . '/rooms/' . $this->StepsThird->video) : NULL;
    }

    public function getS3RoomVideoDownloadPathAttribute()
    {
        return ($this->StepsThird->video) ? ('property/' . auth()->id() . '/rooms/' . $this->StepsThird->video) : NULL;
    }


    public function getRoomImagesPathAttribute()
    {
        return \Storage::disk('s3')->url('property/' . auth()->id() . '/rooms') . '/' ?? NULL;
    }
}
