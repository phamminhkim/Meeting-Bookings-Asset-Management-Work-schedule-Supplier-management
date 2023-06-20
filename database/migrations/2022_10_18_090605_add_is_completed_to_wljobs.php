<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\work\workflow\Wljob;

class AddIsCompletedToWljobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn("wljobs","is_completed")) {
            Schema::table('wljobs', function (Blueprint $table) {
                $table->tinyInteger('is_completed')->nullable(false)->default(0);
            });
        }
      

        $completed_jobs = Wljob::whereNotNull('date_finished')->get();
        foreach ($completed_jobs as $job) {
            $job->is_completed = 1;
            $job->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn("wljobs","is_completed")) {
            Schema::table('wljobs', function (Blueprint $table) {
                $table->dropColumn('is_completed');
            });
        }
       
    }
}
