<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class UserBankDetails extends Model
{
    protected $table = "user_bank_details";

    protected $fillable = [
        'user_id',
        'payment_type',
        'holder_name',
        'bank_name',
        'ifsc_code',
        'account_number',
        'upi_id',
        'upi_qr_code_image',
        'pan_card_number',
        'pan_card_image',
        'cancelled_cheque_image',
        'gstin_number'
    ];

    public function user()
    {
        return $this->belongsTo("App\Models\User", "user_id");
    }

    public function getQRCodeAttribute()
    {
        return ($this->upi_qr_code_image) ? $this->upi_qr_code_image : url('/') . '/images/no-image.jpg';
    }

    public function getQRCodeThumbnailAttribute()
    {
        return \Storage::disk('s3')->url('users/bankdetails/' . $this->user_id . '/' . $this->upi_qr_code_image);
    }


    public function getPanCardThumbnailAttribute()
    {
        return \Storage::disk('s3')->url('users/bankdetails/' . $this->user_id . '/' . $this->pan_card_image);
    }

    public function getCancelledChequeThumbnailAttribute()
    {
        return \Storage::disk('s3')->url('users/bankdetails/' . $this->user_id . '/' . $this->cancelled_cheque_image);
    }
}
