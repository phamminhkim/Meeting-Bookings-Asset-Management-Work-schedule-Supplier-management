<?php
namespace App\Ultils\PaymentTermRun;

use App\Models\payment\PaymentTermPlan;
use Exception;

//Hợp đồng có số tiền và thời hạn cố định
class TypeOne{


    private $contract_term = null;
    private $list_plans = [];
    public function __construct($contract_term)
    {
         $this->contract_term =  $contract_term;
    }
    //Thêm kế hoạch
    public function addPlan(){
        
        if($this->contract_term !=null && $this->contract_term->sub_contract_id != null && $this->contract_term->sub_contract_id != '0'){
            throw new Exception("Điều khoản này đã bị thay thế bởi phụ lục id :".$this->contract_term->sub_contract_id, 1);
            
        }
       
        if($this->contract_term != null){
            $term_plan = new PaymentTermPlan();
            $term_plan->contract_term_id =$this->contract_term->id;
            $term_plan->contract_id = $this->contract_term->contract_id;
            $term_plan->terms_num = $this->contract_term->terms_num;
            $term_plan->content = $this->contract_term->content;
            $term_plan->date_due = $this->contract_term->date_due;
            $term_plan->amount = $this->contract_term->amount;
            // $term_plan->reference_num = $term->reference_num;
            $term_plan->note = $this->contract_term->note;
            $term_plan->term_content = $this->contract_term->term_content;
            $term_plan->sub_contract_id = $this->contract_term->sub_contract_id;
            $term_plan->user_id = Auth()->user()->id;

            $check = PaymentTermPlan::where('contract_term_id', $this->contract_term->id)->first();
                
            if (!$check  ) {
                $term_plan->save();
               
            }
           
            $list_plan[]=$term_plan;

        }
         
       
       

    }
    public function removePlan(){
        $term_plan = PaymentTermPlan::where('contract_term_id', $this->contract_term->id)->first();
                
        if ($term_plan && $term_plan->status != 2) {
            $term_plan->delete();
            return true;
        }
        return false;
        
    }
    public function __get($name)
    {
        if(property_exists($this,$name)){
            return $this->$name;
        }
        return null;
    }
    public function __set($name, $value)
    {
        if(property_exists($this,$name)){

            $this->$name = $value;
            return true;
        }
        return false;

    }



}
?>