<?php

use Illuminate\Database\Seeder;
use App\Models\car\Standard;
use Illuminate\Support\Facades\DB;
class StandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $standard = Standard::where('id','1')->get();
		 if(!$standard || $standard->count()==0){
           Standard::create(['id'=>'1', 'name' => 'ISO9001', 'description' => 'ISO9001', 'active'=>'1']);
         }
		$standard = Standard::where('id','2')->get();
		 if(!$standard || $standard->count()==0){
           Standard::create(['id'=>'2','name' => 'SA8000', 'description' => 'SA8000', 'active'=>'1']);
         }
		$standard = Standard::where('id','3')->get();
		 if(!$standard || $standard->count()==0){
           Standard::create(['id'=>'3','name' => 'ISO14001', 'description' => 'ISO14001', 'active'=>'1']);
         }
		$standard = Standard::where('id','4')->get();
		 if(!$standard || $standard->count()==0){
           Standard::create(['id'=>'4','name' => 'Khác', 'description' => 'Khác', 'active'=>'1']);
         }
    }
}
