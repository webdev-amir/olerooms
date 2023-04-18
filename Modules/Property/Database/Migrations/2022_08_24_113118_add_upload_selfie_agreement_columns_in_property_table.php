<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUploadSelfieAgreementColumnsInPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('upload_selfie')->nullable()->after('deal_of_the_day');
            $table->enum('status_selfie', ['pending', 'approved', 'rejected'])->nullable()->after('upload_selfie');
            $table->timestamp('status_selfie_date')->nullable()->after('status_selfie');
            $table->string('upload_agreement')->nullable()->after('status_selfie_date');
            $table->enum('status_agreement', ['pending', 'approved', 'rejected'])->nullable()->after('upload_agreement');
            $table->timestamp('status_agreement_date')->nullable()->after('status_agreement');
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
             $table->dropColumn('upload_selfie');
             $table->dropColumn('upload_agreement');
             $table->dropColumn('status_selfie');
             $table->dropColumn('status_agreement');
        });
    }
}
