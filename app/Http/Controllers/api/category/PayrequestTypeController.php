<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\payment\PayrequestType;
use App\Models\shared\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrequestTypeController extends Controller
{
    public function index()
    {
        $payrequestType = PayrequestType::with('teams')->paginate(10);

        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $payrequestType;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
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
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|max:4',
            'name' => 'required|max:255',

        ]);

        $failed = $validator->fails();
            //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = PayrequestType::create($request->all());
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
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payrequestType = PayrequestType::findOrFail($id);
        
        $result = array();
        $result['data'] =  array();
        $result['data'] = $payrequestType;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
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

        
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|max:4',
            'name' => 'required|max:255',

        ]);

        $failed = $validator->fails();

        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
                
               
            try {

                $payrequestType = PayrequestType::findOrFail($id);
                if ($payrequestType) {
                    $payrequestType->id =  $payrequestType->id;
                    $payrequestType->company_id = $request->input('company_id');
                    $payrequestType->name = $request->input('name');
                    
                    if($payrequestType->save())
                     $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get article
        $payrequestType = PayrequestType::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;

        $payrequestType->teams()->detach();
        if( $payrequestType->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
         
    }

    public function assignPayrequestTypeToTeams(Request $request){

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;

        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'team_id' => 'required',
        ]);
        $failed = $validator->fails();
        if($failed){
            $result['data']['errors']  = $validator->errors();
        }else{
            try{
                $payrequestType = PayrequestType::find($request->id);
               
                if($payrequestType){
                    $payrequestType->teams()->detach();
                    if($request->team_id != -1){
                        $team = Team::find($request->team_id);
                        $payrequestType->teams()->save($team,['user_id'=>auth()->user()->id]);
                    }
                    $result['data']['success']  = 1;
                }

            }catch(\Exception $e){
                $result['data']['errors']  =  $e->getMessage();
            }
            
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
        
    }
}
