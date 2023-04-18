<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsInPropertiesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('carpet_area')->nullable()->after('video_url')->comment('In sq ft ');
            $table->string('kitchen_modular')->nullable()->after('carpet_area')->comment('moudular yes or no');
            $table->string('parking_space_avail')->nullable()->after('kitchen_modular')->comment('parking  space - avl or not');
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
