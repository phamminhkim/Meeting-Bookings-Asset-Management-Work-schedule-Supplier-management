<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\service\ServiceCategory;
use App\Models\shared\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
class ServiceCategoryController extends BaseController
{
    public function index()
    {
        $servicecat = ServiceCategory::with('teams')->paginate(10);

        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $servicecat;
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
            'name' => 'required|max:255',
        ]);

        $failed = $validator->fails();
            //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = ServiceCategory::create($request->all());
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
        $servicecat = ServiceCategory::findOrFail($id);
        
        $result = array();
        $result['data'] =  array();
        $result['data'] = $servicecat;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function update(Request $request, $id)
    {

        
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        $failed = $validator->fails();

        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
                
               
            try {

                $servicecat = ServiceCategory::findOrFail($id);
                if ($servicecat) {
                    
                    $servicecat->name = $request->input('name');
                   
                    if($servicecat->save())
                     $result['data']['success']  = 1;
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
        $servicecat = ServiceCategory::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        $servicecat->teams()->detach();
        if( $servicecat->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
         
    }
    public function assignServiceToTeams(Request $request){

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
                $servicecat = ServiceCategory::findOrFail($request->id);
               
                if($servicecat){
                    $servicecat->teams()->detach();
                    if($request->team_id != -1){
                        $team = Team::findOrFail($request->team_id);
                        $servicecat->teams()->save($team,['user_id'=>auth()->user()->id]);
                    }
                   

                   // dd($team);
                   
                    //$servicecat->teams()->save($team,['user_id'=>auth()->user()->id]);
                    $result['data']['success']  = 1;
                }

            }catch(\Exception $e){
                $result['data']['errors']  =  $e->getMessage();
            }
            
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
        
    }

}
