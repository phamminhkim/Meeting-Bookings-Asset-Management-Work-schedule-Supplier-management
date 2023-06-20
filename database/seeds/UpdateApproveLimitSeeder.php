<?php

use App\Models\shared\ApprovedLimit;
use App\Models\shared\DocumentType;
use App\Repositories\shared\FactoryCode;
use Illuminate\Database\Seeder;

class UpdateApproveLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list =ApprovedLimit::all();
        $factoryCode = null;
        foreach ($list as $item) {
            $factoryCode = new FactoryCode;
            
            $documentType = DocumentType::find($item->document_type_id);

            if ($documentType) {
                
                $factoryCode->documentCode = $documentType->code;
            }
           
            $factoryCode->company_id = $item->company_id;
            $factoryCode->budget_type = $item->budget_type;
          
            if($item->payment_type_id == 0){
                $item->payment_type_id = 1;
            }else{
                $factoryCode->payment_type_id = $item->payment_type_id;
            }
            
            $factoryCode->currency = $item->currency;
            $factoryCode->name = $item->name;
          
            $item->code = $factoryCode->create();
            $item->save();

                
        }
    }
}
