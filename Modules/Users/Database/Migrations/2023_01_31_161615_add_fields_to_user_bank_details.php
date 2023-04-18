<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUserBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bank_details', function (Blueprint $table) {
            $table->string('pan_card_number')->nullable();
            $table->string('pan_card_image')->nullable();
            $table->string('cancelled_cheque_image')->nullable();
            $table->string('gstin_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bank_details', function (Blueprint $table) {

        });
    }
}
