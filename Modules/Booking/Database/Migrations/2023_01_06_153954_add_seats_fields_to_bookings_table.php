<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeatsFieldsToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // property_billing_data
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('single_ac_seats')->default(0)->after('property_billing_data');
            $table->integer('single_non_ac_seats')->default(0)->after('single_ac_seats');
            $table->integer('double_ac_seats')->default(0)->after('single_non_ac_seats');
            $table->integer('double_non_ac_seats')->default(0)->after('double_ac_seats');
            $table->integer('triple_ac_seats')->default(0)->after('double_non_ac_seats');
            $table->integer('triple_non_ac_seats')->default(0)->after('triple_ac_seats');
            $table->integer('quadruple_ac_seats')->default(0)->after('triple_non_ac_seats');
            $table->integer('quadruple_non_ac_seats')->default(0)->after('quadruple_ac_seats');
            $table->integer('standard_ac_seats')->default(0)->after('quadruple_non_ac_seats');
            $table->integer('standard_non_ac_seats')->default(0)->after('standard_ac_seats');
            $table->integer('deluxe_ac_seats')->default(0)->after('standard_non_ac_seats');
            $table->integer('deluxe_non_ac_seats')->default(0)->after('deluxe_ac_seats');
            $table->integer('suite_ac_seats')->default(0)->after('deluxe_non_ac_seats');
            $table->integer('suite_non_ac_seats')->default(0)->after('suite_ac_seats');
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
