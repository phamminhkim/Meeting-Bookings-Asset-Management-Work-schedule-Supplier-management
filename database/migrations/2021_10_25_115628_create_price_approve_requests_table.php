<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceApproveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_approve_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title',100);
            $table->integer('status')->nullable();
            $table->date('effective_date')->nullable();
            $table->bigInteger('proposer')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('team_id')->unsigned();
            $table->string('company_id',4);
            $table->bigInteger('department_id')->unsigned();
            $table->timestamp('send_date')->nullable();
            $table->bigInteger('document_type_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->string('serial_num',14)->nullable();
            $table->string('currency',3)->nullable();
            $table->bigInteger('vendor_id')->unsigned();
            $table->integer('locked')->default(0);
            $table->string('purchase_note')->nullable();
            $table->string('material_type_name',100)->nullable();
            $table->string('method_payment_name',50)->nullable();
            $table->string('contract_num',50)->nullable();
            $table->string('diff_info')->nullable();
            $table->integer('is_order')->nullable();
            $table->string('another_idea')->nullable();
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
        Schema::dropIfExists('price_approve_requests');
    }
}
