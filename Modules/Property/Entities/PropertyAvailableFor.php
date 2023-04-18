<?php

namespace Modules\Property\Entities;

use Illuminate\Database\Eloquent\Model;

class PropertyAvailableFor extends Model
{
    protected $table = 'property_available_for';

    protected $fillable = [ 'available_for','property_id'];
    
    public function property()
    {
        return $this->belongsTo("Modules\Property\Entities\Property", "property_id");
    }
}
