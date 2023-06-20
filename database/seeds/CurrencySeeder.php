<?php

use App\Models\shared\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('currencies')->delete();
        $list = Currency::where('id', 'VND')->get();
        if (!$list || $list->count() == 0) {
            Currency::create(['id' => 'VND','name' => 'VND', 'created_at' => now(), 'updated_at' => now()]);
        }
        $list = Currency::where('id', 'USD')->get();
        if (!$list || $list->count() == 0) {
            Currency::create(['id' => 'USD','name' => 'USD', 'created_at' => now(), 'updated_at' => now()]);
        }
       
        
         
    }
}
