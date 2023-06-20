<?php

use App\Models\shared\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('modules')->delete();
        $list = Module::where('id', 'PAYMENT')->get();
        if (!$list || $list->count() == 0) {
            Module::create(['id' => 'PAYMENT','name' => 'Thanh toán', 'created_at' => now(), 'updated_at' => now()]);
        }
        $list = Module::where('id', 'REPORT')->get();
        if (!$list || $list->count() == 0) {
            Module::create(['id' => 'REPORT','name' => 'Chứng từ trình duyệt', 'created_at' => now(), 'updated_at' => now()]);
        }
        $list = Module::where('id', 'CARS')->get();
        if (!$list || $list->count() == 0) {
            Module::create(['id' => 'CARS','name' => 'CARS', 'created_at' => now(), 'updated_at' => now()]);
        }
      
         
    }
}
