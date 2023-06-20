<?php

use Illuminate\Database\Seeder;
use App\Models\shared\Wfusertype;
use Illuminate\Support\Facades\DB;

class WFUserTypeTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $wfusertype = Wfusertype::where('name','like','one_user')->get();
		 if(!$wfusertype || $wfusertype->count()==0){
           Wfusertype::create(['id'=>'1','name' => 'one_user', 'description' => 'Nhóm 1 người', 'active'=>'1']);
         }
		$wfusertype = Wfusertype::where('name','like','list_user')->get();
		 if(!$wfusertype || $wfusertype->count()==0){
           Wfusertype::create(['id'=>'2','name' => 'list_user', 'description' => 'Nhóm nhiều người', 'active'=>'1']);
         }
    }
}
