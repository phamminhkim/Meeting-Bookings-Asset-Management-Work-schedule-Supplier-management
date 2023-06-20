<?php

use App\Models\shared\ApprovedLimit;
use App\Models\shared\BudgetType;
use App\Models\shared\Company;
use App\Models\shared\Currency;
use App\Models\shared\DocumentType;
use App\Models\shared\PaymentType;
use Illuminate\Database\Seeder;

class ApproveLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // $company  = Company::where('id','1000')->first();
         // $documentType  = DocumentType::where('code','DNTT')->first();
         // $paymentType  = PaymentType::where('id','1')->first();        
         // $budgetType   = BudgetType::where('id','1')->first(); //1:Trong ngân sách
         // $currency   = Currency::where('id','VND')->first();  
         // //document_type_id
         // if($company && $documentType && $paymentType && $budgetType && $currency){
         //    $list = ApprovedLimit::where('company_id',  $company->id)->get();
         //    if (!$list || $list->count() == 0) {
         //       ApprovedLimit::create(['id' => '1','payment_type_id'=>$paymentType->id,'code'=>'abc','budget_type'=> $budgetType->id,'from'=>'0','to'=>'0','budget_type'=>'1','currency'=>$currency->id,'name' => 'Trong ngân sách','document_type_id'=>$documentType->id,'company_id'=>$company->id, 'created_at' => now(), 'updated_at' => now()]);
         //    }
         // }
       
         // 'comapany_id', 'id','document_type_id','from','to','currency','budget_type','description','company_id','name','active','code','payment_type_id'
    }
}
