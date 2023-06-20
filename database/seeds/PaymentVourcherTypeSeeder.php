<?php

use App\Models\payment\PaymentVoucherType;
use Illuminate\Database\Seeder;

class PaymentVourcherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          // DB::table('modules')->delete();
          $list = PaymentVoucherType::where('id', '1')->get();
          if (!$list || $list->count() == 0) {
            PaymentVoucherType::create(['id' => '1','name' => 'Hoá đơn', 'created_at' => now(), 'updated_at' => now()]);
          }
          $list = PaymentVoucherType::where('id', '2')->get();
          if (!$list || $list->count() == 0) {
            PaymentVoucherType::create(['id' => '2','name' => 'Hợp đồng', 'created_at' => now(), 'updated_at' => now()]);
          }
          $list = PaymentVoucherType::where('id', '3')->get();
          if (!$list || $list->count() == 0) {
            PaymentVoucherType::create(['id' => '3','name' => 'Báo giá', 'created_at' => now(), 'updated_at' => now()]);
          }
          $list = PaymentVoucherType::where('id', '4')->get();
          if (!$list || $list->count() == 0) {
            PaymentVoucherType::create(['id' => '4','name' => 'Yêu cầu thanh toán', 'created_at' => now(), 'updated_at' => now()]);
          }
          $list = PaymentVoucherType::where('id', '99')->get();
          if (!$list || $list->count() == 0) {
            PaymentVoucherType::create(['id' => '99','name' => 'Khác', 'created_at' => now(), 'updated_at' => now()]);
          }
    }
}
