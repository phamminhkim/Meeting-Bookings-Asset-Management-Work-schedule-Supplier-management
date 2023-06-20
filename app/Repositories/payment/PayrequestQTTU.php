<?php
namespace App\Repositories\payment;

use App\Models\payment\contract\ContractTerm;
use App\Models\payment\Payrequest;
use App\Models\shared\Group;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;
use Illuminate\Support\Facades\Validator;

class PayrequestQTTU extends PayrequestBase{

    public  function getLayout(){
        parent::getLayout();


        $control = new HtmlAtrtibute();
        $this->layout->payment_advance_moneys =$control;

        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $this->layout->out_budget = $control;
        return $this->layout;
    }
    protected function validate_store(){

        $validator  = parent::validate_store();

        $fields = $this->request->all();
        // if(!$this->checkTotal($fields))
        // {
        //         $validator->after(function ($validator) {
        //          $validator->errors()->add('chenhlech', 'Số tiền đề nghị và tổng tiền của chứng từ thanh toán chưa khớp.');

        //         });
        // }
        //Kiểm tra dữ liệu tạm ứng ( đã hoàn ứng chưa)
        if (isset($fields['payment_advance_moneys']) ) {

            if(!$this->check_advance_money( $fields['payment_advance_moneys']))
            {
                 $validator->after(function ($validator) {
                     $validator->errors()->add('loitamung', 'Chứng từ đã được hoàn ứng');

                 });
            }else{
                //Kiểm tra chứng từ tạm ứng có khớp với công ty đã tạm ứng hay không

                $payment_advance_moneys = $fields['payment_advance_moneys'];

                for ($i = 0; $i < count($payment_advance_moneys); $i++) {
                    $advance_money_id = "";
                    $mess = "";

                    if(isset( $payment_advance_moneys[$i]['advance_money_id'])){
                        $advance_money_id = $payment_advance_moneys[$i]['advance_money_id'];
                        $payRequest = Payrequest::find($advance_money_id);
                        $group = Group::find($fields['group_id']);
                        $mess = "Chứng từ tạm ứng ". $payRequest->serial_num . ' tại công ty '. $payRequest->company_id .
                        ' không thanh toán cho công ty ' . $group->company_id;
                        if($group->company_id !=  $payRequest->company_id){
                            $validator->after(function ($validator) use($mess) {
                                $validator->errors()->add('loichungtutamung', $mess);

                            });
                        }
                    }
                }
            }
         }

        return  $validator;
    }
     protected function validate_update($id)
     {
        $validator  = parent::validate_update($id);

        $fields = $this->request->all();
        // if(!$this->checkTotal($fields))
        // {
        //         $validator->after(function ($validator) {
        //          $validator->errors()->add('chenhlech', 'Số tiền đề nghị và tổng tiền của chứng từ thanh toán chưa khớp.');

        //         });
        // }
        //Kiểm tra dữ liệu tạm ứng ( đã hoàn ứng chưa)
        if (isset($fields['payment_advance_moneys']) ) {

            if(!$this->check_advance_money( $fields['payment_advance_moneys']))
            {
                 $validator->after(function ($validator) {
                     $validator->errors()->add('loitamung', 'Chứng từ đã sử dụng hoàn ứng');

                 });
            }else {
                $payment_advance_moneys = $fields['payment_advance_moneys'];

                for ($i = 0; $i < count($payment_advance_moneys); $i++) {
                    $advance_money_id = "";
                    $mess = "";

                    if(isset( $payment_advance_moneys[$i]['advance_money_id'])){
                        $advance_money_id = $payment_advance_moneys[$i]['advance_money_id'];
                        $payRequest = Payrequest::find($advance_money_id);
                        $group = Group::find($fields['group_id']);
                        $mess = "Chứng từ tạm ứng ". $payRequest->serial_num . ' tại công ty '. $payRequest->company_id .
                        ' không thanh toán cho công ty ' . $group->company_id;
                        if($group->company_id !=  $payRequest->company_id){
                            $validator->after(function ($validator) use($mess) {
                                $validator->errors()->add('loichungtutamung', $mess);

                            });
                        }
                    }
                }
            }
         }

        return  $validator;
     }
         //KIểm tra tổng số tiền nhập vào so với số tiền
    protected function checkTotal($fields){

        $amount_vourcher =0;
        $amount = 0;

        $payment_vouchers = $fields['payment_vouchers'];
        for ($i = 0; $i < count($payment_vouchers); $i++) {
            $amount_vourcher += $payment_vouchers[$i]['amount'];
        }
        //dd($amount_vourcher . "-" .  $fields['amount']);
        $amount = $fields['amount'];

       // dd( strcmp($amount_vourcher ,$amount)==0?true:false);
        return  strcmp($amount_vourcher ,$amount)==0?true:false;

    }
}



?>
