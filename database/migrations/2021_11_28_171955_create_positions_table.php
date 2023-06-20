<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('positions', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('company_id',7);
			$table->bigInteger('department_id');
            $table->string('description')->nullable();
			$table->string('active',1);
            $table->timestamps();
        });
		 Schema::table('positions', function (Blueprint $table) {
			 $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign('company_id')->references('id')->on('roles')->onDelete('cascade');
        });
		Schema::dropIfExists('positions');
    }
}
