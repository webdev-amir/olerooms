<?php

namespace Modules\Property\Entities;

use Illuminate\Database\Eloquent\Model;

class PropertyOffers extends Model
{

    protected $fillable = ['slug', 'property_id', 'coupon_id', 'created_at'];

    public function coupon()
    {
        return $this->belongsTo('Modules\Coupon\Entities\Coupon', 'coupon_id')->where('is_global_apply', 0)->where([['end_date', '>=', now()], ['start_date', '<=', now()]]);;
    }

    public function getIsGlobalApplyStatusAttribute()
    {
        if ($this->coupon && $this->coupon->is_global_apply) {
            return true;
        }
        return false;
    }
}
