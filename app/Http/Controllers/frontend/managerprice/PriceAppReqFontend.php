<?php
namespace App\Http\Controllers\frontend\managerprice;
use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\managerprice\PriceReq;
use App\Models\shared\DocumentType;
use App\Repositories\approve\ApproveMain;
use App\Repositories\managerprice\PriceAppReqBase;
use App\Repositories\managerprice\PriceAppReqDGIA;
use App\Repositories\managerprice\PriceAppReqMain;
use App\Ultils\ReadCurrency;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Class này quản lý dữ liệu cho frontend của pricerequest
 */
class PriceAppReqFontend extends BaseController{

    public function index(Request $request)
    {

        $type = $request->type;
        $id =   $request->id;
        $pricerequest = PriceReq::find($id);

        $doctype = "";
        if($pricerequest){

            $documentType =DocumentType::where('id',$pricerequest->document_type_id)->first();

            if($documentType){
                $doctype = $documentType->code;
            }

        }else{
            $doctype = $request->doctype;
        }

        switch ($type) {
            case 'add':
                $this->authorize('create', new PriceReq());
                break;
            case 'view':
                $this->authorize('view', PriceReq::findOrFail($id));
                break;
            case 'edit':
                $this->authorize('update',  PriceReq::findOrFail($id));
                break;
           case 'print':
                    $this->authorize('view',  PriceReq::findOrFail($id));
                    break;
            default:

                $this->authorize('view', new PriceReq());

                break;
        }

        //Điều khiển layout ẩn hiện và hiển thị dấu (*)
        switch ($doctype) {
            case Ultils::$MODULE_DOCUMENT_DGIA: // Trình duyệt vượt ngân sách
                $requestBase= new PriceAppReqDGIA($request);
                break;
            default: //DNTT
                $requestBase = new  PriceAppReqBase($request);

                break;
        }

        $layout =  $requestBase->getLayout();

        $documentType = DocumentType::where('code',$doctype)->first();

        $doctype_id= '';
        $doctype_name= '';


        if($documentType){
            $doctype_id = $documentType->id;
            $doctype_name = $documentType->name;
        }

       // dd($pricerequest->amount);
       // return view('payment.request.index',compact('type','id','doctype','doctype_id','doctype_name','pricerequest'));
        $module = Ultils::$MODULE_PRICE;


       // dd("tesst" . $request->notification_id);
        $notification_id = $request->notification_id;
        $array = [];
        $array['type'] = $type;
        $array['id'] = $id;
        $array['doctype'] = $doctype;
        $array['doctype_id'] = $doctype_id;
        $array['doctype_name'] = $doctype_name;
        $array['layout'] = $layout;
        $array['module'] = $module;
        $array['notification_id'] = $notification_id;

        return  $array ;

    }
    public function print(Request $request,$id){

        $request->id = $id;
        // $doc_temp = PriceReq::find($id);
        $doc_temp = PriceReq::with('vendor','department','proposer','group', 'reminders','approveds','company', 'other_attacheds','other_attacheds.attachment_file',
        'attachment_file','products')->find($id);
        $request->document_type_id  = $doc_temp->document_type->id;
       // dd($doc_temp->document_type);
        $docmain =  PriceAppReqMain::create($request,'');
        $document =  $docmain->show($id);
        $form_name = $docmain->form_name;
      
        //$document = Payrequest::find($id)->with('');
       // dd(Ultils::convert_number_to_words(12500.34));
       $info = array();
        // dd($docmain->getLayout());
       $total = 0;
       $info['list_file'] = array();
       $list_file = array();
       $file_approve_attached = array();
      // dd($request);
       $info['data'] =  $doc_temp ;

       $info['form_name'] = $form_name !=""?$form_name:($document->document_type?$document->document_type->name:"");

        $info['layout'] = $docmain->getLayout() ;
       //
        if($document->attachment_file){

            foreach ($document->attachment_file as $file) {
                $attached_file['name'] = $file->name ;
                $attached_file['value'] = "" ;
                array_push( $file_approve_attached,$attached_file);
            }

        }
        $info['list_file']['file_approve_attached']  =array();
        $info['list_file']['file_approve_attached'] = $file_approve_attached;
        if($document->other_attacheds){


            foreach ($document->other_attacheds as $attached) {
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


        //count vendor

        //Chứng từ đính kèm

        $readCurr = new ReadCurrency($document->amount,$document->currency);

        $info['amount'] = $document->amount;
        // $info['amount_word'] = $readCurr->read_to_words() ;

        $info['decimalpoint'] = ',';
        $info['separator'] = '.';
        if($document->currency == 'VND'){
            $info['decimal']  = 0;
        }
        else{
            $info['decimal']  = 2;
            // $info['decimalpoint'] = '.';
            // $info['separator'] = ',';

        }

        $list_vendor = $this->listVendor($doc_temp);
        $info['vendor_count']  = count($list_vendor) ;
        $info['show_spec']  = $this->showSpec($doc_temp) ;
        //Format theo tiền việt

        //dd($document->team->users);
        //Lấy danh sách chữ ký đã được ký
        $approve = ApproveMain::create(Ultils::$MODULE_PRICE, $id);
        $approve_info = $approve->get_info();
       // dd($approve->get_info());
    //    dd( $approve_info);
       $signs = array();

       if(isset($approve_info['finished']) &&  $approve_info['finished'] == 'X'){

          foreach ($approve_info['steps'] as $step) {

            foreach ($step['users'] as  $user) {

                if($user->sign != null){
                   // dd($user->sign);
                    $signs[$user->sign] = $user;
                }
            }


          }
       }


        return view('managerprice.report.index',compact('document','info','signs'));

    }
    public function listVendor($praprequest){
        $vendors = array();
        $vendor_key = "";

       foreach ($praprequest->products as $key1 => $product) {
            foreach ($product->details as $key2 => $detail) {
                $vendor_key = $detail->vendor_id  ."@". $detail->vendor_display;
                if (!in_array($vendor_key,$vendors)) {
                 array_push($vendors,$vendor_key);
                 }

             }

        }
        return $vendors;
    }
    public function  showSpec($praprequest){
        $flag = false;
        $check_quan = true;
        foreach ($praprequest->products as $key => $product) {
            foreach ($product->details as $key => $detail) {
                 //Do đặc thù spec nên số lượng nhập phải là 1 hoặc không nhập
                 if($detail->quantity > 1 )
                 {
                     $check_quan = false;
                 }
            }
            foreach ($product->specs as $key => $spec) {
               foreach ($spec->others as $key => $other) {
                if ($other->vendor_id === $praprequest->vendor_id) {

                    $flag =  true;
                 }
               }
            }
        }
        return $flag && $check_quan;
      }
}
?>
