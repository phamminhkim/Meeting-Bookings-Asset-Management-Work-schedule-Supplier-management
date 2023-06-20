<?php

namespace App\Http\Controllers\api\payment;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\payment\contract\Contract;
use App\Models\payment\contract\ContractTerm;
use App\Ultils\PaymentTermRun\TypeOne;
use App\Ultils\PaymentTermRun\TypeTwo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentPlanController extends BaseController
{
    public function run_plan(Request $request)
    {
        //1.Tìm những điều khoản hợp đồng theo điều kiện tìm kiếm: từ ngày , đến ngày, công ty ....
        //2.Phát sinh điều khoản
        //3.Kiểm tra và lưu thông tin điều khoản
        //4.Trả kết quả

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 1;
        $fields = $request->all();
        $date_from = $request->input('date_from');// '2021-04-01';
        $date_to =$request->date_to; //'2021-04-30';
        $company_id = $request->company_id;//'1000';
        // $contract_id = $request->contract_id ;//'41';
        $contract_type_one = '1';
        $contract_type_two = '2';
        $contract_type_three = '3';
        $contract_type = $request->contract_type != ""?$request->contract_type:"";
        dd("abc".$request->company_id);
        //Lấy danh sách loại 1
        switch ($contract_type) {
            case '1':
                //Lấy theo ngày dự kiến
                $list_one = ContractTerm::whereBetween('date_due', [$date_from, $date_to])
                    ->where('finalization', '0')
                    ->whereHas('contract', function (Builder $query) use ($company_id) {
                        $query->where('company_id', $company_id)
                            // ->where('contract_id', $contract_id)
                            ->where('contract_type', "1")
                            ->where('finalization', '0')
                        ;
                    })->get();
                break;
                case '2':
                    //Dk: chưa thanh lý hợp đồng thì lấy ra danh sách
                    $list_two = ContractTerm::where('finalization', '0') 
                    ->whereHas('contract', function (Builder $query) use ($company_id) {
                        $query->where('company_id', $company_id)
                            // ->where('contract_id', $contract_id)
                            ->where('contract_type', "2")
                            ->where('finalization', '0')
                        ;
                    })->get();   
                case '3':
                    //Dk: chưa thanh lý hợp đồng thì lấy ra danh sách
                    $list_three = ContractTerm::where('finalization', '0') 
                        ->whereHas('contract', function (Builder $query) use ($company_id) {
                            $query->where('company_id', $company_id)
                                // ->where('contract_id', $contract_id)
                                ->where('contract_type', "3")
                                ->where('finalization', '0')
                            ;
                        })->get();
                    break;
    
            default:
                //Lấy theo ngày dự kiến
                $list_one = ContractTerm::whereBetween('date_due', [$date_from, $date_to])
                ->where('finalization', '0')
                ->whereHas('contract', function (Builder $query) use ($company_id) {
                    $query->where('company_id', $company_id)
                        // ->where('contract_id', $contract_id)
                        ->where('contract_type', "1")
                        ->where('finalization', '0')
                    ;
                })->get();
                dd($list_one);
                $list_two = ContractTerm::where('finalization', '0') 
                ->whereHas('contract', function (Builder $query) use ($company_id) {
                    $query->where('company_id', $company_id)
                        // ->where('contract_id', $contract_id)
                        ->where('contract_type', "2")
                        ->where('finalization', '0')
                    ;
                })->get();  

                $list_three = ContractTerm::where('finalization', '0') 
                ->whereHas('contract', function (Builder $query) use ($company_id) {
                    $query->where('company_id', $company_id)
                        // ->where('contract_id', $contract_id)
                        ->where('contract_type', "3")
                        ->where('finalization', '0')
                    ;
                })->get();
               
                break;
        }
       // dd($list);
       
        $add_list = [];
        foreach ($list_one as $key => $term) {

            if ($term->sub_contract_id == null) {
                if ($term->contract->contract_type == '1') {
                    $typeone = new TypeOne($term);
                    $typeone->addPlan();
                    
                    if(count($typeone->list_plans) >0){
                        $add_list[] = $typeone->list_plans;
                    }
                   
                } 
            } 
        }
        foreach ($list_two as $key => $term) {

            if ($term->sub_contract_id == null) {
                 
                $typetwo = new TypeTwo($term, $date_from, $date_to);
                $typetwo->addPlan();
                if(count($typetwo->list_plans) >0){
                    $add_list[] = $typetwo->list_plans;
                }

            } 
        }
        foreach ($list_three as $key => $term) {

            if ($term->sub_contract_id == null) {
                 
                $typetwo = new TypeTwo($term, $date_from, $date_to);
                $typetwo->addPlan();
                if(count($typetwo->list_plans) >0){
                    $add_list[] = $typetwo->list_plans;
                }

            } 
        }        
        //lấy danh sách phụ lục trong hạn 

       // dd($add_list);
        $result['data'] = $add_list;
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
