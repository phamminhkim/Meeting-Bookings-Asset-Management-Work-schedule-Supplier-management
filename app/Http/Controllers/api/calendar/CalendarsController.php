<?php

namespace App\Http\Controllers\api\calendar;

use App\Http\Controllers\Controller;
use App\Models\calendar\Calendar;
use App\Models\calendar\CalendarDetail;
use App\Models\calendar\CalendarType;
use Carbon\Carbon;
use App\Ultils\Ultils;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;



class CalendarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query= Calendar::query()->with(['CalendarType','CalendarDetails','Company']);
        $result = array();
        

        $result['data'] = array();
        try{
            
            if($request->filled('year')){
                $query = $query->where('year', $request->year );
            }  
                      
            $calendars = $query->get();              
            $result['data'] = $calendars;
            $result['success'] = "1";
        }catch (Exception $e) {
            $result['data']['errors']  =  $e->getMessage();

        }
     
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function create()
    {
        $department = CalendarDetail::all();

        $department = Calendar::all();

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");   
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
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'company_id.required' => 'Công ty không được để trống',
            'calendar_type_id.required' => 'Loại không được để trống',
            'title.required' => 'Tiêu đề không được để trống',
            'year.required' => 'Năm không được để trống',

        ];
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'calendar_type_id' => 'required',
            'title' => 'required',
            'year' => 'required',

        ], $meesage);
       
        
        $failed = $validator->fails();
        $fields = $request->all();
        $isErr = false;
        if ($request->company_id) {

            $dep_temp = Calendar::where('company_id', $request->company_id)->first();

            if ($dep_temp) {
                $result['data']['message']  = "Trùng công ty, vui lòng nhập lại";
                $validator->errors()->add('company_id', 'Trùng công ty, vui lòng nhập lại');
                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
               
               
                $re = Calendar::create($fields);  
                $insertid = $re -> id;               
                if ($re) {
                    $calendars = $fields['calendar_details']; 
                    // dd( $fields['calendar_details']);
                    foreach ($calendars as $value) {
                        CalendarDetail::create([
                            'month' => $value['month'],
                            'year' => $value['year'],
                            'day' => $value['day'],
                            'work' => $value['work'],
                            'calendar_holiday_id' => $value['calendar_holiday_id'],
                            'calendar_id' => $insertid
                        ]);
                    }
                    // $result['data']  = $re;
                    //
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['success'] = 0;
                //  $validator->after(function ($validator) use($e) {
                   
                //     $validator->errors()->add('loisave',$e->getMessage());
                   
                // });

                // $failed = $validator->fails();
                 
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
       
        // $payRequestModel = Gooddocu::findOrFail($id)->with(['goods','GooddocusDetails'])->get();
        $payRequestModel = Calendar::with(['calendars','CalendarDetails','CalendarType'])->find($id);
        $result = array();
        $result['data'] =  array();
        $result['data'] = $payRequestModel;
        $result['data']['success']  = 1;
         if (!$payRequestModel) {
            $result['data']['success']  = 0;
        }

        // dd(Ultils::check_workday('1000','HC','2022','09','01'));
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

        $payRequestModel = Calendar::with(['CalendarDetails'])->find($id);
        // $payRequestModel = Calendar::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $payRequestModel;
        $result['data']['success']  = 1;
         if (!$payRequestModel) {
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
    public function update(Request $request, $id)
    {
        $payRequestModel = Calendar::with(['CalendarDetails'])->find($id);
       
        $result = array();
        $result['data'] = array();
        $result['data'] = $payRequestModel;
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'company_id.required' => 'Công ty không được để trống',
            'calendar_type_id.required' => 'Loại không được để trống',
            'title.required' => 'Tiêu đề không được để trống',
            'year.required' => 'Năm không được để trống',

        ];
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'calendar_type_id' => 'required',
            'title' => 'required',
            'year' => 'required',

        ], $meesage);
        
        $fields = $request->all();
      
        $failed = $validator->fails();
        
        $isErr = false;
        if($request->company_id){
            $dep_temp = Calendar::where('company_id', $request->company_id)->first();
             
            if($dep_temp && $dep_temp->id != $id ){
                $result['data']['message']  = "Trùng công ty, vui lòng nhập lại";
                $validator->errors()->add('company_id', 'Trùng công ty, vui lòng nhập lại');

                $isErr = true;
            }
        }
      
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {  
            try {
               
                $department = Calendar::with(['CalendarDetails'])->find($id);
                $insertid = $department -> id;
                

             
                if ($department) {
                    $department->id =  $department->id;
                    $department->calendar_type_id = $request->input('calendar_type_id');
                    $department->company_id= $request->input('company_id');
                    $department->description = $request->input('description');
                    $department->year= $request->input('year');
                    $department->title= $request->input('title');
                   
                    if($department->save())
                     $result['data']['success']  = 1;  
                    
                }
                if ($department){
                    $goods = $fields['calendar_details'];
                    $goods_del = $fields['calendar_details_del'];
                    // dd($goods );
                    foreach( $goods_del as $value){
                        if(isset($value['id']) && $value['id'] != '' ){
                            $goodDetail = CalendarDetail::find($value['id']);
                        if($goodDetail != null) {
                            $goodDetail->delete();  
                        }
                        }
                    }
                    foreach ($goods as $value) {
                       
                           
                        if (isset($value['id']) && $value['id'] != '' ){
                            // if($value['calendar_id'] === null) {
                            //     $value['id']=null;
                              
                              
                            // }  
                           
                            $gooddoc = CalendarDetail::find($value['id']);
                              
                            $gooddoc->fill($value);
                            $gooddoc->save();   

                               
                                                      
                        }else{
                            CalendarDetail::create([
                                     
                                        'month' => $value['month'],
                                        'year' => $value['year'],
                                        'day' => $value['day'],
                                        'work' => $value['work'],
                                        'calendar_holiday_id' => $value['calendar_holiday_id'],
                                        'calendar_id' => $insertid,
                                           
                                    ]);
                                   
                                   
                        };
                       
                    }
                    $result['data']['data']  = $department;
                };
              
           
                
            } catch (\Exception $e) {
               
              
                $result['data']['success'] = 0;
                 $validator->after(function ($validator) use($e) {
                   
                    $validator->errors()->add('loisave',$e->getMessage());
                   
                });

                $failed = $validator->fails();
                 
                $result['data']['errors']  = $validator->errors();
                
            }
        }

        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function destroy($id)
    {

        $payrequestBase = Calendar::with(['calendars','CalendarDetails'])->find($id);
        $result = array();
        $result['data'] =  array();
        // $result['data'] =   $payrequestBase;
        $result['data']['success'] = 0;

        try {
            
            DB::beginTransaction();
           
           
            foreach ($payrequestBase->CalendarDetails as $term) {

                $term->delete();
            }
        $result['data']['success']  = 1 ;
        $payrequestBase->delete();
        DB::commit();

    } catch (\Exception $e) {
        DB::rollback();
        $result['data']['errors'] = $e->getMessage();
    }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}

