<?php

namespace App\Http\Controllers\api\issue;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\issue\Issue;
use App\Repositories\issue\IssueMain;
use Illuminate\Http\Request;

class IssueController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $this->authorize('view', new Issue());

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

           $result = array();
           $result['data'] = array();
           $result['success'] = "0";
           $documentBBase = IssueMain::create($request, '');

           $list = $documentBBase->index($request);
           if ($list) {
               $result['data'] = $list;
               $result['success'] = "1";
           }


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->authorize('create', new Issue());

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = '0';

             $documentBase = IssueMain::create($request);

             $documentModel = $documentBase->store();

             if ($documentModel) {
                 $result['data']['success'] = 1;

                 $result['data']  = $documentModel;

             } else {
                 $result['data']['errors'] = $documentBase->errors;
             }


        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $this->authorize('view',Issue::find($id));


            $documentBase = IssueMain::create($request);

            $documentModel = $documentBase->show($id);

            $result = array();
            $result['data'] =  array();
            $result['data'] = $documentModel;
            $result['data']['success']  = 1;
            if(!$documentModel){
                $result['data']['success']  = 0;
            }



        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $this->authorize('update', Issue::find($id));


                $documentBase = IssueMain::create($request);
                $documentModel = $documentBase->edit($id);

                $result = array();
                $result['data'] =  array();
                $result['data'] = $documentModel;
                $result['data']['success']  = 1;
                if(!$documentModel){
                $result['data']['success']  = 0;
                }



            return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

        $this->authorize('update', Issue::find($id));

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;


            $documentBase = IssueMain::create($request);
            $documentModel = $documentBase->update($id);

        if ($documentModel) {
            $result['data']['success'] = 1;
            $result['data']  = $documentModel;

        } else {
            $result['data']['errors'] = $documentBase->errors;
        }

        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);


    }
 //Cập nhật chứng huỷ
 public function update_cancel(Request $request){


    $this->authorize('update_cancel', Issue::find($request->id));

    $result = array();
    $result['data'] = array();
    $result['data']['success'] = 0;
        $documentBase = IssueMain::create($request, '');
        $re = $documentBase->update_cancel( );

    if ($re) {
        $result['data']['success'] = 1;
    }

    // dd("test class");
    return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id){

        $this->authorize('delete', Issue::find($id));
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;

            $documentBase = IssueMain::create($request);

            if ($documentBase->destroy($id)) {
                $result['data']['success']  = 1;
            }
             else{
                $result['data']['message']  = $documentBase->message;
            }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
