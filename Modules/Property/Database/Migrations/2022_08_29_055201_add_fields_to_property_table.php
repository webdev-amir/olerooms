<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('furnished_type')->nullable()->after('electricity_bill');
            $table->string('bhk_type')->nullable()->after('furnished_type');
            $table->string('floor_no')->nullable()->after('bhk_type');
            $table->string('convenient_time')->nullable()->after('floor_no');
            $table->integer('rooms')->nullable()->after('convenient_time');
            $table->integer('beds')->nullable()->after('rooms');
            $table->integer('guest_capacity')->nullable()->after('beds');
            $table->string('homestay_type')->nullable()->after('guest_capacity');
            $table->boolean('is_homestay_ac')->default(0)->after('homestay_type');
            $table->decimal('starting_amount', 10, 2)->default(0)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
        });
    }
}
