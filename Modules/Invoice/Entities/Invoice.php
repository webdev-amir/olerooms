<?php

namespace Modules\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = "booking_invoices";

    protected $fillable = ['booking_id','filename','report_sharing_date','created_at'];
}
