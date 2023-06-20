<?php

namespace App\Http\Controllers\api\qa;

use App\Http\Controllers\Controller;
use App\Models\qa\QaQuestion;
use App\Models\qa\QaAnswer;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PageQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = QaAnswer::query()->with(['question.category','tag']);
        $result = array();
        $result['data'] = array();          
            try {
                if($request->filled('search')){
                    $query = $query->whereHas('question', function($q) use ($request){
                        $q->where('title', 'like', '%'.$request->search.'%')
                          ->orWhere('content', 'like', '%'.$request->search.'%');
                    });
                }
                if($request->filled('category_id')){
                    $query = $query->whereHas('question', function($q) use ($request){
                        $q->where('category_id', $request->category_id);
                    });
                }
                $pageQuestion = $query->get();              
                $result['data'] = $pageQuestion;
                $result['success'] = "1";
            } catch (Exception $e) {
                $result['data']['errors'] = $e->getMessage();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function show($id , $tag_id)
    {

        $answer = QaAnswer::with([
            'user.department','tag',
            'question.user',
        ])
        ->findOrFail($id);
        
        $result = array();
        $result['data'] =  array();
        $result['data'] = $answer;
        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
