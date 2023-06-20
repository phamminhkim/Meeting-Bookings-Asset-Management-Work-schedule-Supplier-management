<?php

namespace App\Http\Controllers\api\comment;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\shared\CommentVote;
use App\Repositories\commentvote\CommentVoteMain;
use Illuminate\Http\Request;

class CommentVoteController extends BaseController
{
    public function index(Request $request)
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $list = CommentVote::where('user_id', auth()->user()->id)->get();

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
        
        $commentvoteBase = CommentVoteMain::create($request);

        if ($commentvoteBase) {
           
            $comment = $commentvoteBase->store();
           
            if ($comment) {
                $result['data']['success'] = 1;
                $result['data']['comment'] = $comment;

            } else {
                $result['data']['errors'] = $commentvoteBase->errors;
            }
        }

    
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function show(Request $request, $id)
    {
        

    }
    public function edit(Request $request, $id)
    {
        $commentvoteBase = CommentVoteMain::create($request);
        $commentvoteBase = $commentvoteBase->edit($id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $commentvoteBase;
        $result['data']['success'] = 1;
        if (!$commentvoteBase) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);  

    }
    public function update(Request $request, $id)
    {   

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;

        $commentvoteBase = CommentVoteMain::create($request);
        //dd($commentBase);
        $commentvote = $commentvoteBase->update($id);
        if ($commentvote) {
            $result['data']['success'] = 1;
            $result['data']['comment'] = $commentvote;

        } else {
            $result['data']['errors'] = $commentvoteBase->errors;
        }
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function destroy(Request $request, $id)
    {   


    }
}
