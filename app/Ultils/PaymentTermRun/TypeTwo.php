<?php
namespace App\Ultils\PaymentTermRun;

use App\Models\payment\PaymentTermPlan;
use App\Ultils\Ultils;
use Exception;

//Hợp đồng hàng tháng
class TypeTwo{

    private $date_from = "";
    private $date_to = "";
     
    private $contract_term = null;
    private $list_plans = [];
    //Phát sinh kế hoạch trong khoảng thời gian từ ngày đến ngày
    public function __construct($contract_term,$date_from,$date_to )
    {
         $this->date_from =  $date_from;
         $this->date_to =  $date_to;
         $this->contract_term =  $contract_term;
       // $this->list_plans = [ ];
      
        
    }
    //Thêm kế hoạch
    public function addPlan(){
        
        if($this->contract_term !=null && $this->contract_term->sub_contract_id != null && $this->contract_term->sub_contract_id != '0'){
            throw new Exception("Điều khoản này đã bị thay thế bởi phụ lục id :".$this->contract_term->sub_contract_id, 1);
            
        }
        if($this->contract_term->frequency == null ||  $this->contract_term->frequency_type == null || $this->contract_term->frequency == 0 ){
            throw new Exception("Chưa nhập chu kỳ thanh toán");
            
        }
        //Cập nhật mới các kế hoạch chưa được thanh toán trong khoảng thời gian mong muốn

        if($this->contract_term != null){
            //nếu chạy lại thì xoá và  cập nhật lại toàn bộ trong khoảng thời gian input
           
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
            //array_push($this->list_plan, $term_plan);
           
            //           
            $nex_due = $term_plan->date_due;
            while (date('Y-m-d', strtotime($this->date_to)) > date('Y-m-d', strtotime($nex_due)) ) {
                
                $check = PaymentTermPlan::where('contract_term_id', $this->contract_term->id)                         
                                            ->where('date_due',$nex_due)->first();
                if(!$check){
                    $new_plan = $term_plan->replicate();
                    $new_plan->date_due = $nex_due;
                    $new_plan->save();
                    $this->list_plans[] =  $new_plan;
               
                }                          
                
                $nex_due =  Ultils::getDateDueNext($nex_due, $this->contract_term->frequency, $this->contract_term->frequency_type);

                //nếu có hạn chế thời gian hợp đồng 
                if($this->contract_term->duration != null && $this->contract_term->duration > 0){

                    $already_plans = PaymentTermPlan::where('contract_term_id', $this->contract_term->id)->get();
                    
                    if($already_plans->isNotEmpty()){
                        if($already_plans->count() >= $this->contract_term->duration ){
                            $nex_due = $this->date_to;
                        }

                    }
                     
                }
            }
           
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