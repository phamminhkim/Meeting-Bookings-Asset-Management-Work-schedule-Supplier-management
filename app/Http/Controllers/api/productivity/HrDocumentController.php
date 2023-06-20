<?php

namespace App\Http\Controllers\api\productivity;

use App\Http\Controllers\Controller;
use App\Models\productivity\HrproductivityDoc;
use App\Models\productivity\HrproductivityMark;

use App\Models\shared\DocumentType;
use App\Models\shared\Party;
use App\Models\shared\Department;
use App\Models\shared\Group;
use App\Models\shared\Team;
use App\Models\productivity\HrconfigType;
use App\Models\productivity\HraddMark;
use App\Models\productivity\HrconfigPrice;
use App\Models\productivity\Hrdayoff;
use App\Models\productivity\HrproductivityStaff;
use App\Models\productivity\HrsalaryInfo;
use App\Models\shared\ApprovedRouting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use App\Repositories\DocumentSerials;
use App\Ultils\Ultils;
use App\Repositories\approve\ApproveRoutingHelper;

class HrDocumentController extends Controller
{
    public function show($id)
    {
        $result = array();
        $result['data'] = array();

        $document = HrproductivityDoc::find($id);
        if ($document) {
            $query = HrproductivityMark::where('record_type', $document->productivity_type)
                ->where('year', $document->year)
                ->where('month', $document->month);

            if ($document->productivity_type == 1) {
                $query = $query->where('party_id', $document->party_id);
            } else if ($document->productivity_type == 2) {
                $query = $query->where('department_id', $document->department_id);
            }
            $month_records = $query->get();

            $staff_array = array();
            foreach ($month_records as $record) {
                $staff_code = $record['staff_code'];
                if (!isset($staff_array[$staff_code])) {
                    $staff_array[$staff_code] = array();
                }
                if ($record['value']) {
                    $days_off = Hrdayoff::where('year',  $record->year)
                        ->where('month',  $record->month)
                        ->where('day',  $record->day)
                        ->where('staff_code', $staff_code)->first();

                    if (!$days_off) {
                        array_push($staff_array[$staff_code], $record['value']);
                    }
                }
            }

            $staff_array_final = array();
            foreach ($staff_array as $staff_code => $values) {
                $user = array();
                $user['staff_code'] = $staff_code;

                $total_mark = 0;
                $original_mark = 0;
                if ($document->productivity_type == 1) {
                    $query = HrproductivityStaff::query();
                    $existingProductivityDay = $query->where('staff_code', $staff_code)
                        ->where('year', $document->year)
                        ->where('month', $document->month)
                        ->first();

                    if ($existingProductivityDay) {
                        $total_mark = array_sum($values);
                        $total_days = $existingProductivityDay->totalday_calc;

                        $original_mark = round($total_mark / $existingProductivityDay->totalday_calc, 2);

                        $user['total_day'] = $total_days;
                    }
                } else if ($document->productivity_type == 2) {
                    $original_mark = $values[0];
                    $total_mark = $values[0];
                }

                $query = HraddMark::query();
                $additional_mark = $query->where('staff_code', $staff_code)
                    ->where('year', $document->year)
                    ->where('month', $document->month)
                    ->first();

                $final_mark = $original_mark;
                if ($additional_mark) {
                    $final_mark += $additional_mark->mark_delta;

                    $user['additional_mark'] = $additional_mark->mark_delta;
                    $user['update_mark_reason'] = $additional_mark->reason;
                    $user['update_mark_at'] = date("h:i d/m/Y", strtotime($additional_mark->updated_at));

                    $updated_by = User::find($additional_mark->user_id)->first();
                    if ($updated_by) {
                        $user['update_mark_by'] = $updated_by->name;
                    }
                }
                $final_mark = round($final_mark, 2);
                $original_rank = 'Chưa cấu hình';
                $final_rank = 'Chưa cấu hình';
                $ranks = HrconfigType::where('company_id', $document->company_id)->orderBy('from', 'desc')->get();

                foreach ($ranks as $rank) {
                    if ($original_mark >= $rank->from && $original_mark <= $rank->to) {
                        $original_rank = $rank->value;
                    }
                    if ($final_mark >= $rank->from && $final_mark <= $rank->to) {
                        $final_rank = $rank->value;
                    }
                }

                $user['total_mark'] = $total_mark;
                $user['original_mark'] = $original_mark;
                $user['final_mark'] = $final_mark;
                $user['original_rank'] = $original_rank;
                $user['final_rank'] = $final_rank;

                array_push($staff_array_final, $user);
            }

            $document['records'] = $staff_array_final;

            $result['data'] = $document;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    protected function validate_store($request)
    {
        $fields = $request->all();
        $filter = $fields['filter'];
        if ($filter['record_type'] == 1) {
            $validator = Validator::make(
                $fields,
                [
                    'filter.party_id' => 'required',
                    'filter.year' => 'required',
                    'filter.month' => 'required',
                ],
                [
                    'filter.party_id.required' => "Tổ sản xuất không được rỗng",
                    'filter.year.required' => "Năm không được rỗng",
                    'filter.month.required' => "Tháng không được rỗng",
                ]
            );
            $group = $this->getGroup($filter['record_type'], $filter['party_id']);
            if (!$group) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('group', 'Chưa cấu hình Group mặc định cho tổ này.');
                });
            } else {
                $team = $this->getTeamByGroup($filter['record_type'], $group);
                if (!$team) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('team', 'Chưa cấu hình Cây duyệt mặc định cho tổ này.');
                    });
                }
            }
            return $validator;
        } else if ($filter['record_type'] == 2) {
            $validator = Validator::make(
                $fields,
                [
                    'filter.department_id' => 'required',
                    'filter.year' => 'required',
                    'filter.month' => 'required',
                ],
                [
                    'filter.department_id.required' => "Phòng ban không được rỗng",
                    'filter.year.required' => "Năm không được rỗng",
                    'filter.month.required' => "Tháng không được rỗng",
                ]
            );

            $group = $this->getGroup($filter['record_type'], $filter['department_id']);
            if (!$group) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('group', 'Chưa cấu hình Group mặc định cho phòng ban này.');
                });
            } else {
                $team = $this->getTeamByGroup($filter['record_type'], $group);
                if (!$team) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('team', 'Chưa cấu hình Cây duyệt mặc định cho phòng ban này.');
                    });
                }
            }
            return $validator;
        }
    }

    public function getGroup($record_type, $id)
    {
        $group = null;

        if ($record_type == 1) {
            $party = Party::findOrFail($id);
            if ($party) {
                $group = Group::where('company_id', $party->company_id)
                    ->where('department_id', $party->department_id)
                    ->where('workshop_id', $party->workshop_id)
                    ->where('party_id', $party->id)->first();
            }
        } else if ($record_type == 2) {
            $department = Department::findOrFail($id);

            if ($department) {
                $group = Group::where('company_id', $department->company_id)
                    ->where('department_id', $department->id)
                    ->where('workshop_id', null)
                    ->where('party_id', null)->first();
            }
        }

        return $group;
    }

    public function getTeamByGroup($record_type, $group)
    {
        $default_team_name = null;
        if ($record_type == 1) {
            $default_team_name = 'C' . $group->company_id . '_' . 'D' . $group->department_id . '_' . 'W' . $group->workshop_id . '_' . 'P' . $group->party_id . '_DefaultTeam';
        } else if ($record_type == 2) {
            $default_team_name = 'C' . $group->company_id . '_' . 'D' . $group->department_id . '_DefaultTeam';
        }

        $team = Team::where('name', $default_team_name)->first();
        return $team;
    }

    public function store(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = '0';

        $validator = $this->validate_store($request);
        $failed = $validator->fails();
        $fields =  $request->all();

        if ($failed) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                DB::beginTransaction();
                $filter = $fields['filter'];

                $documentType = DocumentType::where('code', 'PROD')->first();

                $group = null;
                $base_team = null;
                if ($filter['record_type'] == 1) {
                    $group = $this->getGroup($filter['record_type'], $filter['party_id']);
                } else if ($filter['record_type'] == 2) {
                    $group = $this->getGroup($filter['record_type'], $filter['department_id']);
                }
                $base_team = $this->getTeamByGroup($filter['record_type'], $group);

                $existingDocument = HrproductivityDoc::where('document_type_id', $documentType->id)
                    ->where('productivity_type', $filter['record_type'])
                    ->where('company_id', $group['company_id'])
                    ->where('department_id', $group['department_id'])
                    ->where('workshop_id', $group['workshop_id'])
                    ->where('party_id', $group['party_id'])
                    ->where('year', $filter['year'])
                    ->where('month', $filter['month'])->first();

                if ($existingDocument) {
                    $new_teamId = $this->getTeam($base_team->id, $existingDocument->team_id);

                    $result['data']['success'] = '1';
                    $result['data']['createNew'] = '0';
                    $result['data']['document_id'] = $existingDocument->id;

                    if ($documentType && $existingDocument->serial_num == null) {
                        $existingDocument->serial_num = DocumentSerials::getSerial(
                            Ultils::$MODULE_HR,
                            $documentType->code,
                            $existingDocument->company_id,
                            Ultils::$MODULE_FORMAT_SERIAL_NUMBER,
                            true
                        );
                    }
                    $existingDocument->status = 1;
                    $existingDocument->team_id = $new_teamId;
                    $existingDocument->save();
                } else {

                    $new_teamId = $this->getTeam($base_team->id, null);

                    $document = HrproductivityDoc::create([
                        'document_type_id' => $documentType['id'],
                        'company_id' => $group['company_id'],
                        'department_id' => $group['department_id'],
                        'workshop_id' => $group['workshop_id'],
                        'party_id' => $group['party_id'],
                        'year' => $filter['year'],
                        'month' => $filter['month'],
                        'group_id' => $group['id'],
                        'team_id' => $new_teamId,
                        'status' => 1,
                        'productivity_type' => $filter['record_type'],
                        'user_id' => auth()->user()->id,
                    ]);

                    if ($documentType) {
                        $document->serial_num = DocumentSerials::getSerial(
                            Ultils::$MODULE_HR,
                            $documentType->code,
                            $document->company_id,
                            Ultils::$MODULE_FORMAT_SERIAL_NUMBER,
                            true
                        );
                        $document->save();
                    }

                    $result['data']['success'] = '1';
                    $result['data']['createNew'] = '1';
                    $result['data']['document_id'] = $document->id;
                    DB::commit();
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                //dd( $th->getMessage());
                $result['data']['success'] = '0';

                $validator->after(function ($validator) use ($th) {
                    $validator->errors()->add('error', $th->getMessage());
                });
                $failed = $validator->fails();
                $result['data']['errors'] = $validator->errors();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    protected function getTeam($base_teamId, $current_teamId)
    {
        if ($current_teamId) {
            $current_team = Team::find($current_teamId);
            //cẩn thận - không xóa các team cấu hình - chỉ xóa các team auto
            if ($current_team && $current_team->name == '_AUTO') {
                $current_team->delete();
            }
        }

        $new_teamId = ApproveRoutingHelper::createTeamFrom($base_teamId);
        return $new_teamId;
    }

    public function final_data(Request $request)
    {
        $result = array();
        $result['data'] = array();

        $company_id = $request->company_id;
        $year = $request->year;
        $month = $request->month;

        $query = HrproductivityMark::where('company_id', $company_id)
            ->where('year', $year)
            ->where('month', $month);

        $month_records = $query->get();

        $staff_array = array();
        foreach ($month_records as $record) {
            $staff_code = $record['staff_code'];
            if (!isset($staff_array[$staff_code])) {
                $staff_array[$staff_code] = array();
            }

            $staff_array[$staff_code]['type'] = $record['record_type'];
            $staff_array[$staff_code]['company_id'] = $record['company_id'];
            $staff_array[$staff_code]['department_id'] = $record['department_id'];
            $staff_array[$staff_code]['workshop_id'] = $record['workshop_id'];
            $staff_array[$staff_code]['party_id'] = $record['party_id'];

            if ($record['value']) {
                if (!isset($staff_array[$staff_code]['values'])) {
                    $staff_array[$staff_code]['values'] = array();
                }

                if ($record['record_type'] == 1) {
                    $days_off = Hrdayoff::where('year',  $record->year)
                        ->where('month',  $record->month)
                        ->where('day',  $record->day)
                        ->where('staff_code', $staff_code)->first();

                    if (!$days_off) {
                        array_push($staff_array[$staff_code]['values'], $record['value']);
                    }
                } else {
                    $staff_array[$staff_code]['final_mark'] = $record['value'];
                }
            }
        }

        $staff_array_final = array();
        foreach ($staff_array as $staff_code => $data) {
            $user = array();
            $user['staff_code'] = $staff_code;

            $user['company_id'] = $data['company_id'];
            $user['department_id'] = $data['department_id'];
            $user['workshop_id'] = $data['workshop_id'];
            $user['party_id'] = $data['party_id'];

            $user['original'] = array();

            $total_mark = 0;
            $original_mark = 0;
            if ($data['type'] == 1) {
                $day_to_calculate = 0;

                $existing_productivity_day = HrproductivityStaff::where('staff_code', $staff_code)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();

                if ($existing_productivity_day) {
                    $user['original']['total_day'] = $existing_productivity_day->totalday_calc;
                } else {
                    $user['original']['total_day'] = null;
                }

                $hr_productivity_day = HrsalaryInfo::where('staff_code', $staff_code)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();

                if ($hr_productivity_day) {
                    $hrupdate_day = array();

                    $hrupdate_day['total_day'] = $hr_productivity_day->totalday_calc;
                    $hrupdate_day['salary_day'] = $hr_productivity_day->totalday_salary;
                    $hrupdate_day['note'] = $hr_productivity_day->note;
                    $hrupdate_day['updated_at'] = $hr_productivity_day->updated_at;

                    $updated_by = User::find($hr_productivity_day->updated_by);
                    if ($updated_by) {
                        $hrupdate_day['updated_by'] = $updated_by->name;
                    }
                    $user['hrupdate_day'] = $hrupdate_day;
                }

                $day_to_calculate = null;
                if ($hr_productivity_day) {
                    $day_to_calculate = $hr_productivity_day->totalday_calc;
                } else if ($existing_productivity_day) {
                    $user['final_day'] = "Chưa có ngày tính lương";
                    //$day_to_calculate = $existing_productivity_day->totalday_calc;
                }
                $total_mark = isset($data['values']) ? array_sum($data['values']) : 0;

                if ($day_to_calculate) {
                    $original_mark = round($total_mark / $day_to_calculate, 2);

                    $user['final_day'] = $day_to_calculate;
                }
            } else if ($data['type'] == 2) {
                $original_mark = $data['final_mark'];
                $total_mark =  $data['final_mark'];
            }
            $user['original']['mark'] = $original_mark;


            $query = HraddMark::query();
            $additional_mark = $query->where('staff_code', $staff_code)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            $final_mark = $original_mark;
            if ($additional_mark) {
                $final_mark += $additional_mark->mark_delta;

                $hrupdate_mark = array();
                $hrupdate_mark['additional_mark'] = $additional_mark->mark_delta;
                $hrupdate_mark['update_reason'] = $additional_mark->reason;
                $hrupdate_mark['update_at'] = date("h:i d/m/Y", strtotime($additional_mark->updated_at));

                $updated_by = User::find($additional_mark->user_id)->first();
                if ($updated_by) {
                    $hrupdate_mark['update_by'] = $updated_by->name;
                }
                $user['hrupdate_mark'] = $hrupdate_mark;
            }
            $final_mark = round($final_mark, 2);

            $original_rank = 'Chưa cấu hình';
            $final_rank = 'Chưa cấu hình';
            $ranks = HrconfigType::where('company_id', $company_id)->orderBy('from', 'desc')->get();

            foreach ($ranks as $rank) {
                if ($original_mark >= $rank->from && $original_mark <= $rank->to) {
                    $original_rank = $rank->value;
                }
                if ($final_mark >= $rank->from && $final_mark <= $rank->to) {
                    $final_rank = $rank->value;
                }
            }

            $productivity_salary = 'Chưa cấu hình';
            $config_salary = HrconfigPrice::where('company_id', $company_id)->where('type_rank', $final_rank)->first();
            if ($config_salary) {
                if (isset($user['hrupdate_day'])) {
                    $productivity_salary = $config_salary->value * $user['hrupdate_day']['total_day'] /  $user['hrupdate_day']['salary_day'];
                    $productivity_salary = round($productivity_salary, -3);
                } else {
                    $final_mark = null;
                    $final_rank = null;
                    $productivity_salary = "Thiếu dữ liệu nhân sự";
                }
            }

            $user['original']['rank'] = $original_rank;

            $user['total_mark'] = $total_mark;
            $user['final_mark'] = $final_mark;
            $user['final_rank'] = $final_rank;
            $user['final_salary'] = $productivity_salary;

            array_push($staff_array_final, $user);
        }

        $document['records'] = $staff_array_final;

        $result['data'] = $document;

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
