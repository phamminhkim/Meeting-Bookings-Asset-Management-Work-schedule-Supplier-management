<?php

use App\Models\shared\Sloc;
use App\Models\shared\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlocTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = Sloc::where('id','6040')->get();
        if(!$list || $list->count()==0){
            Sloc::create(['id' => '6040', 'description' => 'Trường Sơn', 'name' => 'Trường Sơn', 'active' => 1]);
        }
        $list = Sloc::where('id','6041')->get();
        if (!$list || $list->count()==0) {
            Sloc::create(['id' => '6041', 'description' => 'B2B Trường Học', 'name' => 'B2B Trường Học', 'active' => 1]);
        }
        $list = Sloc::where('id','6043')->get();
        if (!$list || $list->count()==0) {
            Sloc::create(['id' => '6043', 'description' => 'TMĐT', 'name' => 'TMĐT', 'active' => 1]);
        }
       
    }
}
