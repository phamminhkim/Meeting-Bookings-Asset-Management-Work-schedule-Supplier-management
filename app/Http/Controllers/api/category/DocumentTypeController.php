<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\DocumentGroup;
use App\Models\shared\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentTypeController extends Controller
{
    public function index(Request $request)
    {
        // dd("tesst");
        if ($request->filled('module')) {
            $documentType = DocumentType::where('module', $request->module)->get();
        } else {
            $documentType = DocumentType::all();
            $documentType->load('document_group');
        }

        if ($request->filled('type')) {
            $listDocument = array();
            foreach ($documentType as $key => $document) {
                $item['label'] = $document->name;
                $item['id'] =  $document->id;
                array_push($listDocument, $item);
            }

            $documentType = $listDocument;
        }

        $result = array();
        $result['data'] = array();
        $result['data'] = $documentType;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function sub_menu(Request $request)
    {

        if ($request->filled('module')) {
            $documentTypes = DocumentType::where('module', $request->module)->get();
            $documentGroups = DocumentGroup::where('module', $request->module)->get();
        }
        $menus  = array();

        $item = array();
        $item_sub = array();
        foreach ($documentTypes as  $value) {
            if (!$value->document_group) {
                $item['name'] = $value->name;
                $item['code'] = $value->code;
                $item['id'] = $value->id;
                array_push($menus, $item);
            }
        }
        foreach ($documentGroups as $value) {

            if ($value->document_types && count($value->document_types) > 0) {
                $item['name'] = $value->name;
                $item['id'] = "";
                $item['code'] = "";
                $item['submenu'] = array();
                foreach ($value->document_types as $doc_type) {
                    $item_sub['name'] = $doc_type->name;
                    $item_sub['code'] = $doc_type->code;
                    $item_sub['id'] = $doc_type->id;
                    array_push($item['submenu'],  $item_sub);
                }
                array_push($menus, $item);
            }
        }

        $result = array();
        $result['data'] = array();

        $result['data'] = $menus;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function store(Request $request)
    {

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //dd( $request);
        $validator = Validator::make($request->all(), [
            'code' => 'required|min:4|max:4|unique:document_types',
            'name' => 'required|max:50',
            'private' => 'required',
            'approve_manual' => 'required',
            'active' => 'required',
        ]);

        $failed = $validator->fails();
        //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = DocumentType::create($request->all());
                if ($re) {

                    $result['data']  = $re;
                    // $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function show($id)
    {
        $documentType = DocumentType::find($id);
        $documentType->load('document_group');
        $result = array();
        $result['data'] =  array();
        $result['data'] = $documentType;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {


        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'code' => 'required|min:4|max:4',
            'name' => 'required|max:50',
            'private' => 'required',
            'approve_manual' => 'required',
            'active' => 'required',


        ]);

        $failed = $validator->fails();

        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $documentType = DocumentType::findOrFail($id);
                if ($documentType) {

                    $documentType->code = $request->input('code');
                    $documentType->name = $request->input('name');
                    $documentType->private = $request->input('private');
                    $documentType->approve_manual = $request->input('approve_manual');
                    $documentType->active = $request->input('active');
                    $documentType->document_group_id = $request->input('document_group_id');


                    if ($documentType->save()) {
                        $documentType->load('document_group');
                        $result['data']['documentType']  = $documentType;
                        $result['data']['success']  = 1;
                    }
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function destroy($id)
    {
        // Get article
        $documentType = DocumentType::find($id);

        $result = array();
        $result['data'] = array();
        $result['data']['success']  = '0';
        try {
            if ($documentType && $documentType->delete()) {
                $result['data']['success']  = '1';
            }
        } catch (\Exception $e) {
            $result['data']['errors']  = $e->getMessage();
        }
        // dd($result);
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
