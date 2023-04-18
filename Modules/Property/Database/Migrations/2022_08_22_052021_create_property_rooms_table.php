<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique()->nullable();
            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('is_ac')->default(0);
            $table->boolean('is_non_ac')->default(0);
            $table->integer('ac_total_seats')->default(0);
            $table->integer('ac_rented_seats')->default(0);
            $table->float('ac_amount',10,2)->default(0)->nullable();
            $table->boolean('ac_is_food_included')->default(0);
            $table->integer('non_ac_total_seats')->default(0);
            $table->integer('non_ac_rented_seats')->default(0);
            $table->float('non_ac_amount',10,2)->default(0)->nullable();
            $table->boolean('non_ac_is_food_included')->default(0);
            $table->enum('room_type', ['all','single', 'double', 'triple', 'quadruple','standard', 'deluxe', 'suite'])->default('all');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_rooms');
    }
}
