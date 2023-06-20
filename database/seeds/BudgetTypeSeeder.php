<?php

use App\Models\shared\BudgetType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('budget_types')->delete();
        $list = BudgetType::where('id', '1')->get();
        if (!$list || $list->count() == 0) {
            BudgetType::create(['id' => '1','name' => 'Trong ngân sách', 'created_at' => now(), 'updated_at' => now()]);
        }
        $list = BudgetType::where('id', '0')->get();
        if (!$list || $list->count() == 0) {
            BudgetType::create(['id' => '0','name' => 'Ngoài/Vượt ngân sách', 'created_at' => now(), 'updated_at' => now()]);
        }
        // $list = BudgetType::where('id', '2')->get();
        // if (!$list || $list->count() == 0) {
        //     BudgetType::create(['id' => '2','name' => 'Vượt ngân sách', 'created_at' => now(), 'updated_at' => now()]);
        // }
        $list = BudgetType::where('id', '-1')->get();
        if (!$list || $list->count() == 0) {
            BudgetType::create(['id' => '-1','name' => 'Không thuộc ngân sách', 'created_at' => now(), 'updated_at' => now()]);
        }
        
    }
}
