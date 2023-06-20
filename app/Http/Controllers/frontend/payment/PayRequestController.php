<?php

namespace App\Http\Controllers\frontend\payment;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\payment\Payrequest;
use App\Models\shared\Currency;
use App\Models\shared\DocumentType;
use App\Repositories\approve\ApproveMain;
use App\Repositories\payment\PayrequestBase;
use App\Repositories\payment\PayrequestCPTK;
use App\Repositories\payment\PayrequestDNTU;
use App\Repositories\payment\PayrequestMain;
use App\Repositories\payment\PayrequestQTTU;
use App\Ultils\ReadCurrency;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PayRequestController extends BaseController
{
    public function index(Request $request)
    {


        $PayrequestFrontend = new PayrequestFrontend;
        $arrayData = $PayrequestFrontend->index($request);
        return view('payment.request.index',$arrayData);
        // return view('payment.request.index',compact('type','id','doctype','doctype_id','doctype_name','layout','module','notification_id'));

    }

    // public function show($id)
    // {

    //     // $type = $request->type;
    //     // $id =   $request->id;
    //    // $layout =  $payrequestBase->getLayout();
    //     $payrequest = Payrequest::findOrFail($id);

    //     return view('payment.request.index',compact('payrequest'));

    // }
    public function print(Request $request,$id){


        $paymain =  PayrequestMain::create($request,"");
        $payrequest =  $paymain->show($id);
        //$payrequest = Payrequest::find($id)->with('');
       // dd(Ultils::convert_number_to_words(12500.34));
       $info = array();

       $total = 0;
       $info['list_file'] = array();
       $list_file = array();
       //Tổng số tiền cần thanh toán
        if($payrequest->payment_vouchers){

            $info['list_file']['purchase_invoice'] = 0;
            $info['list_file']['purchase_voucher'] = 0;


            foreach ($payrequest->payment_vouchers as $voucher) {
                $attached_file = array();
                $total = $total + $voucher->amount;
                $total_file = count($voucher->attachment_file);

                if($total_file  > 0){
                    $attached_file['name'] = $voucher->typeobj->name ;
                    $attached_file['value'] = $total_file ;
                    $key = array_search( $voucher->typeobj->name,array_column($list_file,'name'));

                    if(false !== $key){
                        $list_file[$key]['value'] +=  $total_file;


                    }else{
                        array_push( $list_file,$attached_file);
                    }

                }

                // if($total_file  > 0){


                //     if($voucher->type == '1'){
                //         $info['list_file']['purchase_invoice'] += $total_file ;
                //     }else{
                //         $info['list_file']['purchase_voucher'] += $total_file ;
                //     }
                // }
               // dd( $list_file['purchase_invoice'] );

            }

            // array_push( $info['list_file'],$list_file);

        }

        //Chứng từ đính kèm
        if($payrequest->payment_attacheds){


            foreach ($payrequest->payment_attacheds as $attached) {
                $attached_file = array();
                $total_file = count($attached->attachment_file);
                if($total_file  > 0){
                    $attached_file['name'] = $attached->name ;
                    $attached_file['value'] = $total_file ;

                    $key = array_search( $attached->name,array_column($list_file,'name'));

                    if(false !== $key){
                        $list_file[$key]['value'] +=  $total_file;
                    }else{
                        array_push( $list_file,$attached_file);
                    }
                   // array_push( $list_file,$attached_file);

                }
            }


        }
        $info['list_file']['file_attached']  =array();
        $info['list_file']['file_attached'] = $list_file;
        //dd( $info['list_file'] );
        //Tổng số tiền đã tạm ứng
        $total_advance_money = 0;
        if($payrequest->payment_advance_moneys){
            foreach ($payrequest->payment_advance_moneys as $advance_money) {
                $total_advance_money = $total_advance_money + $advance_money->amount;
            }
        }
        $currency = Currency::find($payrequest->currency);
        //Số tiền hoàn lại ( danh cho quyết toán tạm ứng)
        $info['amount_refund'] = $total_advance_money - $total;
        $readCurr = new ReadCurrency(abs($total_advance_money - $total),$payrequest->currency,"VN",$currency->unit,$currency->odd,$currency->and,$currency->twounit);
        $info['amount_refund_word'] = $readCurr->read_to_words();

        $readCurr = new ReadCurrency($payrequest->amount,$payrequest->currency,"VN",$currency->unit,$currency->odd,$currency->and,$currency->twounit);

        $info['amount'] = $payrequest->amount;
        $info['amount_word'] = $readCurr->read_to_words() ;

        $readCurr = new ReadCurrency($total,$payrequest->currency,"VN",$currency->unit,$currency->odd,$currency->and,$currency->twounit);
        $info['amount_voucher'] = $total;
        $info['amount_voucher_word'] = $readCurr->read_to_words() ;
        $readCurr = new ReadCurrency($total,$payrequest->currency,"VN",$currency->unit,$currency->odd,$currency->and,$currency->twounit);

        $info['decimalpoint'] = ',';
        $info['separator'] = '.';
        if($payrequest->currency == 'VND'){
            $info['decimal']  = 0;
        }
        else{
            $info['decimal']  = 2;
            // $info['decimalpoint'] = '.';
            // $info['separator'] = ',';

        }
        //Format theo tiền việt

        //dd($payrequest->team->users);
        //Lấy danh sách chữ ký đã được ký
        $approve = ApproveMain::create(Ultils::$MODULE_PAYMENT, $id);
        $approve_info = $approve->get_info();
       // dd($approve->get_info());
    //    dd( $approve_info);
       $signs = array();
       $signviews = array();
       if(isset($approve_info['finished']) &&  $approve_info['finished'] == 'X'){

          foreach ($approve_info['steps'] as $step) {

            foreach ($step['users'] as  $user) {

                if($user->sign != null){
                   // dd($user->sign);
                    //$signs[$user->sign] = $user;

                    array_push( $signs, $user);
                }
            }


          }
          $signs = collect($signs)->sortBy('step')->sortBy('sign')->values();
       }else {

         //Lấy thông tin người duyệt trong view lên File In - Trong trường hợp chưa duyệt xong
         if($payrequest->team && $payrequest->team->users){
             $i = 0;
            foreach ($payrequest->team->users as $user) {
                if ($user->pivot->sign) {
                    //$signviews[$user->pivot->sign] = $user;
                    array_push( $signviews, $user);
                }

                // dd( $user->pivot->sign);

            }
           // dd($signviews);
        }
        $signviews = collect($signviews)->sortBy('step')->sortBy('sign')->values();
       }
        $reminder = $payrequest->reminder->where('print', 1);
        $info['list_reminder']  =array();
        $info['list_reminder'] = $reminder;

        return view('payment.report.index',compact('payrequest','info','signs','signviews', 'reminder'));

    }
    public function statistics(Request $request)
    {

        $PayrequestFrontend = new PayrequestFrontend;
        $arrayData = $PayrequestFrontend->index($request);
        return view('payment.request.statistics',$arrayData);


    }


}
