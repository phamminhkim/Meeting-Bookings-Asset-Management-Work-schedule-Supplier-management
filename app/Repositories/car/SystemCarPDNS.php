<?php
namespace App\Repositories\car;

use App\Models\car\Car;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class SystemCarPDNS extends SystemCarBase{

    public function __construct(Request $request)
    {
        parent::__construct( $request);
        $this->form_name= __('Phiếu car hệ thống');
    }

    public  function getLayout(){
         parent::getLayout();
         //$this->layout = new HtmlAtrtibute();
         $control = new HtmlAtrtibute();
         $control->require_validation = true;
         $control->has_custom_label = true;
         $control->custom_label_text = __('form.excess_amount');
         $this->layout->amount = $control;
        
        return $this->layout;
    }
    protected function validate_update($id)
    {
        $this->request['budget_type'] = 0;
       $validator  = parent::validate_update($id);
       $fields = $this->request->all();
       $user = new User();
       $user = auth()->user();
       $document = Car::findOrFail($id);
      
       if($user->company){
       
        $team_id = ApproveRoutingHelper::get_team($user->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'],$fields['amount'], $fields['currency'],$fields['payment_type_id'],0,0);
        // dd($team_id);
        if ($team_id == "") {
            $validator->after(function ($validator) {
                $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
            });

        }
    }
  
       return  $validator;
    }
   
}
?>