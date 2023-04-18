<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleVisitPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_visit_property', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade'); 

            $table->bigInteger('schedule_visits_id')->unsigned();
            $table->foreign('schedule_visits_id')->references('id')->on('schedule_visits')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->json('property_booking_data')->nullable()->comment('Visit schedule Booking Data in json form');
            
            $table->decimal('amount',10,2)->nullable();
            $table->date('visit_date')->nullable()->comment('Visit date');
            $table->string('visit_time')->nullable()->comment('Visit time');
            $table->dateTime('visit_date_time')->nullable()->comment('Visit date time extra fileds use if we need any');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_visit_property');
    }
}
