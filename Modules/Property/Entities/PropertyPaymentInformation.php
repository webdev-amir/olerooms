<?php

namespace Modules\Property\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyPaymentInformation extends Model
{
    use HasFactory;
    protected $table = "property_payment_information";

    protected $fillable = [
        'property_id',
        'payment_type',
        'holder_name',
        'bank_name',
        'ifsc_code',
        'account_number',
        'cancelled_check_photo',
        'passbook_front_photo',
        'upi_id',
        'upi_qr_code_image'
    ];


    public function property()
    {
        return $this->belongsTo("Modules\Property\Entities\Property", "property_id");
    }


    public function getQRCodeAttribute()
    {

        return ($this->upi_qr_code_image) ? $this->upi_qr_code_image : url('/') . '/images/no-image.jpg';
    }

    public function getQRCodeThumbnailAttribute()
    {
        return \Storage::disk('s3')->url('property/' .$this->property->user_id . '/' . $this->upi_qr_code_image);

        $image = \URL::to('storage/app/public/property/' . auth()->id() . '/' . $this->upi_qr_code_image);
        if (\Storage::exists('/public/property/' . auth()->id() . '/' . $this->upi_qr_code_image)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;

    }

    public function getCancleCheckPhotoThumbnailAttribute()
    {
        return \Storage::disk('s3')->url('property/' .$this->property->user_id . '/' . $this->passbook_front_photo);

        $image = \URL::to('storage/app/public/property/' . auth()->id() . '/' . $this->passbook_front_photo);
        if (\Storage::exists('/public/property/' . auth()->id() . '/' . $this->passbook_front_photo)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }

    public function getPassbookFrontPhotoThumbnailAttribute()
    {
        return \Storage::disk('s3')->url('property/' .$this->property->user_id . '/' . $this->cancelled_check_photo);

        $image = \URL::to('storage/app/public/property/' . auth()->id() . '/' . $this->cancelled_check_photo);
        if (\Storage::exists('/public/property/' . auth()->id() . '/' . $this->cancelled_check_photo)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }

        return $filename;
    }
}
