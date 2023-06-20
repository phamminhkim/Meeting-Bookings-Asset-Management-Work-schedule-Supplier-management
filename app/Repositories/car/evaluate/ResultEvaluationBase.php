<?php
namespace App\Repositories\car\evaluate;

use App\Models\car\ResultEvaluation;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\car\evaluate\ResultEvaluationAbs;
use App\Repositories\HtmlAtrtibute;
use App\User;

class ResultEvaluationBase extends ResultEvaluationAbs{

    public  function getLayout(){

        $this->layout = new HtmlAtrtibute();
       
       
        $control = new HtmlAtrtibute();
        $control->require_validation = true;
        $this->layout->content = $control;
       

        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $control->isVisible = false;
        $this->layout->doc_type_id = $control;

        return $this->layout;
    }
    
     //các hàm override

   protected function update_sub_data($obj){}
   protected function store_sub_data($obj){}
   protected function destroy_sub_data($obj){}


}
?>