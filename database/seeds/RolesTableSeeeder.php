<?php

use App\Models\shared\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Role::truncate();
        // DB::table('roles')->delete();
        $list = Role::where('name','admin')->get();
        if(!$list || $list->count()==0){
            Role::create(['name' => 'admin','description'=>'admin','active'=>'1']);
          }
        $list = Role::where('name','user')->get();
        if(!$list || $list->count()==0){
            Role::create(['name' => 'user','description'=>'user','active'=>'1']);
        }
        $list = Role::where('name','leader')->get();
        if(!$list || $list->count()==0){
            Role::create(['name' => 'leader','description'=>'leader','active'=>'1']);
        }
		
		//Phieu car
		$list = Role::where('name','Car_creator')->get();
        if(!$list || $list->count()==0){
            Role::create(['name' => 'Car_creator','description'=>'Xem, tạo xóa, sửa phiếu car','active'=>'1']);
        }
	    $list = Role::where('name','Car_view')->get();
        if(!$list || $list->count()==0){
            Role::create(['name' => 'Car_view','description'=>'Xem phiếu car theo người tạo hoặc người duyệt','active'=>'1']);
        }
        $list = Role::where('name','Car_update')->get();
        if(!$list || $list->count()==0){
            Role::create(['name' => 'Car_update','description'=>'Cập nhật lại','active'=>'1']);
        }
      
    }
}
