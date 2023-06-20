<?php

use App\Models\shared\Step;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('steps')->delete();

        $list = Step::where('id','1')->get();
        if(!$list || $list->count()==0){
            Step::create(['id' => '1','name' => 'Step 1', 'created_at' => now(), 'updated_at' => now()]);
        }
        $list = Step::where('id','2')->get();
        if(!$list || $list->count()==0){
            Step::create(['id' => '2','name' => 'Step 2', 'created_at' => now(), 'updated_at' => now()]);
        }
        $list = Step::where('id','3')->get();
        if(!$list || $list->count()==0){
            Step::create(['id' => '3','name' => 'Step 3', 'created_at' => now(), 'updated_at' => now()]);
        }        
        $list = Step::where('id','4')->get();
        if(!$list || $list->count()==0){
            Step::create(['id' => '4','name' => 'Step 4', 'created_at' => now(), 'updated_at' => now()]);
        }        
        
        
    }
}
