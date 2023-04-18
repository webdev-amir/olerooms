<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Property\Entities\Property;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Sluggable;
    use HasRoles;
    use Sortable;

    public static $guard_name = "admin";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'slug', 'username', 'name', 'email', 'phone', 'password', 'image', 'status', 'last_login_at', 'last_login_ip', 'email_verified_at', 'mobile_verified_at', 'role_id', 'company_name', 'address', 'address2', 'marital_status', 'gender', 'deactivate_at', 'dob', 'city', 'gst_no', 'device_token', 'occupation'

    ];

    protected $deleted = 'deleted_at';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
        'deactivate_at' => 'datetime',
    ];

    public $sortable = ['id', 'name', 'email', 'created_at'];

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
            ],
            'username' => [
                'source' => 'name',
                'onUpdate' => false
            ],
        ];
    }


    /**
     * The function return the full picture path by setter attributes.
     * 
     * @param array $slug 
     */
    public function getPicturePathAttribute()
    {
        return \Storage::disk('s3')->url('users/' . $this->image);
    }

    /**
     * The function return the full picture path for thumb by setter attributes.
     * 
     * @param array $slug 
     */
    public function getThumbPicturePathAttribute()
    {
        return \Storage::disk('s3')->url('users/' . $this->image);
    }

    public function getAvatarUrl()
    {
        return $this->getThumbPicturePathAttribute();
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->name);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userFeedback()
    {
        return $this->hasOne('Modules\Forms\Entities\UserFeedbacks', 'user_id');
    }

    public function getNotificationNumberAttribute()
    {
        return ($this->phone) ? $this->country_code . $this->phone : 'N/A';
    }

    /**
     * The attributes that should be has is_mobile Verifired.
     */
    public function mobileVerifired()
    {
        return $this->hasOne('Modules\Users\Entities\MobileVerification', 'user_id');
    }

    /**
     * The attributes that should be has is_mobile Verifired.
     */
    public function is_mobileVerifired()
    {

        return $this->mobile_verified_at;
    }

    public function userCompleteProfileVerifiredIfRejected()
    {
        return $this->hasOne('Modules\Users\Entities\UserCompleteProfileVerification', 'user_id')->orderBy('id', 'desc')->where('status', 'rejected');
    }



    public function userCompleteProfilePending()
    {
        return $this->hasOne('Modules\Users\Entities\UserCompleteProfileVerification', 'user_id')->orderBy('id', 'desc')->where('status', 'pending');
    }

    /**
     * The attributes that should be has is_user_profile_complete Verifired.
     */
    public function userCompleteProfileVerifired()
    {
        return $this->hasOne('Modules\Users\Entities\UserCompleteProfileVerification', 'user_id')->orderBy('id', 'desc')->where('status', '!=', 'rejected');
    }

    /**
     * The attributes that should be has userCompleteProfileVerifiredIfApproved.
     */
    public function userCompleteProfileVerifiredIfApproved()
    {
        return $this->hasOne('Modules\Users\Entities\UserCompleteProfileVerification', 'user_id')->orderBy('id', 'desc')->where('status', 'approved');
    }

    /**
     * The attributes that should be has is_mobile Verifired.
     */
    public function is_profileVerifired()
    {
        return $this->userCompleteProfileVerifired ? true : false;
    }

    public function getUserQRCodeImageAttribute()
    {
        return $this->userBankDetail ? $this->userBankDetail->QRCodeThumbnail : '';
    }


    public function getUserPanCardImageAttribute()
    {
        return $this->userBankDetail ? $this->userBankDetail->PanCardThumbnail : '';
    }

    public function getCancelledChequeImageAttribute()
    {
        return $this->userBankDetail ? $this->userBankDetail->CancelledChequeThumbnail : '';
    }


    public function getComponyLogoAttribute()
    {
        if ($this->userCompleteProfileVerifired) {
            return $this->userCompleteProfileVerifired->ComponyLogo;
        }
    }

    public function is_profileVerifiredApproved()
    {
        return ($this->userCompleteProfileVerifired && $this->userCompleteProfileVerifired->status == 'approved') ? true : false;
    }

    public function getErrorPicturePathAttribute()
    {
        return  \URL::to('img/nouser.jpg');
    }

    public function property()
    {
        return $this->hasMany(Property::class, "user_id");
    }

    public function getVendorMyPropertyCountAttribute()
    {
        $propertyCount = $this->hasMany(Property::class, "user_id")->count();
        if ($propertyCount > 0) {
            return $propertyCount;
        } else {
            return 'N/A';
        }
    }

    public function userToken()
    {
        return $this->hasOne('App\Models\JwtUserTokens', 'user_id');
    }

    public function userBankDetail()
    {
        return $this->hasOne('Modules\Users\Entities\UserBankDetails', 'user_id');
    }

    /** Wallet Functions **/
    /**
     * The attributes that should be has Verifired.
     */
    public function wallet()
    {
        return $this->hasMany('Modules\Wallet\Entities\Wallet', 'user_id');
    }


    public function redeemAmount()
    {
        return $this->hasMany('Modules\Wallet\Entities\RedeemCreditRequest', 'user_id');
    }

    public function getUserWalletCreditAmountAttribute()
    {
        return $this->wallet->where('type', 'credit')->sum('amount');
    }

    public function getUserWalletDebitAmountAttribute()
    {
        return $this->wallet->where('type', 'debit')->sum('amount');
    }

    public function getUserWalletAmountAttribute()
    {
        return $this->UserWalletCreditAmount - $this->UserWalletDebitAmount;
    }

    public function getUserTotalEarningsAttribute()
    {
        return $this->UserWalletCreditAmount - $this->UserRedeemRejectedAmount;
    }


    public function getUserRedeemRejectedAmountAttribute()
    {
        return $this->wallet->where('status', 'rejected')->sum('amount');
    }
    
    /** End Wallet Functions **/
}
