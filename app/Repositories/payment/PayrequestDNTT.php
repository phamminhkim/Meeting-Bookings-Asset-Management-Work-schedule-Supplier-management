<?php
namespace App\Repositories\payment;

use App\Models\payment\contract\ContractTerm;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;
use Illuminate\Support\Facades\Validator;

class PayrequestDNTT extends PayrequestBase{


    public  function getLayout(){
        parent::getLayout();
        $control = new HtmlAtrtibute();
        $control->isVisible = false;
        $this->layout->contract_term =$control;

        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $this->layout->out_budget = $control;

        // $control = new HtmlAtrtibute();
        // $control->require_validation = true;
        // $control->isVisible = true;
        // $control->is_read_only = true;
        // $this->layout->amount = $control;


        return $this->layout;
    }
    protected function validate_store(){

        $validator  = parent::validate_store();

        $fields = $this->request->all();

 
        return  $validator;
    }
    protected function validate_update($id)
    {
       $validator  = parent::validate_update($id);

       $fields = $this->request->all();
      
       return  $validator;
    }
    
}



?>
