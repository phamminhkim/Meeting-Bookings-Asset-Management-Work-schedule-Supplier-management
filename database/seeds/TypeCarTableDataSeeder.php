<?php

use Illuminate\Database\Seeder;
use App\Models\car\TypeCar;
use Illuminate\Support\Facades\DB;
class TypeCarTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $typecar = TypeCar::where('id','1')->get();
		 if(!$typecar || $typecar->count()==0){
           TypeCar::create(['id'=>'1', 'name' => 'NVLC', 'description' => 'NVLC', 'active'=>'1']);
         }
		 $typecar = TypeCar::where('id','2')->get();
		 if(!$typecar || $typecar->count()==0){
           TypeCar::create(['id'=>'2','name' => 'NVLP', 'description' => 'NVLP', 'active'=>'1']);
         }
		 $typecar = TypeCar::where('id','3')->get();
		 if(!$typecar || $typecar->count()==0){
           TypeCar::create(['id'=>'3','name' => 'TP TLG SX', 'description' => 'TP TLG SX', 'active'=>'1']);
         }
		 $typecar = TypeCar::where('id','4')->get();
		 if(!$typecar || $typecar->count()==0){
           TypeCar::create(['id'=>'4','name' => 'OEM', 'description' => 'OEM', 'active'=>'1']);
         }
		  $typecar = TypeCar::where('id','5')->get();
		 if(!$typecar || $typecar->count()==0){
           TypeCar::create(['id'=>'5','name' => 'TP Tồn kho', 'description' => 'TP Tồn kho', 'active'=>'1']);
         }
		  $typecar = TypeCar::where('id','6')->get();
		 if(!$typecar || $typecar->count()==0){
           TypeCar::create(['id'=>'6','name' => 'CXBQ/Khác', 'description' => 'CXBQ/Khác', 'active'=>'1']);
         }
    }
}
