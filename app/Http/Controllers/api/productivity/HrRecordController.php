<?php

namespace App\Http\Controllers\api\productivity;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\productivity\Hrdayoff;
use App\Models\productivity\HrproductivityMark;
use App\Models\shared\Department;
use App\Models\shared\Party;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use App\Models\productivity\HrproductivityDoc;
use App\Models\productivity\HrproductivityStaff;
use App\Models\productivity\HrsalaryInfo;
use App\Models\shared\Employee;
use Symfony\Component\HttpFoundation\Response;


class HrRecordController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', new HrproductivityMark());

        $result = array();
        $result['data'] = array();

        $record_type = $request->record_type;
        $year = $request->year;

        if ($record_type && $year) {
            $query = HrproductivityMark::where('record_type', $record_type)
                ->where('year', $year);

            if ($record_type == 1) {
                $query = $query->where('party_id', $request->party_id);
            } else if ($record_type == 2) {
                $query = $query->where('department_id', $request->department_id);
            }

            if ($request->filled('month') &&  $request->month != "null") {
                $month = $request->month;
                $query = $query->where('month', $month);

                $month_records = $query->get();

                $monthArray = $this->getMonthData($record_type, $year, $month, $request->department_id, $request->party_id, $month_records);
                if ($record_type == 1) {
                    $party = Party::findOrFail($request->party_id);
                    $monthArray['company_id'] = $party['company_id'];
                    $monthArray['department_id'] = $party['department_id'];
                    $monthArray['workshop_id'] = $party['workshop_id'];
                    $monthArray['party_id'] = $party['id'];
                } else if ($record_type == 2) {
                    $department = Department::findOrFail($request->department_id);
                    $monthArray['department_id'] = $department['id'];
                }
                $staff_array_final = array();

                if ($record_type == 1) {
                    $staff_array = array();
                    foreach ($month_records as $record) {
                        $user_id = $record['staff_code'];
                        if (!isset($staff_array[$user_id])) {
                            $staff_array[$user_id] = array();
                        }
                        $staff_array[$user_id][$record['day']] = $record['value'];
                    }
                    $days_off = Hrdayoff::where('year', $year)
                        ->where('month', $month)
                        ->whereIn('staff_code', array_keys($staff_array))->get()->toArray();

                    foreach ($staff_array as $staff_code => $values) {
                        $user = array();
                        $user['staff_code'] = $staff_code;

                        $user_days_off = array();
                        foreach ($days_off as $index => $day_off) {
                            if ($day_off['staff_code'] === $staff_code) {
                                $user_days_off[$day_off['day']] = $day_off;
                                unset($days_off[$index]);
                            }
                        }

                        foreach ($values as $day => $value) {
                            if (isset($user_days_off[$day]) && $value > 0) {
                                $user['d' . $day] = 'X';
                            } else {
                                $user['d' . $day] = $value;
                            }
                        }

                        $existing_productivity_day = HrproductivityStaff::where('staff_code', $staff_code)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->first();

                        $hr_productivity_day = HrsalaryInfo::where('staff_code', $staff_code)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->first();

                        if ($existing_productivity_day) {
                            $user['base_total_day'] = $existing_productivity_day->totalday_calc;
                        } else {
                            $user['base_total_day'] = null;
                        }

                        if ($hr_productivity_day) {
                            $user['hr_total_day'] = $hr_productivity_day->totalday_calc;
                            $user['hr_note'] = $hr_productivity_day->note;
                            $user['hr_total_day_updated_at'] = $hr_productivity_day->updated_at;

                            $updated_by = User::find($hr_productivity_day->updated_by);
                            if ($updated_by) {
                                $user['hr_total_day_updated_by'] = $updated_by->name;
                            }
                        } else {
                            $user['hr_total_day'] = null;
                        }

                        $user['days_off'] = $user_days_off;
                        $user['total_days_off'] = count($user_days_off);

                        array_push($staff_array_final, $user);
                    }
                } else if ($record_type == 2) {
                    $staff_array = array();
                    foreach ($month_records as $record) {
                        $user_id = $record['staff_code'];
                        if (!isset($staff_array[$user_id])) {
                            $staff_array[$user_id] = array();
                        }
                        $staff_array[$user_id]['value'] = $record['value'];
                        $staff_array[$user_id]['note'] = $record['note'];
                    }

                    foreach ($staff_array as $staff_code => $values) {
                        $user = array();
                        $user['staff_code'] = $staff_code;
                        $user['value'] = $values['value'];
                        $user['note'] = $values['note'];

                        array_push($staff_array_final, $user);
                    }
                }


                $monthArray['records'] = $staff_array_final;

                $result['data'] = $monthArray;
            } else {
                for ($month = 1; $month <= 12; $month++) {
                    $subquery = clone $query;
                    $subquery = $subquery->where('month', $month);

                    $monthRecords = $subquery->get();
                    $monthArray = $this->getMonthData($record_type, $year, $month, $request->department_id, $request->party_id, $monthRecords);

                    array_push($result['data'], $monthArray);
                }
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    function getMonthData($record_type, $year, $month, $department_id, $party_id, $monthRecords)
    {
        $countMonthRecords = count($monthRecords);

        $monthArray = array();
        $monthArray['id'] = $year . '-' . $month;
        $monthArray['record_type'] = $record_type;
        $monthArray['year'] = $year;
        $monthArray['month'] = $month;
        $monthArray['total_records'] = $countMonthRecords;
        $monthArray['total_users'] = count($monthRecords->unique('staff_code'));

        $monthArray['actions'] = $this->getAvailableAction($record_type, $year, $month, $department_id, $party_id, $countMonthRecords);
        $monthArray['status'] = $this->getRecordStatus($record_type, $year, $month, $department_id, $party_id, $countMonthRecords);
        $mostRecent = $countMonthRecords > 0 ? $monthRecords->max('updated_at') : null;
        $last_update_by = $countMonthRecords > 0 ? $monthRecords->first() : null;
        $monthArray['last_updated'] = $mostRecent ? date("H:i d/m/Y", strtotime($mostRecent)) : null;

        if ($last_update_by) {
            $user = User::find($last_update_by->update_by);
            $monthArray['last_updated_by'] = $user;
        }


        return $monthArray;
    }

    function getAvailableAction($record_type, $year, $month, $department_id, $party_id, $total_records)
    {
        $existingDocument = $this->getDocument($record_type, $year, $month, $department_id, $party_id);

        $actions = array();
        $actions['view_data'] = $total_records > 0;

        $actions['update_data'] = $this->checkUploadData($existingDocument, $year, $month);

        if ($existingDocument) {
            $actions['view_document'] = true;
            $actions['view_document_id'] = $existingDocument->id;
        } else {
            $actions['view_document'] = false;
        }
        if (!$existingDocument) //Chưa xuất chứng từ
        {
            $actions['export_data'] =  $total_records > 0;
        } else if (
            $existingDocument->status == 1 || //Đã nhập dữ liệu
            $existingDocument->status == 3
        ) //Phản hồi
        {
            $actions['export_data'] = true;
        } else {
            $actions['export_data'] = false;
        }

        // Check thêm các quyền liên quan
        return $actions;
    }

    function getRecordStatus($record_type, $year, $month, $department_id, $party_id, $total_records)
    {
        if ($total_records > 0) {
            $existingDocument = $this->getDocument($record_type, $year, $month, $department_id, $party_id);

            if ($existingDocument) {
                return $existingDocument->status;
                //2: Đã gửi duyệt
                //3: Phản hồi
                //4: Đã duyệt
                //5: Đã khóa
            }
            return 1; //Đã nhập dữ liệu
        } else {
            return 0; //Chưa có dữ liệu
        }


        return $total_records > 0 ? 1 : 0;;
    }

    function checkUploadData($existingDocument, $year, $month)
    {
        $currentYear = date("Y");
        $currentMonth = date("m");
        if ($year > $currentYear) { //Up dữ liệu cho tương lai thì chặn
            return false;
        } else if ($currentYear == $year) { //Nếu up trong năm
            // Up dữ liệu cho tháng lớn hơn tháng hiện tại thì chặn
            if ($month > $currentMonth) {
                return false;
            }
        }
        if (
            $existingDocument &&
            ($existingDocument->status == 2 || //Đã gửi duyệt
                $existingDocument->status == 4 || //Đã duyệt
                $existingDocument->status == 5)
        ) { //Đã hoàn thành
            return false;
        }
        return true;
    }

    function getDocument($record_type, $year, $month, $department_id, $party_id)
    {
        $existingDocument = HrproductivityDoc::where('productivity_type', $record_type)
            ->where('year', $year)
            ->where('month', $month);
        if ($record_type == 1) {
            $existingDocument = $existingDocument->where('party_id', $party_id);
        } else if ($record_type == 2) {
            $existingDocument = $existingDocument->where('department_id', $department_id);
        }
        return $existingDocument->first();
    }

    protected function validate_store($request)
    {
        $fields = $request->all();
        if ($fields['filter']['record_type'] == 1) {
            $validator = Validator::make(
                $fields,
                [
                    'filter.party_id' => 'required',
                    'filter.year' => 'required',
                    'filter.month' => 'required',
                    'data*.staff_code' => 'required|unique',
                ],
                [
                    'filter.party_id.required' => "Tổ sản xuất không được rỗng",
                    'filter.year.required' => "Năm không được rỗng",
                    'filter.month.required' => "Tháng không được rỗng",
                    'data*.staff_code.required' => "MSNV trong file không được rỗng",
                    'data*.staff_code.unique' => "Cập nhật trùng 2 mã nhân viên",
                ]
            );
            return $validator;
        } else if ($fields['filter']['record_type'] == 2) {
            $validator = Validator::make(
                $fields,
                [
                    'filter.department_id' => 'required',
                    'filter.year' => 'required',
                    'filter.month' => 'required',
                    'data*.staff_code' => 'required|unique',
                ],
                [
                    'filter.department_id.required' => "Phòng ban không được rỗng",
                    'filter.year.required' => "Năm không được rỗng",
                    'filter.month.required' => "Tháng không được rỗng",
                    'data*.staff_code.required' => "MSNV trong file không được rỗng",
                    'data*.staff_code.unique' => "Cập nhật trùng 2 mã nhân viên",
                ]
            );
            return $validator;
        }
    }

    public function store(Request $request)
    {
        $this->authorize('update', new HrproductivityMark());

        $validator = $this->validate_store($request);
        $failed = $validator->fails();
        $fields =  $request->all();
        if ($failed) {
            return $this->sendError("Dữ liệu không hợp lệ", $validator->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            try {
                // BỔ SUNG: CHECK TRẠNG THÁI CHỨNG TỪ HOẶC ĐIỂM ĐÃ KHÓA HAY CHƯA TRƯỚC KHI UPLOAD
                // BỔ SUNG: CHECK CÔNG TY, PHÒNG BAN, XƯỞNG, TỔ CỦA TỪNG NGƯỜI ĐƯỢC NHẬP ĐỂ TRÁNH VƯỢT QUYỀN
                // BỔ SUNG: CẬP NHẬT TỔNG SỐ NGÀY NĂNG SUẤT TỪ FIELD (TOTALNSDAY TRONG DATA) VÀO BẢNG TỔNG KẾT THÁNG
                // BỔ SUNG: CẬP NHẬT NOTE TỪ FIELD (NOTE TRONG DATA) VÀO BẢNG TỔNG KẾT THÁNG (QC)
                //// Không cần thiết - BỔ SUNG: KIỂM TRA QUYỀN HẠN CỦA NGƯỜI NHẬP ĐỂ XÁC ĐỊNH PHẠM VI NHẬP DỮ LIỆU
                $totalCreatedCount = 0;
                $totalUpdatedCount = 0;
                $totalDeleteCount = 0;
                DB::beginTransaction();
                $records = $fields['data'];
                $filter = $fields['filter'];
                $user = auth()->user();

                $record_type = $filter['record_type'];
                $year = $filter['year'];
                $month = $filter['month'];
                $department_id = isset($filter['department_id']) ? $filter['department_id'] : null;
                $party_id = isset($filter['party_id']) ? $filter['party_id'] : null;

                if ($record_type == 1) {
                    $party = Party::findOrFail($party_id);

                    $totalDeleteCount = HrproductivityMark::where('record_type', $record_type)
                        ->where('party_id', $party_id)
                        ->where('year', $year)
                        ->where('month', $month)
                        ->delete();

                    foreach ($records as $record) {
                        $employee = Employee::where('employee_id', $record['staff_code'])->where('party_id', $party_id)->first();
                        if ($employee) {
                            //Up số ngày năng suất
                            $existingProductivityDay = HrproductivityStaff::where('staff_code', $record['staff_code'])
                                ->where('year', $year)
                                ->where('month', $month)
                                ->first();

                            if ($existingProductivityDay) {
                                if ($existingProductivityDay['totalday_calc'] != $record['totalday_calc']) {
                                    $existingProductivityDay['totalday_calc'] = $record['totalday_calc'];
                                    $existingProductivityDay['update_by'] = $user->id;
                                    $existingProductivityDay->save();

                                    $totalUpdatedCount++;
                                }
                            } else {
                                $existingProductivityDay = HrproductivityStaff::create([
                                    'year' => $year,
                                    'month' => $month,
                                    'staff_code' => $record['staff_code'],
                                    'totalday_calc' => $record['totalday_calc'],
                                    'update_by' => $user->id
                                ]);
                            }

                            for ($day = 1; $day <= 31; $day++) {

                                $existingRecord = HrproductivityMark::where('record_type', $record_type)
                                    ->where('party_id', $party_id)
                                    ->where('year', $year)
                                    ->where('month', $month)
                                    ->where('day', $day)
                                    ->where('staff_code', $record['staff_code'])
                                    ->first();

                                if ($existingRecord) {
                                    if ($existingRecord['value'] != $record['d' . $day]) {
                                        $existingRecord['value'] = $record['d' . $day];
                                        $existingRecord->save();

                                        $totalUpdatedCount++;
                                    }
                                } else {
                                    $existingRecord = HrproductivityMark::create([
                                        'record_type' => $record_type,
                                        'company_id' => $party['company_id'],
                                        'department_id' => $party['department_id'],
                                        'workshop_id' => $party['workshop_id'],
                                        'party_id' => $party['id'],
                                        'year' => $year,
                                        'month' => $month,
                                        'day' => $day,
                                        'staff_code' => $record['staff_code'],
                                        'value' => $record['d' . $day],
                                        'update_by' => $user->id,
                                        'date' => Carbon::createFromDate($filter['year'], $filter['month'], $day)
                                    ]);

                                    $totalCreatedCount++;
                                }
                            }
                        } else {
                            return $this->sendFailedWithMessage("Công nhân có mã số: " . $record['staff_code'] . " không thuộc tổ " . $party['name']);
                        }
                    }
                } else if ($filter['record_type'] == 2) {
                    $department = Department::findOrFail($filter['department_id']);

                    $totalDeleteCount = HrproductivityMark::where('record_type', $record_type)
                        ->where('department_id', $department_id)
                        ->where('year', $year)
                        ->where('month', $month)
                        ->delete();

                    foreach ($records as $record) {
                        $employee = Employee::where('employee_id', $record['staff_code'])->where('party_id', $party_id)->first();
                        if ($employee) {
                            $existingRecord = HrproductivityMark::where('record_type', $record_type)
                                ->where('department_id', $department_id)
                                ->where('year', $year)
                                ->where('month', $month)
                                ->where('staff_code', $record['staff_code'])
                                ->first();

                            if ($existingRecord) {
                                if (
                                    $existingRecord['value'] != $record['avgpoint']
                                ) {
                                    $existingRecord['value'] = $record['avgpoint'];
                                    $existingRecord['update_by'] = $user->id;
                                    $existingRecord->save();
                                }
                                $totalUpdatedCount++;
                            } else {
                                $existingRecord = HrproductivityMark::create([
                                    'record_type' => $record_type,
                                    'company_id' => $department['company_id'],
                                    'department_id' =>  $department['id'],
                                    'workshop_id' => null,
                                    'party_id' => null,
                                    'year' => $year,
                                    'month' => $month,
                                    'day' => null,
                                    'staff_code' => $record['staff_code'],
                                    'value' => $record['avgpoint'],
                                    'update_by' => $user->id,
                                    'date' => Carbon::createFromDate($year, $month)
                                ]);

                                $totalCreatedCount++;
                            }
                        }  
                        else {
                            return $this->sendFailedWithMessage("Nhân viên có mã số: " . $record['staff_code'] . " không thuộc phòng ban " . $department['name']);
                        }
                    }
                }

                DB::commit();
                $result = array();
                $result['createdCount'] = $totalCreatedCount;
                $result['updatedCount'] = $totalUpdatedCount;
                $result['deletedCount'] = $totalDeleteCount;

                $query = HrproductivityMark::query();
                $query = $query->where('record_type', $record_type)
                    ->where('year', $year)
                    ->where('month', $month);
                if ($record_type == 1) {
                    $query = $query->where('party_id', $party_id);
                } else if ($record_type == 2) {
                    $query = $query->where('department_id', $department_id);
                }
                $monthRecords = $query->get();
                $monthArray = $this->getMonthData($record_type, $year, $month, $department_id, $party_id, $monthRecords);

                $result['updatedData'] = $monthArray;

                return $this->sendResponse($result, "Upload dữ liệu thành công");
            } catch (\Throwable $th) {
                DB::rollBack();
                return $this->sendError($th->getMessage());
            }
        }
    }
}
