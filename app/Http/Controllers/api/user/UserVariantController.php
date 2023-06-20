<?php

namespace App\Http\Controllers\api\user;;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\shared\UserVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
class UserVariantController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $result = array();
        $result['data'] =  array();

        //dd($request->url);
        $list = UserVariant::where('url',$request->url)
                             ->where('user_id',Auth::user()->id)->get();

        $result['data'] = $list;
        $result['success'] = "1";
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
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;

        //dd( $request);
        $validator = Validator::make($request->all(), [
            'url' => 'required',
            'name' => 'required',
            'properties' => 'required',
            'save_filter' => 'required'

        ]);

        $failed = $validator->fails();
            //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $user = Auth::User();
                if ($user) {

                    $variant = UserVariant::where('user_id',$user->id)
                                            ->where('url',$request->url)->first();

                    if(!$variant )
                        $variant = new UserVariant;
                    $variant->user_id = $user->id;
                    $variant->url = $request->url;
                    $variant->name = $request->name;
                    $variant->save_filter = $request->save_filter;
                    $variant->properties = $request->properties  ;
                    //dd($variant);
                    $variant->save();
                     $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\shared\UserVariant  $userVariant
     * @return \Illuminate\Http\Response
     */
    public function show(UserVariant $userVariant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\shared\UserVariant  $userVariant
     * @return \Illuminate\Http\Response
     */
    public function edit(UserVariant $userVariant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\shared\UserVariant  $userVariant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserVariant $userVariant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\shared\UserVariant  $userVariant
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserVariant $userVariant)
    {
        //
    }
}
