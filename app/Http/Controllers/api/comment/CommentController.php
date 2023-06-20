<?php

namespace App\Http\Controllers\api\comment;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\shared\Comment;
use App\Repositories\comment\CommentMain;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    public function index(Request $request)
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $list = Comment::where('user_id', auth()->user()->id)->get();

        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function store(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;
        
        $commentBase = CommentMain::create($request);
      
        if ($commentBase) {
            
            $comment = $commentBase->store();
            if ($comment) {
                $result['data']['success'] = 1;
                $result['data']['comment'] = $comment;

            } else {
                $result['data']['errors'] = $commentBase->errors;
            }
        }

        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function show(Request $request, $id)
    {
        
        $comment = Comment::with('other_attacheds')->find($id);
        $result = array();
        $result['data'] = array();
        $result['data'] = $comment;
        $result['data']['success'] = 1;
        if (!$comment) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function edit(Request $request, $id)
    {
       
        $commentBase = CommentMain::create($request);
        $commentBase = $commentBase->edit($id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $commentBase;
        $result['data']['success'] = 1;
        if (!$commentBase) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {   

        //$this->authorize('update',Comment::find($id));

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;

        $commentBase = CommentMain::create($request);
        //dd($commentBase);
        $comment = $commentBase->update($id);
        if ($comment) {
            $result['data']['success'] = 1;
            $result['data']['comment'] = $comment;

        } else {
            $result['data']['errors'] = $commentBase->errors;
        }
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function destroy(Request $request, $id)
    {   

        //$this->authorize('delete',Comment::find($id));
      
        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;
        
        $commentBase = CommentMain::create($request);
        
        //dd($commentBase);
        $re = $commentBase->destroy($id);
        if ($re) {
            $result['data']['success'] = 1;
           

        } else {
            $result['data']['errors'] = $commentBase->errors;
        }
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    

}
