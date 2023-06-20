<?php

use Illuminate\Database\Seeder;
use App\Models\shared\Wfapptype;
use Illuminate\Support\Facades\DB;
class WFAppTypeTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wfapptype = Wfapptype::where('id','1')->get();
		 if(!$wfapptype || $wfapptype->count()==0){
           Wfapptype::create(['id' => '1','name' => 'Người tạo phiếu', 'description' => 'Người tạo phiếu', 'active'=>'1']);
         }
		$wfapptype = Wfapptype::where('id','2')->get();
		 if(!$wfapptype || $wfapptype->count()==0){
           Wfapptype::create(['id' => '2','name' => 'Người xác nhận', 'description' => 'Người xác nhận', 'active'=>'1']);
         }
		$wfapptype = Wfapptype::where('id','3')->get();
		 if(!$wfapptype || $wfapptype->count()==0){
           Wfapptype::create(['id' => '3','name' => 'Người xác nhận nguyên nhân', 'description' => 'Người xác nhận nguyên nhân', 'active'=>'1']);
         }
		$wfapptype = Wfapptype::where('id','4')->get();
		 if(!$wfapptype || $wfapptype->count()==0){
           Wfapptype::create(['id' => '4','name' => 'Người xác định', 'description' => 'Người xác định', 'active'=>'1']);
         }
		$wfapptype = Wfapptype::where('id','5')->get();
		 if(!$wfapptype || $wfapptype->count()==0){
           Wfapptype::create(['id' => '5','name' => 'Người xem xét', 'description' => 'Người xem xét', 'active'=>'1']);
         }
		$wfapptype = Wfapptype::where('id','6')->get();
		 if(!$wfapptype || $wfapptype->count()==0){
           Wfapptype::create(['id' => '6','name' => 'Người ký duyệt', 'description' => 'Người ký duyệt', 'active'=>'1']);
         }
		$wfapptype = Wfapptype::where('id','7')->get();
		 if(!$wfapptype || $wfapptype->count()==0){
           Wfapptype::create(['id' => '7','name' => 'Người phê duyệt', 'description' => 'Người phê duyệt', 'active'=>'1']);
         }
		$wfapptype = Wfapptype::where('id','8')->get();
		 if(!$wfapptype || $wfapptype->count()==0){
           Wfapptype::create(['id' => '8','name' => 'Nhân viên QA', 'description' => 'Nhân viên QA', 'active'=>'1']);
         }
    }
}
