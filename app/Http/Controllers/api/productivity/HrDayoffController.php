<?php

namespace App\Http\Controllers\api\productivity;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\productivity\Hrdayoff;
use App\Models\shared\Employee;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HrDayoffController extends  BaseController
{
    public function index(Request $request)
    {
        $this->authorize('view', new Hrdayoff());

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $query = Hrdayoff::query();


        if ($request->filled('start_date')) {
            if (!$request->filled('end_date')) {
                $request->end_date = $request->start_date;
            }
            $start_date = Carbon::create($request->start_date);
            $end_date = Carbon::create($request->end_date);
            $end_date->addHours(23);
            $end_date->addMinutes(59);
            $end_date->addSeconds(59);

            // dd($start_date . "-" . $end_date);

            $query = $query->whereBetween('year', [$start_date->year, $end_date->year]);
            $query = $query->whereBetween('month', [$start_date->month, $end_date->month]);
            //$query = $query->whereBetween('day', [$start_date->day, $end_date->day]);

        }

        if ($request->filled('staff_id')) {
            // dd([$request->staff_id]);
            $staff_codes  = explode(',', $request->staff_id);
            $query = $query->whereIn('staff_code', $staff_codes);
        }
        $withModel = ['reminder', 'updated_by', 'employee'];
        $list = $query->orderBy('id', 'ASC')->with($withModel)->get();
        $newList = collect([]);
        if ($list) {
            foreach ($list as $hrdayoff) {

                $hrdayoff->date_off = $hrdayoff->year . "-" .  str_pad($hrdayoff->month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($hrdayoff->day, 2, '0', STR_PAD_LEFT);
                if (Carbon::create($hrdayoff->date_off) >= $start_date && Carbon::create($hrdayoff->date_off) <= $end_date) {
                    $newList->add($hrdayoff);
                }
            }
            $result['data'] = $newList;
            $result['success'] = "1";
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    protected function validate_store($request)
    {
        $validator = Validator::make(
            $request->all(),
            [


                'staff_id' => 'required',
                'date_off' => 'required',
                'reason' => 'required'
            ],
            [

                'staff_id.required' => "Nhân viên không được rỗng",
                'date_off.required' => "Ngày nghỉ không được rỗng",
                'reason.required' => "Lý do không được rỗng",
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
        $this->authorize('create', new Hrdayoff());

        $user = new User();
        $user = auth()->user();

        $validator = $this->validate_store($request);
        $failed = $validator->fails();
        $fields =  $request->all();
        if ($failed) {
            return $this->sendError('Validation Error.', $validator->errors(), 200);
            // $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $fields['updated_by'] = $user->id;
                $fields['staff_code'] = $fields['staff_id'];
                $day_off = Carbon::create($fields['date_off']);
                $fields['year'] = $day_off->year;
                $fields['month'] = $day_off->month;
                $fields['day'] = $day_off->day;

                $hrdayoff = Hrdayoff::create($fields);

                $hrdayoff->load(['reminder', 'updated_by', 'employee']);
                $hrdayoff->date_off = $hrdayoff->year . "-" .  str_pad($hrdayoff->month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($hrdayoff->day, 2, '0', STR_PAD_LEFT);
            } catch (\Throwable $th) {
                return $this->sendError('Save Error.', $th->getMessage(), 200);
            }
        }
        return $this->sendResponse($hrdayoff, 'HrDayoff save successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hrdayoff = Hrdayoff::find($id);

        if (is_null($hrdayoff)) {

            return $this->sendError('Hrdayoff not found.');
        }

        $this->authorize('view', $hrdayoff);

        $hrdayoff->load(['reminder', 'updated_by', 'employee']);
        $hrdayoff->staff_id = $hrdayoff->employee->employee_id;
        $hrdayoff->date_off = $hrdayoff->year . "-" .  str_pad($hrdayoff->month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($hrdayoff->day, 2, '0', STR_PAD_LEFT);

        return $this->sendResponse($hrdayoff, 'Hrdayoff retrieved successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hrdayoff = Hrdayoff::find($id);

        if (is_null($hrdayoff)) {

            return $this->sendError('Hrdayoff not found.');
        }

        $this->authorize('view', $hrdayoff);

        $hrdayoff->load(['reminder', 'updated_by', 'employee']);
        $hrdayoff->staff_id = $hrdayoff->employee->employee_id;
        $hrdayoff->date_off = $hrdayoff->year . "-" .  str_pad($hrdayoff->month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($hrdayoff->day, 2, '0', STR_PAD_LEFT);

        return $this->sendResponse($hrdayoff, 'Hrdayoff retrieved successfully.');
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
            return $this->sendError('Validation Error.', $validator->errors(), 200);
            // $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $hrdayoff = Hrdayoff::find($id);

                $this->authorize('create', $hrdayoff);

                $fields['updated_by'] = $user->id;
                $fields['staff_code'] = $fields['staff_id'];
                $day_off = Carbon::create($fields['date_off']);
                $fields['year'] = $day_off->year;
                $fields['month'] = $day_off->month;
                $fields['day'] = $day_off->day;

                $hrdayoff->fill($fields);
                $hrdayoff->save();

                $hrdayoff->load(['reminder', 'updated_by', 'employee']);

                $hrdayoff->staff_id = $hrdayoff->employee->employee_id;
                $hrdayoff->date_off = $hrdayoff->year . "-" .  str_pad($hrdayoff->month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($hrdayoff->day, 2, '0', STR_PAD_LEFT);
            } catch (\Throwable $th) {
                return $this->sendError('Save Error.', $th->getMessage(), 200);
            }
        }
        return $this->sendResponse($hrdayoff, 'HrDayoff update successfully.');
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
            $hrdayoff = Hrdayoff::find($id);
            if (is_null($hrdayoff)) {
                return $this->sendError('hrdayoff not found.');
            }

            $this->authorize('delete', $hrdayoff);
            
            $hrdayoff->delete();
            return $this->sendResponse($hrdayoff, 'hrdayoff was deleted successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Delete Error.', $th->getMessage(), 200);
        }
    }

    protected function validate_upload($request)
    {
        $fields = $request->all();
        $validator = Validator::make(
            $fields,
            [
                'data*.staff_code' => 'required',
                'data*.date_off' => 'required|date',
            ],
            [
                'data*.staff_code.required' => "MSNV trong file không được rỗng",
                'data*.date_off.required' => "Ngày nghỉ trong file không được rỗng",
            ]
        );
        return $validator;
    }

    public function uploadData(Request $request)
    {
        $this->authorize('create', new Hrdayoff());
        $this->authorize('update', new Hrdayoff());

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
                    $date_off = strtotime($record['date_off']);
                    $year = date("Y", $date_off);
                    $month = date("m", $date_off);
                    $day = date("d", $date_off);

                    $existingRecord = Hrdayoff::where('staff_code', $record['staff_code'])
                        ->where('year', $year)
                        ->where('month', $month)
                        ->where('day', $day)
                        ->first();

                    if ($existingRecord) {

                        if (
                            $existingRecord['reason'] != $record['reason']
                        ) {
                            $existingRecord['reason'] = $record['reason'];
                            $existingRecord['update_by'] = $user->id;
                            $existingRecord->save();
                        }

                        $totalUpdatedCount++;
                    } else {
                        $existingRecord = Hrdayoff::create([
                            'year' => $year,
                            'month' => $month,
                            'day' => $day,
                            'staff_code' => $record['staff_code'],
                            'reason' => $record['reason'],
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
