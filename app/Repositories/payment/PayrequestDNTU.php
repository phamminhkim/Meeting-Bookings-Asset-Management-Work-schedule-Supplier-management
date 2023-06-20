<?php 
namespace App\Repositories\payment;

use App\Models\payment\contract\ContractTerm;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;
use Illuminate\Support\Facades\Validator;

class PayrequestDNTU extends PayrequestBase{

    public  function getLayout(){
        parent::getLayout();
        
        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $control->isVisible = false; 
        $this->layout->budget_type = $control;
        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $this->layout->money_receiver = $control;
        $control = new HtmlAtrtibute();
        $control->isVisible = false; 
        $this->layout->payment_vouchers =$control;
        $control = new HtmlAtrtibute();
        $this->layout->payment_attacheds =$control;

        $control = new HtmlAtrtibute();
        $control->require_validation = false;
       
        // $control->has_custom_label = false;
        // $control->custom_label_text = __('form.settlement_period');
        // $this->layout->finish_date  =$control;
       

        return $this->layout;
    }
    protected function validate_store(){
        
        $this->request['budget_type'] = 1;
        $validator  = parent::validate_store();
      
        $fields = $this->request->all();
        
         
        return  $validator;
    }
    protected function validate_update($id)
    {
        $this->request['budget_type'] = 1;
       $validator  = parent::validate_update($id);
        
       $fields = $this->request->all();
   
 
       return  $validator;
    } 
}



?>