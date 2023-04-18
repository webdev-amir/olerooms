<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Property\Entities\Property;

class UserWishlist extends Model
{
    protected $table = 'user_wishlist';
    protected $fillable = [
        'object_id',
        'object_model',
        'user_id'
    ];

    public function property()
    {
        return $this->hasOne(Property::class, "id", 'object_id')->withTrashed();
    }
}
