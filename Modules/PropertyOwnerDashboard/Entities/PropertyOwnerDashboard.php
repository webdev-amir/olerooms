<?php

namespace Modules\PropertyOwnerDashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyOwnerDashboard extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        // return \Modules\PropertyOwnerDashboard\Database\factories\PropertyOwnerDashboardFactory::new();
    }
    
}
