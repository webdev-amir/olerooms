<?php

namespace Modules\Login\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = ['email','user_role','otp'];
    
    protected static function newFactory()
    {
        // return \Modules\Login\Database\factories\OtpVerificationFactory::new();
    }
}
