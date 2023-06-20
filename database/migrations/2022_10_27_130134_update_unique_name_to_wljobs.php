<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\work\workflow\Wljob;
use App\Ultils\Ultils;

class UpdateUniqueNameToWljobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $jobs = Wljob::all();
        foreach ($jobs as $job) {
            $job->unique_name = Ultils::name_to_unique_name($job->name);
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
    }
}
