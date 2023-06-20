<?php
namespace App\Repositories\car;

use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;

class SystemCarBase extends SystemCarAbs{

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
    protected function getTeam()
    {
        $user = new User();
        $user = auth()->user();
        $fields = $this->request->all();
        //$fields['payment_type_id'] = "0";
      
        $team_id = ApproveRoutingHelper::get_team($user->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'],$fields['amount'], $fields['currency'],$fields['payment_type_id'],0,0);
        return $team_id;
    } 
   protected function update_sub_data($obj){}
   protected function store_sub_data($obj){}
   protected function destroy_sub_data($obj){}


}
?>