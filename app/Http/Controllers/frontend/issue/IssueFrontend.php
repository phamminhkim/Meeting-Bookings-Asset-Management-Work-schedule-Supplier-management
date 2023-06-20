<?php

namespace App\Http\Controllers\frontend\issue;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\issue\Issue;
use App\Models\shared\DocumentType;
use App\Repositories\approve\ApproveMain;
use App\Repositories\issue\IssueBase;
use App\Repositories\issue\IssueMain;
use App\Repositories\issue\IssueYCDV;

use App\Ultils\ReadCurrency;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

class IssueFrontend extends BaseController
{
    public function index(Request $request)
    {

        $form_name = "";
        $type = $request->type;
        $id =   $request->id;
        $document = Issue::find($id);

        $doctype = "";
        if($document){

            $documentType = DocumentType::where('id',$document->document_type_id)->first();

            if($documentType){
                $doctype = $documentType->code;
            }

        }else{
            $doctype = $request->doctype;
            $documentType = DocumentType::where('code',$doctype)->first();

        }

        switch ($type) {
            case 'add':
                $this->authorize('create', new Issue());
                break;
            case 'view':
                $this->authorize('view', Issue::findOrFail($id));
                break;
            case 'edit':
                $this->authorize('update',  Issue::findOrFail($id));
                break;
           case 'print':
                    $this->authorize('view',  Issue::findOrFail($id));
                    break;
            default:

                $this->authorize('view', new Issue());

                break;
        }

        //Điều khiển layout ẩn hiện và hiển thị dấu (*)
        switch ($doctype) {
            case Ultils::$MODULE_ISSUE_YCDV :
                $documentBase = new  IssueYCDV($request);
                if($documentBase){
                    $form_name = $documentBase->form_name;
                }
                break;
            default:
                $documentBase = new  IssueBase($request);
                break;
        }

        $documentBase->documentType = $documentType;//NHớ truyền để kiểm tra loại chứng từ xử lý
        $layout =  $documentBase->getLayout();
        $documentType = DocumentType::where('code',$doctype)->first();

        $doctype_id= '';
        $doctype_name= '';
        // $showstep = '';


        if($documentType){
            $doctype_id = $documentType->id;
            $doctype_name = $documentType->name;
            // $showstep =  $documentType->approve_manual == 1?"X":"";
        }

        $module = Ultils::$MODULE_ISSUE;

       // dd("tesst" . $request->notification_id);
        $notification_id = $request->notification_id;

        $array = [];

        $array['type'] = $type;
        $array['id'] = $id;
        $array['doctype'] = $doctype;
        $array['doctype'] = $doctype;
        $array['doctype_id'] = $doctype_id;
        $array['doctype_name'] = $doctype_name;
        $array['layout'] = $layout;
        $array['module'] = $module;
        $array['notification_id'] = $notification_id;
        $array['form_name'] = $form_name;
        // $array['showstep'] = $showstep ;
        return $array;
        // return view('document.index',compact('type','id','doctype','doctype_id','doctype_name','layout','module','notification_id','form_name'));

    }

    public function print(Request $request,$id){

        $request->id = $id;
        $doc_temp = Issue::find($id);
        $request->document_type_id  = $doc_temp->document_type->id;
       // dd($doc_temp->document_type);
        $docmain =  IssueMain::create($request);
        $document =  $docmain->show($id);
        $form_name = $docmain->form_name;
        //$document = Payrequest::find($id)->with('');
       // dd(Ultils::convert_number_to_words(12500.34));
       $info = array();
        // dd($docmain->getLayout());
       $total = 0;
       $info['list_file'] = array();
       $list_file = array();
      // dd($request);
       $info['form_name'] = $form_name !=""?$form_name:($document->document_type?$document->document_type->name:"");
       $info['layout'] = $docmain->getLayout() ;
       //Tổng số tiền cần thanh toán
        if($document->attachment_file){

            foreach ($document->attachment_file as $file) {
                $attached_file['name'] = $file->name ;
                $attached_file['value'] = "" ;
                array_push( $list_file,$attached_file);
            }
            // $total_file = count($document->attachment_file);

            // dd($total_file );
            // if($total_file  > 0){
            //     $attached_file['name'] = "Files:" ;
            //     $attached_file['value'] = $total_file ;
            //     array_push( $list_file,$attached_file);
            // }
        }
        $info['list_file']['file_attached']  =array();
        $info['list_file']['file_attached'] = $list_file;

        //Chứng từ đính kèm

        $readCurr = new ReadCurrency($document->amount,$document->currency);

        $info['amount'] = $document->amount;
        $info['amount_word'] = $readCurr->read_to_words() ;

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
        //Format theo tiền việt

        //dd($document->team->users);
        //Lấy danh sách chữ ký đã được ký
        $approve = ApproveMain::create(Ultils::$MODULE_REPORT, $id);
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


        return view('issue.report.index',compact('document','info','signs'));

    }
}
