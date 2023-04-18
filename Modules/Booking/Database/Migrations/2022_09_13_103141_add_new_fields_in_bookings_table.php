<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsInBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('booking_confirmed_date')->nullable()->after('status');
            $table->timestamp('booking_reject_date')->nullable()->after('booking_confirmed_date');
            $table->timestamp('booking_cancelled_date')->nullable()->after('booking_reject_date');
            $table->timestamp('booking_completed_date')->nullable()->after('booking_cancelled_date');
            $table->enum('booking_reject_confirm_type', ['auto', 'manual'])->nullable()->after('booking_completed_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('bookings', function (Blueprint $table) {

        // });
    }
}
