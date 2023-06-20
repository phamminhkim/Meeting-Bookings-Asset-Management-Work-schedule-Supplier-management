<?php 
namespace App\Repositories\payment;

use App\Models\payment\contract\ContractTerm;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;
use Illuminate\Support\Facades\Validator;

class PayrequestCPTK extends PayrequestBase{

    public  function getLayout(){
        parent::getLayout();
        $control = new HtmlAtrtibute();
        $control->require_validation = true;
        $this->layout->budget_type = $control;

        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $this->layout->money_receiver = $control;

       
        $control = new HtmlAtrtibute();
        $control->require_validation = true;
        $this->layout->doc_reference = $control;
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