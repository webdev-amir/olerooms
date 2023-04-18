<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->enum('type',['Booking','ScheduleVisit'])->default('Booking')->nullable();
            $table->integer('type_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('payment_gateway',50)->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->decimal('fee',10,2)->nullable();
            $table->decimal('tax',10,2)->nullable();
            $table->string('currency',10)->nullable();
            $table->string('method',50)->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();

            $table->string('amount_refunded')->nullable();
            $table->string('bank')->nullable();
            $table->string('wallet')->nullable();
            $table->string('entity')->nullable();
            $table->string('refund_Date')->nullable();
            $table->string('bank_transaction_id')->nullable();
            $table->string('refund_id')->nullable();
            
            $table->string('status',30)->nullable();
            $table->string('ip_address')->nullable();
            $table->json('logs')->nullable();

            $table->integer('create_user')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
