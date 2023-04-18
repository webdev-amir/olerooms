<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class RedeemCreditRequest extends Model
{
    use Sluggable;

    protected $table = "redeem_credit_request";

    protected $fillable = ['slug','user_id','status','amount','completed_date','rejected_date','payment_date','transactionid','comments','created_at'];

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
                'method'=>function ($string, $separator) {
                        return md5(microtime());
                    },
                'onUpdate'=>false
            ]
        ];
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'rejected_date',
        'completed_date',
        'payment_date'
    ];

    /**
     * get Record by slug
     *
     * @return array
     */
    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }

    public function user()
    {
         return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getAmountWithCurrencySignAttribute()
    {
        return  numberformatWithCurrency($this->amount);
    }
}
