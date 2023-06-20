<?php

use App\Models\shared\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = DocumentType::where('code', 'DNTT')->get();
        //  dd($list );
        if (!$list || $list->count() == 0) {
            DocumentType::create(['code' => 'DNTT', 'name' => 'Đề nghị thanh toán', 'active' => 1, 'module' => 'PAYMENT']);
        }
        $dntt = DocumentType::where('code','DNTT')->first();
        $dntt->currency_approved = 'VND';
        $dntt->save();
        $list = DocumentType::where('code', 'DNTU')->get();
        //  dd($list );
        if (!$list || $list->count() == 0) {
            DocumentType::create(['code' => 'DNTU', 'name' => 'Đề nghị tạm ứng', 'active' => 1, 'module' => 'PAYMENT']);
        }
        $list = DocumentType::where('code', 'QTTU')->get();
        //  dd($list );
        if (!$list || $list->count() == 0) {
            DocumentType::create(['code' => 'QTTU', 'name' => 'Quyết toán tạm ứng', 'active' => 1, 'module' => 'PAYMENT']);
        }
        $list = DocumentType::where('code', 'CPTK')->get();
        //  dd($list );
        if (!$list || $list->count() == 0) {
            DocumentType::create(['code' => 'CPTK', 'name' => 'Chi phí tiếp khách', 'active' => 1, 'module' => 'PAYMENT']);
        }
        $list = DocumentType::where('code', 'TTDB')->get();
        if (!$list || $list->count() == 0) {
            DocumentType::create(['code' => 'TTDB', 'name' => 'Thanh toán đặc biệt', 'active' => 1, 'module' => 'PAYMENT']);
        }

        $list = DocumentType::where('code', 'PDNS')->get();
        if (!$list || $list->count() == 0) {
            DocumentType::create(['code' => 'PDNS', 'name' => 'Duyệt ngân sách', 'active' => 1, 'module' => 'REPORT']);//Tờ trình duyệt
        }
		 $list = DocumentType::where('code', 'SCAR')->get();
        if (!$list || $list->count() == 0) {
            DocumentType::create(['code' => 'SCAR', 'name' => 'Phiếu car hệ thống', 'active' => 1, 'module' => 'CARS']);
        }
		$list = DocumentType::where('code', 'PCAR')->get();
        if (!$list || $list->count() == 0) {
            DocumentType::create(['code' => 'PCAR', 'name' => 'Phiếu car sản phẩm', 'active' => 1, 'module' => 'CARS']);
        }
    }

}
