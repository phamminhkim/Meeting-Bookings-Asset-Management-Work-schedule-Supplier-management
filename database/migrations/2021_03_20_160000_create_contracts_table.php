<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_num',50);
            $table->date('sign_date');
            $table->string('a_signer',50);
            $table->string('a_position',50);
            $table->string('b_signer',50);
            $table->string('b_position',50);
            $table->bigInteger('vendor_id')->unsigned();
            $table->date('date_begin');
            $table->date('date_end')->nullable();
            $table->string('title',120);
            $table->text('content');
            $table->float('amount',18);
            $table->float('amount_paid',18)->nullable();
            $table->integer('payment_status')->nullable();
            $table->bigInteger('bugget_id')->nullable();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('department_id')->unsigned();
            $table->timestamps();
            $table->string('econtract_id',14)->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->softDeletes();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
