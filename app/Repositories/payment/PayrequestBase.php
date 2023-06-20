<?php 
namespace App\Repositories\payment;

use App\Repositories\HtmlAtrtibute;

class PayrequestBase extends PayrquestAbs{
     public  function getLayout(){

        $this->layout = new HtmlAtrtibute();
         $control = new HtmlAtrtibute();
         $control->require_validation = true;
         $this->layout->proposer_payment = $control;
         $control = new HtmlAtrtibute();
         $control->require_validation = true;
         $this->layout->currency = $control;
         $control = new HtmlAtrtibute();
         $control->require_validation = true;
         $this->layout->exchange_rate = $control ;
         $control = new HtmlAtrtibute();
         $control->require_validation = true;
         $this->layout->amount =$control ;
         
         $this->layout->amount_exchanged =$control ;
        $control = new HtmlAtrtibute();
        $control->require_validation = true; 
   

        $this->layout->content  =$control;
        $control = new HtmlAtrtibute();
        $control->require_validation = true; 
        $this->layout->money_receiver  =$control;
        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $this->layout->finish_date  =$control;
        $control = new HtmlAtrtibute();
        $control->require_validation = true; 
        $this->layout->method_payment  =$control ;

        $control = new HtmlAtrtibute();
        $control->require_validation = true; 
        $this->layout->bank_id  =$control ;

        $control = new HtmlAtrtibute();
        $control->require_validation = true; 
        $this->layout->bank_name  =$control ;
        $control = new HtmlAtrtibute();
        $control->require_validation = false; 
        $this->layout->bank_branch  =$control ;

        $control = new HtmlAtrtibute();
        $control->require_validation = true; 
        $this->layout->bank_account  =$control ;
        //  $this->layou->bank_id  ;
        //  $this->layou->bank_account;
        $control = new HtmlAtrtibute();
        $control->require_validation = true; 
        
        $this->layout->budget_type  =$control;

        $control = new HtmlAtrtibute();
        $control->require_validation = true;
        $control->isVisible = true;
        $this->layout->budget_num = $control;
        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $control->isVisible = false;
        $this->layout->out_budget = $control;
        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $this->layout->amount_out_budget = $control;
        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $this->layout->amount_out_exchanged = $control;

        $control = new HtmlAtrtibute();
        $this->layout->doc_reference = $control ;

        //Tab
        $control = new HtmlAtrtibute();
        $control->require_validation = true; 
        $this->layout->group_id =$control ;
        $control = new HtmlAtrtibute();
        $control->isVisible = false; 
        $this->layout->contract_term =$control;
        $control = new HtmlAtrtibute();
        $this->layout->payment_vouchers =$control;
        $control = new HtmlAtrtibute();
        $this->layout->payment_attacheds =$control;
     //    $control = new HtmlAtrtibute();
     //    $control->isVisible = false; 
     //    $this->layout->payment_advance_money =$control;

        $control = new HtmlAtrtibute();
        $control->isVisible = false; 
        $this->layout->payment_advance_moneys =$control;
         return $this->layout;

     }
}



?>