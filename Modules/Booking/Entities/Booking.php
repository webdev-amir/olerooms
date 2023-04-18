<?php

namespace Modules\Booking\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Property\Entities\Property;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Modules\Payment\Entities\Payment;
use Modules\Review\Entities\Review;

class Booking extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;
    use SoftDeletes;
    protected $table = 'bookings';



    protected $fillable = ['slug', 'code', 'user_id',  'vendor_id', 'payment_id', 'property_id', 'property_room_id', 'check_in_date', 'check_out_date', 'property_booking_data', 'property_billing_data', 'amount', 'total', 'status', 'commission', 'commission_type', 'email', 'name', 'phone', 'address', 'address2', 'address_lat', 'address_long', 'city', 'state', 'zip_code', 'country', 'customer_notes', 'update_user', 'booking_reject_confirm_type', 'custom_chekout_date', 'booking_cancelled_reject_date', 'booking_payment_type', 'final_offer_amount', 'remaining_payable_amount', 'is_remaining_amount_paid', 'agent_corp_code', 'booked_by', 'code_type', 'single_ac_seats', 'single_non_ac_seats', 'double_ac_seats', 'double_non_ac_seats', 'triple_ac_seats', 'triple_non_ac_seats', 'quadruple_ac_seats', 'quadruple_non_ac_seats', 'standard_ac_seats', 'standard_non_ac_seats', 'deluxe_ac_seats', 'deluxe_non_ac_seats', 'suite_ac_seats', 'suite_non_ac_seats'];


    const INPROGRESS = 'in-progress'; // New booking reserve request, before payment processing
    const PENDING    = 'pending'; // New booking, After payment need to confirm by vendor or suto system
    const REJECTED   = 'rejected'; // Reject booking by vendor/auto by system
    const CONFIRMED  = 'confirmed'; // after processing -> confirmed (advancec payment)
    const COMPLETED  = 'completed'; //
    const CANCELLED  = 'cancelled';
    const EXPIRED    = 'expired'; //if not accept booking or not paid advanced payment then auto expired booking request

    const AUTOCONFIRMED = 'autoconfirmed'; //booking auto confirmed by system 
    const AUTOREJECTED  = 'autorejected'; //booking auto reject by system 

    const notAcceptedStatus = [
        'in-progress'
    ];

    const notAcceptedStatusInEarning = [
        'in-progress', 'pending', 'cancelled', 'rejected', 'expired'
    ];

    protected $dates = ['check_in_date', 'check_out_date', 'booking_confirmed_date', 'booking_reject_date', 'booking_cancelled_date', 'booking_completed_date'];
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

    public function getStatusNameAttribute()
    {
        return booking_status_to_text($this->status);
    }

    public function getStatusClassAttribute()
    {
        switch ($this->status) {
            case "pending":
                return "primary";
                break;
            case "completed":
                return "success";
                break;
            case "confirmed":
                return "info";
                break;
            case "cancelled":
                return "danger";
                break;
            case "paid":
                return "info";
                break;
            case "expired":
                return "danger";
                break;
            case "rejected":
                return "danger";
                break;
        }
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'booking_id');
    }



    public function customer()
    {
        return $this->belongsTo("App\Models\User", "user_id", "id")->withTrashed();
    }

    public function author()
    {
        return $this->belongsTo("App\Models\User", "user_id", "id")->withDefault();
    }

    public function getUserFullNameAttribute()
    {
        return ucfirst($this->first_name . ' ' . $this->last_name);
    }

    public function getBookingJsonDataAttribute()
    {
        return json_decode($this->property_booking_data, true);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id');
    }

    public function paymentCaptured()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id')->where('status', 'captured');
    }



    public function getBookingPaymentSuccessDetailUrl()
    {
        return route('booking.payment.success', ['slug' => $this->slug]);
    }

    public function getBookingPaymentSuccessDetailUrlCompany()
    {
        return route('company.booking.payment.success', ['slug' => $this->slug]);
    }



    public function generateCode()
    {
        return md5(uniqid() . rand(0, 99999));
    }

    public function save(array $options = [])
    {
        if (empty($this->code))
            $this->code = str_pad('OLE' . $this->id, 4, '0', STR_PAD_LEFT);
        return parent::save($options); // TODO: Change the autogenerated stub
    }

    /**
     * Get Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne("App\Models\User", "id", 'user_id')->withTrashed();
    }

    public function vendor()
    {
        return $this->hasOne("App\Models\User", "id", 'vendor_id')->withTrashed();
    }

    public static function getRecentBookings($limit = 10)
    {
        $q = parent::where('status', '!=', 'draft');
        return $q->orderBy('id', 'desc')->limit($limit)->get();
    }

    public static function getBookingHistory($booking_status = false, $customer_id = false)
    {
        $list_booking = parent::query()->orderBy('id', 'desc');
        if (!empty($booking_status)) {
            $list_booking->where("status", $booking_status);
        }
        if (!empty($customer_id)) {
            $list_booking->where("user_id", $customer_id);
        }

        $list_booking->where('status', '!=', 'draft');
        return $list_booking->paginate(10);
    }

    public function getIsAbleToCancelVendorAttribute()
    {
        if ($this->start_date < now() || $this->status == 'cancelled' || $this->status == 'expired') {
            return false;
        }
        return true;
    }

    public function getIsAbleToCancelBookingCustomerAttribute()
    {
        if ($this->status == 'cancelled' || $this->status == 'expired' || $this->status == 'completed' || $this->end_date < now()) {
            return false;
        }
        return true;
    }

    public function getCancellationStatusAttribute()
    {
        $status = $this->booking_cancelled_reject_date ? 'rejected' : ($this->booking_cancelled_date ? 'accepted' : 'N/A');
        return $status;
    }

    public function getCancellationStatusDateAttribute()
    {
        $status = $this->booking_cancelled_reject_date ? date('d M Y, g:i a', strtotime($this->booking_cancelled_reject_date)) : ($this->booking_cancelled_date ? date('d M Y, g:i a', strtotime($this->booking_cancelled_date))  : 'N/A');
        return $status;
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id')->withTrashed();
    }


    public function getCancellationBeforeDateAttribute()
    {
        if ($this->BookingRemainingHours > setting_item('booking-cancelled-before-time') && $this->check_in_date > now()) {
            return true;
        }
        return false;
    }

    public function getBookingRemainingHoursAttribute()
    {
        $start =   strtotime(\Carbon\Carbon::today());
        $end   =   strtotime($this->check_in_date);
        $diff = $end - $start;
        $hours = $diff / (60 * 60);
        return $hours;
    }

    public function getFullPaymentAmountSumVendorAttribute()
    {
        return $this->where('vendor_id', auth()->user()->id)->whereIn('status', ['confirmed', 'completed'])->where('booking_payment_type', 'full')->sum('total');
    }

    public function getPartialPaymentAmountSumVendorAttribute()
    {
        return $this->where('vendor_id', auth()->user()->id)->whereIn('status', ['confirmed', 'completed'])->where('booking_payment_type', 'partial')->sum('remaining_payable_amount');
    }


    public function getCommissionPaymentAmountSumVendorAttribute()
    {
        return $this->where('vendor_id', auth()->user()->id)->whereIn('status', ['confirmed', 'completed'])->where('booking_payment_type', 'full')->sum('commission');
    }


    public function getAllBookingsSumVendorAttribute()
    {
        return $this->where('vendor_id', auth()->user()->id)->whereIn('status', ['confirmed', 'completed'])->count();
    }


    public function getAdminCommissionEarningsSumAttribute()
    {
        return  $this->whereIn('status', ['confirmed', 'completed'])->where('booking_payment_type', 'full')->sum('commission');
    }
}
