<?php

use App\Models\shared\Permission;
use App\Models\shared\Role;
use Illuminate\Database\Seeder;

class HrAddMarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = Permission::where('name','like','%_hraddmark')->get();
      //  dd($list );
        if(!$list || $list->count()==0){
            Permission::create(['name' => 'create_hraddmark', 'description' => 'Tạo cộng/trừ điểm năng suất ']);
            Permission::create(['name' => 'review_hraddmark', 'description' => 'Xem cộng/trừ điểm năng suất']);
            Permission::create(['name' => 'update_hraddmark', 'description' => 'Sửa cộng/trừ điểm năng suất']);
            Permission::create(['name' => 'delete_hraddmark', 'description' => 'Xoá cộng/trừ điểm năng suất']);
            
        }
    }
}
