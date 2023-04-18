<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\User;
use Modules\Booking\Entities\Booking;

class Wallet extends Model
{
    use Sluggable, Sortable;

    protected $table = "wallet";

    protected $fillable = ['slug', 'user_id', 'booking_code', 'booking_id', 'type', 'amount', 'status', 'description', 'created_at'];

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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    /**
     * set sortrable by param.
     *
     * @return array
     */
    public $sortable = ['id', 'user_id', 'type', 'amount', 'description', 'created_at'];

    /**
     * get Record by slug
     *
     * @return array
     */
    static public function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id')->withTrashed();
    }

    public function getWalletAmountWithSignAttribute()
    {
        return ($this->type == 'debit') ? env('CURRENCY_SIGN') . "-" . number_format($this->amount, 2) : env('CURRENCY_SIGN') . "+" . number_format($this->amount, 2);
    }
}
