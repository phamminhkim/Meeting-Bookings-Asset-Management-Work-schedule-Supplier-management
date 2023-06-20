<?php

use Illuminate\Database\Seeder;
use App\Models\shared\CarError;
use Illuminate\Support\Facades\DB;
class CarErrorTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$carerror = CarError::where('id','1')->get();
		 if(!$carerror || $carerror->count()==0){
           CarError::create(['id'=>'1', 'name' => 'Lỗi NC nặng', 'description' => 'QA', 'active'=>'1']);
         }
		 $carerror = CarError::where('id','2')->get();
		 if(!$carerror || $carerror->count()==0){
           CarError::create(['id'=>'2','name' => 'Lỗi nghiêm trọng', 'description' => 'QC', 'active'=>'1']);
         }
		 $carerror = CarError::where('id','3')->get();
		 if(!$carerror || $carerror->count()==0){
           CarError::create(['id'=>'3','name' => 'Lỗi nặng', 'description' => 'QC', 'active'=>'1']);
         }
		 $carerror = CarError::where('id','4')->get();
		 if(!$carerror || $carerror->count()==0){
           CarError::create(['id'=>'4','name' => 'Lỗi nhẹ', 'description' => 'QA', 'active'=>'1']);
         }
		  $carerror = CarError::where('id','5')->get();
		 if(!$carerror || $carerror->count()==0){
           CarError::create(['id'=>'5','name' => 'Lỗi nghiêm trọng', 'description' => 'QA', 'active'=>'1']);
         }
		  $carerror = CarError::where('id','6')->get();
		 if(!$carerror || $carerror->count()==0){
           CarError::create(['id'=>'6','name' => 'Lỗi NC nhẹ', 'description' => 'QA', 'active'=>'1']);
         }
		   $carerror = CarError::where('id','7')->get();
		 if(!$carerror || $carerror->count()==0){
           CarError::create(['id'=>'7','name' => 'Lỗi nhẹ', 'description' => 'QC', 'active'=>'1']);
         }
    }
}
