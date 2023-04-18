<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            //propery details
            $table->bigInteger('user_id')->unsigned();
            $table->string('property_code')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('property_type_id')->unsigned();
            $table->foreign('property_type_id')->references('id')->on('property_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->string('map_location')->nullable();
            $table->string('lat', 30)->nullable();
            $table->string('long', 30)->nullable();
            $table->text('full_address')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('security_deposit_amount', 12, 2)->nullable();
            //Property Information
            $table->string('property_name')->nullable();
            $table->text('property_description')->nullable();
            $table->text('available_for')->nullable();
            //Property Inventory
            $table->integer('total_seats')->nullable();
            $table->integer('rented_seats')->nullable();
            $table->integer('total_floors')->nullable();
            $table->string('electricity_bill')->nullable();

            $table->json('amenities_ids')->nullable()->comment('All aminities ids array in json form');

            $table->string('cover_image')->nullable();
            $table->string('video')->nullable();
            //Extra Info
            $table->enum('status', ['pending', 'publish', 'reject'])->default('pending')->nullable();
            $table->boolean('is_publish')->default(1);
            $table->bigInteger('update_user')->nullable();
            $table->boolean('featured_property')->default(0);
            $table->boolean('deal_of_tahe_day')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('property_amenities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('amenitiy_id')->unsigned();
            $table->foreign('amenitiy_id')->references('id')->on('amenities')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('property_payment_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->enum('payment_type', ['upi', 'cheque'])->default('upi');
            $table->string('holder_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('cancelled_check_photo')->nullable();
            $table->string('passbook_front_photo')->nullable();
            $table->string('upi_id')->nullable();
            $table->string('upi_qr_code_image')->nullable();
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
        Schema::dropIfExists('properties');
        Schema::dropIfExists('property_amenities');
    }
}
