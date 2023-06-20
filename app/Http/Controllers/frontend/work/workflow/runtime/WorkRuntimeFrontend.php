<?php

namespace App\Http\Controllers\frontend\work\workflow\runtime;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;

use App\Models\shared\Currency;
use App\Models\shared\DocumentType;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wlworkflow;
use App\Repositories\approve\ApproveMain;
use App\Repositories\document\DocumentBase;
use App\Repositories\work\workflow\runtime\WorkflowBase;
use App\Ultils\ReadCurrency;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
use App\User;

class WorkRuntimeFrontend extends BaseController
{
    public function index(Request $request)
    {
        $form_name = "";
        $type = $request->type;
        $id =   $request->id;
        $wlworkflow_id = $request->wlid;
        $current_workflow_code = $request->current_workflow_code;

        $document = WlworkflowDoc::find($id);

        $doctype = Ultils::$WORKFLOW_CODE_DEFAULT; // 'WLDC';// $request->doctype;// '//test'

        if ($document) {

            $documentType = DocumentType::where('id', $document->document_type->id)->first();

            if ($documentType) {
                $doctype = $documentType->code;
            }
        } else {
            $documentType = DocumentType::where('code', $doctype)->first();
        }

        switch ($type) {
            case 'view':
            case 'print':
                $this->authorize('view', WlworkflowDoc::findOrFail($id));
                break;
            case 'add':
                $this->authorize('create', [new WlworkflowDoc(), $current_workflow_code]);
                break;
            case 'edit':
                $this->authorize('update',  WlworkflowDoc::findOrFail($id));
                break;
            default:
                $this->authorize('view', [new WlworkflowDoc(), $current_workflow_code]);
                break;
        }

        $wlworkflow = null;

        $available_workflows = Wlworkflow::where('wlworkflow_type_id', $current_workflow_code)->where('type', 0)->where('active', 1)->get();
        $creatable_workflows = array();
        
        $interacting_user = User::find(auth()->user()->id);
        if ($interacting_user->hasWorkflowPermission($current_workflow_code, 'create')) {
            $creatable_workflows = Wlworkflow::where('wlworkflow_type_id', $current_workflow_code)->where('type', 0)->where('active', 1)->get();
        }

        if ($wlworkflow_id) {
            $wlworkflow = Wlworkflow::find($wlworkflow_id);
        } else {
            if ($document) {
                $wlworkflow = Wlworkflow::find($document->wlworkflow_id);
            }
        }

        if ($wlworkflow) {
            $current_workflow_code = $wlworkflow->wlworkflow_type_id;
            $wlworkflow_id = $wlworkflow->id;
        }

        $documentBase = new  WorkflowBase($request);
        $documentBase->wlworkflow = $wlworkflow;

        $documentBase->documentType = $documentType; //NHớ truyền để kiểm tra loại chứng từ xử lý
        $layout =  $documentBase->getLayout();
        $controls =  $documentBase->getInitialControls();

        $doctype_id = '';
        $doctype_name = '';

        if ($documentType) {
            $doctype_id = $documentType->id;
            $doctype_name = $documentType->name;
        }
        if ($wlworkflow) {
            $doctype_name = $wlworkflow->name;
        }
        $module = Ultils::$MODULE_WORKFLOW;
        $notification_id = $request->notification_id;

        $array = [];

        $array['type'] = $type;
        $array['wlworkflow_id'] = $wlworkflow_id;
        $array['id'] = $id;
        $array['doctype'] = $doctype;
        $array['current_workflow_code'] =  $current_workflow_code;
        $array['doctype_id'] = $doctype_id;
        $array['doctype_name'] = $doctype_name;

        $array['layout'] = $layout;
        $array['controls'] = $controls;
        $array['module'] = $module;
        $array['notification_id'] = $notification_id;
        $array['form_name'] = $form_name;
        $array['available_workflows'] = $available_workflows;
        $array['creatable_workflows'] = $creatable_workflows;

        return $array;
    }

    public function print(Request $request, $id)
    {


        //     $request->id = $id;
        //     $doc_temp = Document::find($id);
        //     $request->document_type_id  = $doc_temp->document_type->id;
        //    // dd($doc_temp->document_type);
        //     $docmain =  DocumentMain::create($request);
        //     $document =  $docmain->show($id);
        //     $form_name = $docmain->form_name;
        //     //$document = Payrequest::find($id)->with('');
        //    // dd(Ultils::convert_number_to_words(12500.34));
        //    $info = array();
        //     // dd($docmain->getLayout());
        //    $total = 0;
        //    $info['list_file'] = array();
        //    $list_file = array();
        //   // dd($request);
        //    $info['form_name'] = $form_name !=""?$form_name:($document->document_type?$document->document_type->name:"");
        //    $info['layout'] = $docmain->getLayout() ;
        //    //Tổng số tiền cần thanh toán
        //     if($document->attachment_file){

        //         foreach ($document->attachment_file as $file) {
        //             $attached_file['name'] = $file->name ;
        //             $attached_file['value'] = "" ;
        //             array_push( $list_file,$attached_file);
        //         }
        //         // $total_file = count($document->attachment_file);

        //         // dd($total_file );
        //         // if($total_file  > 0){
        //         //     $attached_file['name'] = "Files:" ;
        //         //     $attached_file['value'] = $total_file ;
        //         //     array_push( $list_file,$attached_file);
        //         // }
        //     }
        //     if($document->filesigns){

        //         foreach ($document->filesigns as $filesign) {
        //             if($filesign->attachment_file){

        //                 foreach ($filesign->attachment_file as $file) {
        //                     $attached_file['name'] = $file->name ;
        //                     $attached_file['value'] = "" ;
        //                     array_push( $list_file,$attached_file);
        //                 }

        //             }
        //         }
        //         // $total_file = count($document->attachment_file);

        //         // dd($total_file );
        //         // if($total_file  > 0){
        //         //     $attached_file['name'] = "Files:" ;
        //         //     $attached_file['value'] = $total_file ;
        //         //     array_push( $list_file,$attached_file);
        //         // }
        //     }

        //     $info['list_file']['file_attached']  =array();
        //     $info['list_file']['file_attached'] = $list_file;

        //     //Chứng từ đính kèm

        //     $currency = Currency::find($document->currency);
        //     $readCurr = new ReadCurrency($document->amount,$document->currency,"VN",$currency->unit,$currency->odd,$currency->and,$currency->twounit);

        //     $info['amount'] = $document->amount;
        //     $info['amount_word'] = $readCurr->read_to_words() ;

        //     $info['decimalpoint'] = ',';
        //     $info['separator'] = '.';
        //     if($document->currency == 'VND'){
        //         $info['decimal']  = 0;
        //     }
        //     else{
        //         $info['decimal']  = 2;
        //         // $info['decimalpoint'] = '.';
        //         // $info['separator'] = ',';

        //     }
        //     //Format theo tiền việt

        //     //dd($document->team->users);
        //     //Lấy danh sách chữ ký đã được ký
        //     $approve = ApproveMain::create(Ultils::$MODULE_REPORT, $id);
        //     $approve_info = $approve->get_info();
        //    // dd($approve->get_info());
        // //    dd( $approve_info);
        //    $signs = array();

        //    if(isset($approve_info['finished']) &&  $approve_info['finished'] == 'X'){

        //       foreach ($approve_info['steps'] as $step) {

        //         foreach ($step['users'] as  $user) {

        //             if($user->sign != null){
        //                // dd($user->sign);
        //                 $signs[$user->sign] = $user;
        //             }
        //         }


        //       }
        //    }


        return view('document.report.index', compact('document', 'info', 'signs'));
    }
}
