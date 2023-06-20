<?php

namespace App\Http\Controllers\api\productivity;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\productivity\HrsalaryInfo;
 
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HrSalaryInfoController extends  BaseController
{
    public function index(Request $request)
    {
        $this->authorize('view', new HrsalaryInfo());
       
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $query = HrsalaryInfo::query();
       
         
        if ( $request->filled('start_date')) {
            if (! $request->filled('end_date')) {
                $request->end_date = $request->start_date;
            }
            $start_date = Carbon::create( $request->start_date);
            $end_date = Carbon::create( $request->end_date);
            $end_date->addHours(23);
            $end_date->addMinutes(59);
            $end_date->addSeconds(59);

           // dd($start_date . "-" . $end_date);
            
            $query = $query->whereBetween('year', [$start_date->year, $end_date->year]);
            $query = $query->whereBetween('month', [$start_date->month, $end_date->month]);
      
        }

        if ( $request->filled('staff_id')) {
          // dd([$request->staff_id]);
           $staff_codes  = explode(',',$request->staff_id);
            
           $query = $query->whereIn('staff_code',$staff_codes);
          
        }
        $withModel = [ 'reminder','updated_by','employee'];
        $list= $query->orderBy('id', 'ASC')->with($withModel)->get();
        if ($list) {
            foreach ($list as $hrsalaryinfo) {
               
                $hrsalaryinfo->date_off = $hrsalaryinfo->year."-".  str_pad($hrsalaryinfo->month, 2, '0', STR_PAD_LEFT) ."-". str_pad($hrsalaryinfo->day, 2, '0', STR_PAD_LEFT) ;
            }
            $result['data'] = $list;            
            $result['success'] = "1";
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    protected function validate_store($request)
    {
        $validator = Validator::make($request->all(), [

          
            'staff_id' => 'required',  
            'totalday_calc' => 'required'  ,
            'totalday_salary' => 'required' ,   
            'year' => 'required'   , 
            'month' => 'required'    
        ],
        [
              
            'year.required' => "Năm không được rỗng",       
            'month.required' => "Tháng không được rỗng",       
            'staff_id.required' => "Nhân viên không được rỗng",       
            'totalday_calc.required' => "Số ngày tính lương năng suất không được rỗng",       
            'totalday_salary.required' => "Số ngày làm việc trong tháng không được rỗng",       
        ]
        );

        return $validator;

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new HrsalaryInfo());
        
        $user = new User();
        $user = auth()->user();

        $validator = $this->validate_store($request);
        $failed = $validator->fails();
        $fields =  $request->all();
        if ($failed) {
            return $this->sendError('Validation Error.', $validator->errors(),200);
        }else{
            try {
                $fields['updated_by'] = $user->id;
                $fields['staff_code'] = $fields['staff_id'];
                
                $hrsalary_info = HrsalaryInfo::create($fields);
                
                $hrsalary_info->load([ 'reminder','updated_by','employee']);
 
            } catch (\Throwable $th) {
                return $this->sendError('Save Error.', $th->getMessage(),200);
            }
        }
        return $this->sendResponse($hrsalary_info, 'HrsalaryInfo save successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hrsalaryinfo = HrsalaryInfo::find($id);  
       
        if (is_null($hrsalaryinfo)) {
            
            return $this->sendError('HrsalaryInfo not found.');
        }

        $this->authorize('view', $hrsalaryinfo);
       
        $hrsalaryinfo->load([ 'reminder','updated_by','employee']);
        $hrsalaryinfo->staff_id = $hrsalaryinfo->employee->employee_id;
       // $hrsalaryinfo->date_off = $hrsalaryinfo->year."-".  str_pad($hrsalaryinfo->month, 2, '0', STR_PAD_LEFT) ."-". str_pad($hrsalaryinfo->day, 2, '0', STR_PAD_LEFT) ;
       
        return $this->sendResponse($hrsalaryinfo, 'HrsalaryInfo retrieved successfully.');
    }
     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hrsalaryinfo = HrsalaryInfo::find($id);  
       
        if (is_null($hrsalaryinfo)) {
            
            return $this->sendError('HrsalaryInfo not found.');
        }

        $this->authorize('update', $hrsalaryinfo);
       
        $hrsalaryinfo->load([ 'reminder','updated_by','employee']);
        $hrsalaryinfo->staff_id = $hrsalaryinfo->employee->employee_id;
        //$hrsalaryinfo->date_off = $hrsalaryinfo->year."-".  str_pad($hrsalaryinfo->month, 2, '0', STR_PAD_LEFT) ."-". str_pad($hrsalaryinfo->day, 2, '0', STR_PAD_LEFT) ;
       
        return $this->sendResponse($hrsalaryinfo, 'HrsalaryInfo retrieved successfully.');
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = new User();
        $user = auth()->user();

        $validator = $this->validate_store($request);
        $failed = $validator->fails();
        $fields =  $request->all();
        if ($failed) {
            return $this->sendError('Validation Error.', $validator->errors(),200);
            // $result['data']['errors'] = $validator->errors();
        }else{
            try {
                $hrsalaryinfo = HrsalaryInfo::find($id);

                $this->authorize('update', $hrsalaryinfo);

                $fields['updated_by'] = $user->id;
                $fields['staff_code'] = $fields['staff_id'];
              

                $hrsalaryinfo->fill($fields);
                $hrsalaryinfo->save();
                
                $hrsalaryinfo->load([ 'reminder','updated_by','employee']);

                $hrsalaryinfo->staff_id = $hrsalaryinfo->employee->employee_id;
               // $hrsalaryinfo->date_off = $hrsalaryinfo->year."-".  str_pad($hrsalaryinfo->month, 2, '0', STR_PAD_LEFT) ."-". str_pad($hrsalaryinfo->day, 2, '0', STR_PAD_LEFT) ;
 
            } catch (\Throwable $th) {
                return $this->sendError('Save Error.', $th->getMessage(),200);
            }
        }
        return $this->sendResponse($hrsalaryinfo, 'HrsalaryInfo update successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $hrsalaryinfo = HrsalaryInfo::find($id);
            if (is_null($hrsalaryinfo)) {
            
                return $this->sendError('hrsalaryinfo not found.');
            }
            $this->authorize('delete', $hrsalaryinfo);

            $hrsalaryinfo->delete();
            return $this->sendResponse($hrsalaryinfo, 'hrsalaryinfo was deleted successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Delete Error.', $th->getMessage(),200);
        }
       
    }

    protected function validate_upload($request)
    {
        $fields = $request->all();
        $validator = Validator::make(
            $fields,
            [
                'data*.staff_code' => 'required',
                'data*.year' => 'required',
                'data*.month' => 'required',
                'data*.totalday_calc' => 'required',
                'data*.totalday_salary' => 'required',
            ],
            [
                'data*.staff_code.required' => "MSNV trong file không được rỗng",
                'data*.year.required' => "Ngày trong file không được rỗng",
                'data*.month.required' => "Tháng trong file không được rỗng",
                'data*.totalday_calc.required' => "Số ngày tính lương trong file không được rỗng",
                'data*.totalday_salary.required' => "Số ngày theo lịch trong file không được rỗng",
            ]
        );
        return $validator;
    }

    public function uploadData(Request $request)
    {
        $this->authorize('create', new HrsalaryInfo());
        $this->authorize('update', new HrsalaryInfo());
       
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = '0';

        $validator = $this->validate_upload($request);
        $failed = $validator->fails();
        $fields =  $request->all();
        if ($failed) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
              
                $totalCreatedCount = 0;
                $totalUpdatedCount = 0;
                DB::beginTransaction();
                $records = $fields['data'];
                $user = auth()->user();

                foreach ($records as $record) {
                    $year = $record['year'];
                    $month = $record['month'];

                    $existingRecord = HrsalaryInfo::where('staff_code', $record['staff_code'])
                        ->where('year', $year)
                        ->where('month', $month)
                        ->first();
                      
                    if ($existingRecord) {
                      
                        if (
                            $existingRecord['totalday_calc'] != $record['totalday_calc'] || 
                            $existingRecord['totalday_salary'] != $record['totalday_salary'] ||
                            $existingRecord['note'] != $record['note']
                        ) {
                            $existingRecord['totalday_calc'] = $record['totalday_calc'];
                            $existingRecord['totalday_salary'] = $record['totalday_salary'];
                            $existingRecord['note'] = $record['note'];
                            $existingRecord['updated_by'] = $user->id;
                            $existingRecord->save();
                          
                        }
                       
                        $totalUpdatedCount++;
                    } else {
                        $existingRecord = HrsalaryInfo::create([
                            'year' => $year,
                            'month' => $month,
                            'staff_code' => $record['staff_code'],
                            'totalday_calc' => $record['totalday_calc'],
                            'totalday_salary' => $record['totalday_salary'],
                            'note' => $record['note'],
                            'updated_by' => $user->id,
                        ]);

                        $totalCreatedCount++;
                    }
                }

               
                DB::commit();
                $result['data']['success'] = 1;
                $result['data']['createdCount'] = $totalCreatedCount;
                $result['data']['updatedCount'] = $totalUpdatedCount;
              
            } catch (\Throwable $th) {
                DB::rollBack();
                $result['error'] =  $th->getMessage();
            }
        }
      
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
