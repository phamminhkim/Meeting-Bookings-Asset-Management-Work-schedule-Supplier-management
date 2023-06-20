<?php

use App\Models\work\workflow\Wlphase;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Ultils\Ultils;
class UpdateUniqueNameToWlphases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $phases = Wlphase::all();
        foreach ($phases as $phase) {
            $phase->unique_name = Ultils::name_to_unique_name($phase->name);
            $phase->save();
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
