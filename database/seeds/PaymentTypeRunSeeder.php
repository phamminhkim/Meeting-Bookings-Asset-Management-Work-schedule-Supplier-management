<?php

use App\Models\shared\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeRunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  DB::table('payment_types')->delete();

        $list = PaymentType::where('id', '1')->get();
        if (!$list || $list->count() == 0) {
            PaymentType::create(['id' => '1', 'name' => 'Thanh toán', 'created_at' => now(), 'updated_at' => now()]);
        }
        $list = PaymentType::where('id', '2')->get();
        if (!$list || $list->count() == 0) {
            PaymentType::create(['id' => '2', 'name' => 'Thanh toán NVL', 'created_at' => now(), 'updated_at' => now()]);
        }
        $list = PaymentType::where('id', '0')->get();
        if (!$list || $list->count() == 0) {
            PaymentType::create(['id' => '0', 'name' => 'Tờ trình', 'created_at' => now(), 'updated_at' => now()]);
        }

    }
}
