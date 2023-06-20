<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('content');
            $table->string('note')->nullable();
            $table->integer('service_category_id');
            $table->date('request_date');
            $table->date('finish_date')->nullable();
            $table->string('company_id', 4);
            $table->bigInteger('project_id')->unsigned()->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->bigInteger('create_by')->unsigned();
            $table->bigInteger('request_by')->unsigned();
            $table->bigInteger('team_id')->unsigned();
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
        Schema::dropIfExists('tickets');
    }
}
