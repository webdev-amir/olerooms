<?php

namespace Modules\Property\Entities;

use Illuminate\Database\Eloquent\Model;

class PropertyAmenities extends Model
{
    protected $table = 'property_amenities';

    protected $fillable = [ 'amenitiy_id','property_id'];
    
    public function amenities()
    {
        return $this->belongsTo("Modules\Amenities\Entities\Amenities", "amenitiy_id");
    }
    
    public function property()
    {
        return $this->belongsTo("Modules\Property\Entities\Property", "property_id");
    }
}
