<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reminderable_id')->unsigned();
            $table->string('reminderable_type');
            $table->bigInteger('user_id')->unsigned();
            $table->string('url')->nullable();
            $table->string('content');
            $table->integer('type')->default(1);
            $table->integer('duration')->nullable();
            $table->dateTime('date_due');
            $table->dateTime('start_date')->nullable();
            $table->integer('duration_value')->nullable();
            $table->integer('reminded_before_day')->nullable();
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
        Schema::dropIfExists('reminders');
    }
}
