<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('schedule_code')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('vendor_id')->nullable();
            $table->integer('payment_id')->nullable();

            $table->json('schedule_booking_data')->nullable()->comment('Visit schedule Booking Data in json form');
            $table->json('schedule_billing_data')->nullable()->comment('Visit schedule Billing Data in json form');

            $table->decimal('amount',10,2)->nullable();
            $table->decimal('total',10,2)->nullable();
            $table->string('status',30)->nullable();

            $table->decimal('commission',10,2)->default(0)->nullable();
            $table->string('commission_type',150)->nullable();
           
            $table->bigInteger('update_user')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('schedule_visits');
    }
}
