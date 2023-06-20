<?php
namespace App\Repositories\managerprice;


use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\Repositories\managerprice\PriceAppReqBase;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PriceAppReqDGIA extends PriceAppReqBase{

    public function __construct(Request $request)
    {
        parent::__construct( $request);
        $this->form_name = __('Trình duyệt giá');

    }

    public  function getLayout(){
        parent::getLayout();

        $control = new HtmlAtrtibute();
        $control->require_validation = true;
        $this->layout->group_id = $control;

        return $this->layout;
    }
    protected function validate_store(){


        $validator  = parent::validate_store();
        return  $validator;
    }
    protected function validate_update($id)
    {
       $validator  = parent::validate_update($id);
       return  $validator;
    }

}
?>
