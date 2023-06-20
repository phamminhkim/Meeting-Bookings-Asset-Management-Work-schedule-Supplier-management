<?php

use App\Models\shared\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('companies')->delete();
        $list = Company::where('id', '1000')->get();
        if (!$list || $list->count() == 0) {
            Company::create(['id' => '1000', 'name' => 'TLG', 'active' => 1]);
        }
        $list = Company::where('id', '2000')->get();
        if (!$list || $list->count() == 0) {
            Company::create(['id' => '2000', 'name' => 'TLLT', 'active' => 1]);
        }
        $list = Company::where('id', '3000')->get();
        if (!$list || $list->count() == 0) {
            Company::create(['id' => '3000', 'name' => 'TLHC', 'active' => 1]);
        }

        $list = Company::where('id', '6000')->get();
        if (!$list || $list->count() == 0) {
            Company::create(['id' => '6000', 'name' => 'TLMN', 'active' => 1]);
        }
        $list = Company::where('id', '6100')->get();
        if (!$list || $list->count() == 0) {
            Company::create(['id' => '6100', 'name' => 'TLMB', 'active' => 1]);
        }
        $list = Company::where('id', '6200')->get();
        if (!$list || $list->count() == 0) {
            Company::create(['id' => '6200', 'name' => 'TLMT', 'active' => 1]);
        }

        $list = Company::where('id', '5000')->get();
        if (!$list || $list->count() == 0) {
            Company::create(['id' => '5000', 'name' => 'NTL', 'active' => 1]);
        }
     
       
    }
}
