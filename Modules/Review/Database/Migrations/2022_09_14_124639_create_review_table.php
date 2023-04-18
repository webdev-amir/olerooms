<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('object_id')->comment('eg. Property Id')->nullable();
           
            $table->string('object_model', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();
            $table->text('reply_content')->nullable();
            $table->integer('rate_number')->nullable();
            $table->string('author_ip', 100)->nullable();
            $table->string('status', 50)->nullable();
            $table->dateTime('publish_date')->nullable();
            $table->bigInteger('user_id')->comment('eg. Customer Id')->unsigned()->nullable();
            $table->bigInteger('update_user')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('lang', 10)->nullable();
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
        Schema::dropIfExists('review');
    }
}
