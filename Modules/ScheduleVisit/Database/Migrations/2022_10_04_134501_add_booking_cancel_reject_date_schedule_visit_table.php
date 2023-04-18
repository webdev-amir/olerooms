<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookingCancelRejectDateScheduleVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_visits', function (Blueprint $table) {
            $table->timestamp('schedule_visit_cancelled_date')->nullable()->after('schedule_billing_data');
            $table->timestamp('schedule_visit_cancelled_reject_date')->nullable()->after('schedule_visit_cancelled_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('schedule_visits', function (Blueprint $table) {
        // });
    }
}
