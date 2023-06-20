<?php
namespace App\Repositories\document;

use App\Models\shared\DocumentType;
use App\Models\shared\Group;
use App\Models\shared\Team;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;

class DocumentBase extends DocumentAbs{

    public  function getLayout(){
        $this->layout = new HtmlAtrtibute();


        $control = new HtmlAtrtibute();
        $control->require_validation = true;
        $this->layout->title = $control;
        $control = new HtmlAtrtibute();
        $control->require_validation = true;
        $this->layout->amount = $control;
        $control = new HtmlAtrtibute();
        $control->require_validation = true;
        $this->layout->content = $control;
        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $control->isVisible = false;
        $this->layout->budget_type = $control;
        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $this->layout->budget_num = $control;

        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $control->isVisible = false;
        $this->layout->doc_type_id = $control;


         //ẩn hiện theo loại chứng từ đã có đánh dấu
         //dd($this->documentType);
         if($this->documentType && $this->documentType->approve_manual == 1)
         {
                $control = new HtmlAtrtibute();
                $this->layout->showstep = $control;

                 $control = new HtmlAtrtibute();
                 $this->layout->add_user = $control;

                 $control = new HtmlAtrtibute();
                 $control->require_validation = true;
                 $this->layout->add_user_confirm = $control;

                 $control = new HtmlAtrtibute();
                 $control->require_validation = true;
                 $this->layout->add_user_view = $control;

                 $control = new HtmlAtrtibute();
                 $control->require_validation = true;
                 $this->layout->add_user_approve = $control;

                 $control = new HtmlAtrtibute();
                 $control->require_validation = false;
                 $this->layout->amount = $control;

         }

        return $this->layout;
    }

    //các hàm override
    protected function getTeam($obj)
    {

        $team_id = null;

        $documentType = DocumentType::find($this->request->document_type_id);
        if ($documentType->approve_manual == '1') {
            if (!$obj || !$obj->team_id) {
                $team_id =  $this->createTeam();

            }else{

                $team_id = $obj->team_id;
            }
            $this->assign_user($team_id,$this->request->team_users);

        }else {
            $team_id =  $this->getTeamConfig();


            if ($obj ) {

                $team = Team::find($obj->team_id);
                 //cẩn thận - không xóa các team cấu hình - chỉ xóa các team auto
                if ($team && $team->name == '_AUTO') {
                    $team->delete();
                }
            }
            $team_id = ApproveRoutingHelper::createTeamFrom($team_id);

        }
        return $team_id;
    }

   


   protected function update_sub_data($obj){}
   protected function store_sub_data($obj){}
   protected function destroy_sub_data($obj){}


}
?>
