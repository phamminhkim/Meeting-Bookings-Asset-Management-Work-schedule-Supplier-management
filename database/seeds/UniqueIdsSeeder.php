<?php

use App\Models\shared\UniqueId;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniqueIdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('unique_ids')->delete();

        $list = UniqueId::where('document_type_code','DNTT')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'PAYMENT','letter' => 'DNTT','document_type_code' => 'DNTT', 'serial'=>'000001']);
        }
        $list = UniqueId::where('document_type_code','DNTU')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'PAYMENT','letter' => 'DNTU','document_type_code' => 'DNTU', 'serial'=>'000001']);
        }
        $list = UniqueId::where('document_type_code','CPTK')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'PAYMENT','letter' => 'CPTK','document_type_code' => 'CPTK', 'serial'=>'000001']);
        }
        $list = UniqueId::where('document_type_code','QTTU')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'PAYMENT','letter' => 'QTTU','document_type_code' => 'QTTU', 'serial'=>'000001']);
        }
        $list = UniqueId::where('document_type_code','TTDB')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'PAYMENT','letter' => 'TTDB','document_type_code' => 'TTDB', 'serial'=>'000001']);//Thanh toán đặc biệt
        }

        // $list = UniqueId::where('document_type_code','DVNS')->get();
        // if(!$list || $list->count()==0){
        //     UniqueId::create(['module' => 'REPORT','letter' => 'DVNS','document_type_code' => 'DVNS', 'serial'=>'000001']);//Duyệt vượt /ngoài ngân sách
        // }
        $list = UniqueId::where('document_type_code','PDNS')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'REPORT','letter' => 'PDNS','document_type_code' => 'PDNS', 'serial'=>'000001']);//Phê duyệt Ngân sách
        }
        $list = UniqueId::where('document_type_code','GTTT')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'PAYMENT','letter' => 'GTTT','document_type_code' => 'GTTT', 'serial'=>'000001']);//Giao tế tài trợ
        }
        $list = UniqueId::where('document_type_code','PCAR')->where('company_id','1000')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'CARS','letter' => 'PCAR','document_type_code' => 'PCAR', 'serial'=>'000001','company_id'=>'1000','year'=>'2021']);
        }
		$list = UniqueId::where('document_type_code','PCAR')->where('company_id','2000')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'CARS','letter' => 'PCAR','document_type_code' => 'PCAR', 'serial'=>'000001','company_id'=>'2000','year'=>'2021']);
        }
		$list = UniqueId::where('document_type_code','PCAR')->where('company_id','3000')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'CARS','letter' => 'PCAR','document_type_code' => 'PCAR', 'serial'=>'000001','company_id'=>'3000','year'=>'2021']);
        }
		$list = UniqueId::where('document_type_code','PCAR')->where('company_id','5000')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'CARS','letter' => 'PCAR','document_type_code' => 'PCAR', 'serial'=>'000001','company_id'=>'5000','year'=>'2021']);
        }
        $list = UniqueId::where('document_type_code','SCAR')->where('company_id','1000')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'CARS','letter' => 'SCAR','document_type_code' => 'SCAR', 'serial'=>'000001','company_id'=>'1000','year'=>'2021']);
        }
		$list = UniqueId::where('document_type_code','SCAR')->where('company_id','2000')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'CARS','letter' => 'SCAR','document_type_code' => 'SCAR', 'serial'=>'000001','company_id'=>'2000','year'=>'2021']);
        }
		$list = UniqueId::where('document_type_code','SCAR')->where('company_id','3000')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'CARS','letter' => 'SCAR','document_type_code' => 'SCAR', 'serial'=>'000001','company_id'=>'3000','year'=>'2021']);
        }
		$list = UniqueId::where('document_type_code','SCAR')->where('company_id','5000')->get();
        if(!$list || $list->count()==0){
            UniqueId::create(['module' => 'CARS','letter' => 'SCAR','document_type_code' => 'SCAR', 'serial'=>'000001','company_id'=>'5000','year'=>'2021']);
        }
    }
}
