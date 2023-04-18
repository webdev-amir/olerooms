<?php

namespace Modules\Users\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class UserCompleteProfileVerification extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;

    protected $table = "user_complete_profile_verification";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'user_id', 'aadhar_card_number', 'gst_number', 'adhar_card_doc', 'selfy_image', 'logo_image', 'status', 'action_date', 'created_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'action_date',
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
                'source' => 'user_id',
                'method' => function ($string, $separator) {
                    return md5(microtime());
                },
                'onUpdate' => false
            ]
        ];
    }

    /**
     * The attributes that should be belongs to data
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
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


    public function getComponyLogoAttribute()
    {
        return \URL::to('public/images/ole-verified.svg');
        return \Storage::disk('s3')->url('users/' . $this->logo_image);

        $logo_image = \URL::to('storage/app/public/users/' . $this->logo_image);
        if ($this->logo_image && \Storage::exists('/public/users/' . $this->logo_image)) {
            $filename = $logo_image;
        } else {
            // $filename = noLogoPicturePath();
            $filename = '';
        }
        return $filename;
    }

    /**
     * The function return the full picture path for thumb by setter attributes.
     * 
     * @param array $slug 
     */
    public function getThumbAdharDocumentAttribute()
    {
        return ($this->adhar_card_doc && $this->adhar_card_doc != 'noimage.jpg') ? \Storage::disk('s3')->url('users/' . $this->adhar_card_doc) : 'N/A';

        return ($this->adhar_card_doc && $this->adhar_card_doc != 'noimage.jpg') ? \URL::to('storage/app/public/users/' . $this->adhar_card_doc) : 'N/A';
    }
    /**
     * The function return the full picture path for thumb by setter attributes.
     * 
     * @param array $slug 
     */
    public function getThumbSelfyImageAttribute()
    {
        return \Storage::disk('s3')->url('users/' . $this->selfy_image);


        $image = \URL::to('storage/app/public/users/' . $this->selfy_image);
        if (\Storage::exists('/public/users/' . $this->selfy_image)) {
            $filename = $image;
        } else {
            $filename = \URL::to('img/avtar.png');
        }

        return $filename;

        // return ($this->selfy_image && $this->selfy_image != 'noimage.jpg') ? \URL::to('storage/app/public/users/thumbnail/' . $this->selfy_image) : \URL::to('img/avtar.png');
    }


    public function getS3SelfyDownloadPathAttribute()
    {
        return 'users/' . $this->selfy_image;
    }

    public function getS3LogoDownloadPathAttribute()
    {
        return 'users/' . $this->logo_image;
    }

    public function getS3AadharDocPathAttribute()
    {
        return 'users/' . $this->adhar_card_doc;
    }

    /**
     * The function return the full picture path for thumb by setter attributes.
     * 
     * @param array $slug 
     */
    public function getThumbLogoImageAttribute()
    {
        return \Storage::disk('s3')->url('users/thumbnail/' . $this->logo_image);

        $logo_image = \URL::to('storage/app/public/users/thumbnail/' . $this->logo_image);
        if ($this->logo_image && \Storage::exists('/public/users/thumbnail/' . $this->logo_image)) {
            $filename = $logo_image;
        } else {
            $filename = noLogoPicturePath();
        }
        return $filename;
    }

    // Download Aadhar document for Admin side
    public function getVendorMyPropertyAadharDocDownloadAttribute()
    {
        return \Storage::disk('s3')->url('users/' . $this->adhar_card_doc);

        $image = \URL::to('storage/app/public/users/' . $this->adhar_card_doc);
        if (\Storage::exists('/public/users/' . $this->adhar_card_doc)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }
}
