<?php
namespace App\Repositories\document;

use App\Models\document\Document;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class DocumentBGIA extends DocumentBase{

    public function __construct(Request $request)
    {
        parent::__construct( $request);
        //$this->form_name = __('Tờ trình');
       
    }

    public  function getLayout(){
        parent::getLayout();
        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $control->isVisible = false;
        $this->layout->amount = $control;
      
        return $this->layout;
    }
    protected function validate_store(){
       
        $this->request['amount'] = 0;
        $this->request['budget_type'] = 1;
        $validator  = parent::validate_store();
        
        return  $validator;
    }
    protected function validate_update($id)
    {
        $this->request['amount'] = 0;
        $this->request['budget_type'] = 1;
       $validator  = parent::validate_update($id);
        //    $fields = $this->request->all();
        //    $user = new User();
        //    $user = auth()->user();
        //    $document = Document::findOrFail($id);
        
        //    if($user->company){
        
        //     $team_id = ApproveRoutingHelper::get_team($user->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'],$fields['amount'], $fields['currency'],$fields['payment_type_id'],0,0);
        //     // dd($team_id);
        //     if ($team_id == "") {
        //         $validator->after(function ($validator) {
        //             $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
        //         });

        //     }

        //       if($document->team_id <> $team_id){
        //         foreach ( $document->approveds as  $approve) {

        //             if($approve->online == 'X' && $approve->release <> '1'){
        //                 $validator->after(function ($validator) {
        //                     $validator->errors()->add('phantuyen', 'Chứng từ đã gửi không thể phân tuyến lại.');
        //                 }); 
        //             }
                    
        //         }

        //      }
        // }
  
       return  $validator;
    }
     
}
?>