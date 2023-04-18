<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('booking_payment_type', ['partial', 'full'])->default('full')->after('total');
            $table->decimal('final_offer_amount', 10, 2)->default(0)->after('booking_payment_type');
            $table->decimal('remaining_payable_amount', 10, 2)->default(0)->after('final_offer_amount');
            $table->enum('is_remaining_amount_paid', ['yes', 'no'])->nullable()->after('remaining_payable_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
        });
    }
}
