<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChancelRequestDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_visits', function (Blueprint $table) {
            $table->timestamp('cancel_request_date')->nullable()->after('schedule_billing_data');
            $table->text('cancellation_reason')->nullable()->after('cancel_request_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_visits', function (Blueprint $table) {

        });
    }
}
