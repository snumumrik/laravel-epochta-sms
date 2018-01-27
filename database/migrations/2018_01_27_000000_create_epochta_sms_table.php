<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEpochtaSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epochta_sms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender');
            $table->string('phone');
            $table->string('body')->nullable();
            $table->string('datetime')->nullable();
            $table->tinyInteger('lifetime')->nullable();

            $table->integer('sms_id')->nullable(); // ид смс на сервисе
            $table->integer('resend_sms_id')->nullable(); // ид смс на сервисе, которая повторно была отправлена для текущей

            $table->tinyInteger('sms_sent_status')->nullable(); // состояние отправки смс
            $table->tinyInteger('sms_delivered_status')->nullable(); // состояние доставки смс
            $table->tinyInteger('dispatch_status')->nullable(); // состояние рассылки

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
        Schema::drop('epochta_sms');
    }
}
