<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCategoryTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('service_category_ticket',function(Blueprint $table){
            $table->bigInteger('service_category_id')->unsigned();
            $table->bigInteger('ticket_id')->unsigned();
        });
        Schema::table('service_category_ticket', function (Blueprint $table) {

            $table->foreign('service_category_id')->references('id')->on('service_categories')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_category_ticket', function (Blueprint $table) {
            $table->dropForeign('service_category_ticket_service_category_id_foreign')->references('id')->on('service_categories')->onDelete('cascade');
            $table->dropForeign('service_category_ticket_ticket_id_foreign')->references('id')->on('tickets')->onDelete('cascade');
        });

        Schema::dropIfExists('service_category_ticket');
    }
}
