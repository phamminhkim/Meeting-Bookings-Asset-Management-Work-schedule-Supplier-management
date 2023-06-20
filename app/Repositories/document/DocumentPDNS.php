<?php
namespace App\Repositories\document;

use App\Models\document\Document;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class DocumentPDNS extends DocumentBase{

    public function __construct(Request $request)
    {
        parent::__construct( $request);
        $this->form_name= __('PHIẾU ĐỀ NGHỊ BỔ SUNG NGÂN SÁCH');
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
    protected function validate_store(){

        $this->request['budget_type'] = 1;
        $validator  = parent::validate_store();

        return  $validator;
    }
    protected function validate_update($id)
    {
        $this->request['budget_type'] = 1;
       $validator  = parent::validate_update($id);
       $fields = $this->request->all();
       $user = new User();
       $user = auth()->user();
       $document = Document::findOrFail($id);



       return  $validator;
    }

}
?>
