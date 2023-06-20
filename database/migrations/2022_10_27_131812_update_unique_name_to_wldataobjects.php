<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\work\workflow\Wldataobject;
use App\Ultils\Ultils;

class UpdateUniqueNameToWldataobjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dataobjects = Wldataobject::all();
        foreach ($dataobjects as $dataobject) {
            $dataobject->unique_name = Ultils::name_to_unique_name($dataobject->name);
            $dataobject->save();
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
