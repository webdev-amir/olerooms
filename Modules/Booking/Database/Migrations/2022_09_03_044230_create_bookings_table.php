<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('code', 64)->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('vendor_id')->nullable();
            $table->integer('payment_id')->nullable();

            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('property_room_id')->nullable()->unsigned();
            /*$table->foreign('property_room_id')->references('id')->on('property_rooms')
                ->onUpdate('cascade')->onDelete('cascade');*/
            $table->dateTime('check_in_date')->nullable()->comment('Property booking checkin date');
            $table->dateTime('check_out_date')->nullable()->comment('Property booking checkout date');
            $table->json('property_booking_data')->nullable()->comment('Property Booking Data in json form');
            $table->json('property_billing_data')->nullable()->comment('Property Billing Data in json form');

            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable()->comment('Total amount is final amount after add reduce all tax and charges');
            $table->string('status', 30)->nullable();

            $table->decimal('commission', 10, 2)->default(0)->nullable();
            $table->string('commission_type', 150)->nullable();

            $table->string('email', 255)->nullable();
            $table->string('name', 255)->nullable();
            // $table->string('last_name',255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('address2', 255)->nullable();
            $table->string('address_lat')->nullable();
            $table->string('address_long')->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('zip_code', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('cancellation_reason')->nullable();

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
        Schema::dropIfExists('bookings');
    }
}
