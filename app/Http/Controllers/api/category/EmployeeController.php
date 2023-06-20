<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\Workshop;
use App\Models\shared\Party;
use App\Models\shared\Employee;
use Illuminate\Http\Request;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeController extends BaseController
{
    public function canCUD()
    {
        $user = User::find(auth()->user()->id);
        return $user->hasPermission('config_employee');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = array();
        $result['data'] = array();

        $query = Employee::query();

        // if ($this->request->filled('date_in')) {
        //     if (!$this->request->filled('end_date')) {
        //         $this->request->end_date = $this->request->start_date;
        //     }
        //     $start_date = Carbon::create($this->request->start_date);
        //     $end_date = Carbon::create($this->request->end_date);
        //     $end_date->addHours(23);
        //     $end_date->addMinutes(59);
        //     $end_date->addSeconds(59);
        //     $query = $query->whereBetween('created_at', [$start_date, $end_date]);
        // }
        if ($request->filled('scopes') && $request->scopes != "undefined" &&  $request->scopes != "null") {
            $scopes = explode(",", $request->scopes);
            foreach ($scopes as $scope) {
                $key = substr($scope, 0, 1);
                $value = substr($scope, 1);

                if ($key == 'c') {
                    $query = $query->orWhere('company_id', $value);
                } else if ($key == 'd') {
                    $query = $query->orWhere('department_id', $value);
                } else if ($key == 'w') {
                    $query = $query->orWhere('workshop_id', $value);
                } else if ($key == 'p') {
                    $query = $query->orWhere('party_id', $value);
                }
            }
        }

        if ($request->filled('gender') && $request->gender != "undefined" &&  $request->gender != "null") {
            $query = $query->whereIn('gender', explode(",", $request->gender));
        }
        if ($request->filled('employee_type') && $request->employee_type != "undefined" &&  $request->employee_type != "null") {
            $query = $query->whereIn('employee_type', explode(",", $request->employee_type));
        }
        if ($request->filled('employment_type') && $request->employment_type != "undefined" &&  $request->employment_type != "null") {
            $query = $query->whereIn('employment_type', explode(",", $request->employment_type));
        }
        if ($request->filled('direct_type') && $request->direct_type != "undefined" &&  $request->direct_type != "null") {
            $query = $query->whereIn('direct_type', explode(",", $request->direct_type));
        }
        if ($request->filled('is_working')) {
            $query = $query->where('is_working', $request->is_working);
        }

        $employees = $query->get();

        if ($request->filled('type')) {
            $type = $request->filled('type');
            switch ($type) {
                case 'tree_combobox':
                    $result = array();

                    $companies = $employees->pluck('company_id')->flatten()->sort();
                    $departments = $employees->pluck('department_id')->flatten()->sort();
                    $workshops = $employees->pluck('workshop_id')->flatten()->sort();
                    $parties = $employees->pluck('party_id')->flatten()->sort();

                    $companies = array_unique($companies->toArray());
                    $departments = array_unique($departments->toArray());
                    $workshops = array_unique($workshops->toArray());
                    $parties =  array_unique($parties->toArray());

                    $companies = Company::whereIn('id', $companies)->get();
                    $departments = Department::whereIn('id', $departments)->get();
                    $workshops = Workshop::whereIn('id', $workshops)->get();
                    $parties = Party::whereIn('id', $parties)->get();

                    $list_companies = array();

                    foreach ($companies as $company) {
                        $item_company = array();
                        $item_company['id'] = "c" . $company->id;
                        $item_company['label'] = $company->name;
                        $item_company['children'] = array();
                        foreach ($departments as $department) {
                            if ($department->company_id == $company->id) {
                                $item_department = array();
                                $item_department['id'] = "d" . $department->id;
                                $item_department['label'] = $department->name;
                                $item_department['children'] = array();
                                foreach ($workshops as  $workshop) {
                                    if ($workshop->department_id == $department->id) {
                                        $item_workshop = array();
                                        $item_workshop['id'] = "w" . $workshop->id;
                                        $item_workshop['label'] = $workshop->name;
                                        $item_workshop['children'] = array();
                                        foreach ($parties as $party) {
                                            if ($party->workshop_id == $workshop->id) {
                                                $item_party = array();
                                                $item_party['id'] =  "p" . $party->id;
                                                $item_party['label'] = $party->name;
                                                $item_party['children'] = array();
                                                foreach ($employees as $employee) {
                                                    if ($employee->party_id == $party->id) {
                                                        $item_employee = array();
                                                        $item_employee['id'] = $employee->employee_id;
                                                        $item_employee['label'] = $employee->name.' ('.$employee->employee_id.')';
                                                        array_push($item_party['children'], $item_employee);
                                                    }
                                                }
                                                array_push($item_workshop['children'], $item_party);
                                            }
                                        }
                                        array_push($item_department['children'], $item_workshop);
                                    }
                                }
                                array_push($item_company['children'], $item_department);
                            }
                        }
                        array_push($list_companies, $item_company);
                    }

                    $employees = $list_companies;
                    break;
            }
        }

        $result['data'] = $employees;
        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function validate_store($fields)
    {
        $validator = Validator::make($fields, [
            'company_id' => 'required|min:4|max:4',
            'department_id' => 'required',
            'employee_id' => 'required|min:7|max:10',
            'name' => 'required|max:100',
            'gender' => 'required',
            'date_in' => 'required|date',
            'is_resign' => 'required|boolean'
        ]);

        $company = Company::find($fields['company_id']);
        if (!$company) {
            $validator->after(function ($validator) use ($fields) {
                $validator->errors()->add('company_id', 'Không tồn tại công ty với mã ' . $fields['company_id']);
            });
        }
        $department = Department::find($fields['department_id']);
        if (!$department) {
            $validator->after(function ($validator) use ($fields) {
                $validator->errors()->add('department_id', 'Không tồn tại phòng ban với mã ' . $fields['department_id']);
            });
        }
        if (isset($fields['workshop_id'])) {
            $workshop = Workshop::find($fields['workshop_id']);
            if (!$workshop) {
                $validator->after(function ($validator) use ($fields) {
                    $validator->errors()->add('workshop_id', 'Không tồn tại xưởng sản xuất với mã ' . $fields['workshop_id']);
                });
            }
            if (isset($fields['party_id'])) {
                $party = Party::find($fields['party_id']);
                if (!$party) {
                    $validator->after(function ($validator) use ($fields) {
                        $validator->errors()->add('party_id', 'Không tồn tại tổ sản xuất với mã ' . $fields['party_id']);
                    });
                }
            }
        } else {
            if (isset($fields['party_id'])) {
                $validator->after(function ($validator) use ($fields) {
                    $validator->errors()->add('party_id', 'Vui lòng chọn xưởng trước khi chọn tổ');
                });
            }
        }

        $employee = Employee::where('employee_id', $fields['employee_id'])->first();
        if ($employee) {
            $validator->after(function ($validator) use ($fields) {
                $validator->errors()->add('employee_id', 'Mã nhân viên ' . $fields['employee_id'] . ' đã tồn tại');
            });
        }

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
        if (!$this->canCUD()) {
            return $this->sendError("Không có quyền hạn", [], Response::HTTP_FORBIDDEN);
        } else {
            $fields = $request->all();
            $validator = $this->validate_store($fields);
            $is_failed = $validator->fails();

            if ($is_failed) {
                return $this->sendError("Dữ liệu không hợp lệ", $validator->errors(), Response::HTTP_BAD_REQUEST);
            } else {
                try {
                    $fields['is_working'] = !$fields['is_resign'];
                    if (!$fields['is_resign']) {
                        $fields['date_out'] = null;
                    }

                    $created_employee = Employee::create($fields);
                    if ($created_employee) {
                        return  $this->sendResponse($created_employee, "Tạo mới nhân viên thành công");
                    }
                } catch (\Exception $e) {
                    return $this->sendError($e->getMessage());
                }
            }
        }
    }

    public function validate_update($id, $fields)
    {
        $validator = Validator::make($fields, [
            'company_id' => 'required|min:4|max:4',
            'department_id' => 'required',
            'name' => 'required|max:100',
            'gender' => 'required',
            'date_in' => 'required|date',
            'is_resign' => 'required|boolean'
        ]);

        $company = Company::find($fields['company_id']);
        if (!$company) {
            $validator->after(function ($validator) use ($fields) {
                $validator->errors()->add('company_id', 'Không tồn tại công ty với mã ' . $fields['company_id']);
            });
        }
        $department = Department::find($fields['department_id']);
        if (!$department) {
            $validator->after(function ($validator) use ($fields) {
                $validator->errors()->add('department_id', 'Không tồn tại phòng ban với mã ' . $fields['department_id']);
            });
        }
        if (isset($fields['workshop_id'])) {
            $workshop = Workshop::find($fields['workshop_id']);
            if (!$workshop) {
                $validator->after(function ($validator) use ($fields) {
                    $validator->errors()->add('workshop_id', 'Không tồn tại xưởng sản xuất với mã ' . $fields['workshop_id']);
                });
            }
            if (isset($fields['party_id'])) {
                $party = Party::find($fields['party_id']);
                if (!$party) {
                    $validator->after(function ($validator) use ($fields) {
                        $validator->errors()->add('party_id', 'Không tồn tại tổ sản xuất với mã ' . $fields['party_id']);
                    });
                }
            }
        } else {
            if (isset($fields['party_id'])) {
                $validator->after(function ($validator) use ($fields) {
                    $validator->errors()->add('party_id', 'Vui lòng chọn xưởng trước khi chọn tổ');
                });
            }
        }

        $employee = Employee::where('id', $id)->where('employee_id', $fields['employee_id'])->first();
        if (!$employee) {
            $validator->after(function ($validator) use ($fields) {
                $validator->errors()->add('employee_id', 'Mã nhân viên ' . $fields['employee_id'] . ' không tồn tại');
            });
        }

        return $validator;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$this->canCUD()) {
            return $this->sendError("Không có quyền hạn", [], Response::HTTP_FORBIDDEN);
        } else {
            $fields = $request->all();
            $validator = $this->validate_update($id, $fields);
            $is_failed = $validator->fails();

            if ($is_failed) {
                return $this->sendError("Dữ liệu không hợp lệ", $validator->errors(), Response::HTTP_BAD_REQUEST);
            } else {
                try {
                    $existing_employee = Employee::find($id);
                    if ($existing_employee) {
                        $existing_employee->company_id = $fields['company_id'];
                        $existing_employee->department_id = $fields['department_id'];
                        $existing_employee->workshop_id = $fields['workshop_id'];
                        $existing_employee->party_id = $fields['party_id'];
                        $existing_employee->is_working = !$fields['is_resign'];
                        $existing_employee->name = $fields['name'];
                        $existing_employee->gender = $fields['gender'];
                        $existing_employee->date_in = $fields['date_in'];
                        $existing_employee->date_out = $fields['is_resign'] ? $fields['date_out'] : null;

                        if ($existing_employee->save()) {
                            return $this->sendResponse($existing_employee, "Cập nhật nhân viên thành công");
                        } else {
                            return $this->sendFailedWithStatusCode("Cập nhật nhân viên thất bại", Response::HTTP_NOT_MODIFIED);
                        }
                    } else {
                        return $this->sendFailedWithStatusCode("Không tìm thấy nhân viên", Response::HTTP_NOT_FOUND);
                    }
                } catch (\Exception $e) {
                    return $this->sendError($e->getMessage());
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$this->canCUD()) {
            return $this->sendError("Không có quyền hạn", [], Response::HTTP_FORBIDDEN);
        } else {
            $employee = Employee::find($id);

            if ($employee) {
                if ($employee->delete()) {
                    return $this->sendSuccess("Xóa nhân viên thành công");
                } else {
                    return $this->sendFailedWithStatusCode("Xóa nhân viên thất bại", Response::HTTP_NOT_MODIFIED);
                }
            } else {
                return $this->sendFailedWithStatusCode("Không tìm thấy nhân viên", Response::HTTP_NOT_FOUND);
            }
        }
    }

    protected function validate_upload($fields)
    {
        $validator = Validator::make($fields, [
            'data*.company_code' => 'required|min:4|max:4',
            'data*.department_code' => 'required',
            'data*.employee_id' => 'required|max:100',
            'data*.name' => 'required|max:100',
            'data*.gender' => 'required',
            'data*.date_in' => 'required|date',
            'data*.is_resign' => 'required|boolean'
        ]);

        $upload_companies = array_unique(array_column($fields['data'], 'company_code'));
        $exist_companies = Company::all()->pluck('name')->flatten()->toArray();
        $invalid_companies = array_diff($upload_companies, $exist_companies);
        if (count($invalid_companies) > 0) {
            $validator->after(function ($validator) use ($invalid_companies) {
                $validator->errors()->add('company_code', 'Không tồn tại công ty với mã ' . implode(", ", $invalid_companies));
            });
        }

        $upload_departments = array_unique(array_column($fields['data'], 'department_code'));
        $exist_departments = Department::all()->pluck('code')->flatten()->toArray();
        $invalid_departments = array_diff($upload_departments, $exist_departments);
        if (count($invalid_departments) > 0) {
            $validator->after(function ($validator) use ($invalid_departments) {
                $validator->errors()->add('department_code', 'Không tồn tại phòng ban với mã ' . implode(", ", $invalid_departments));
            });
        }

        $upload_workshops = array_unique(array_column($fields['data'], 'workshop_code'));
        $exist_workshops  = Workshop::all()->pluck('code')->flatten()->toArray();
        $invalid_workshops  = array_diff($upload_workshops, $exist_workshops);

        if (count($invalid_workshops) > 0) {
            $validator->after(function ($validator) use ($invalid_workshops) {
                $validator->errors()->add('workshop_code', 'Không tồn tại xưởng sản xuất với mã ' . implode(", ", $invalid_workshops));
            });
        }

        $upload_parties = array_unique(array_column($fields['data'], 'party_code'));
        $exist_parties = Party::all()->pluck('code')->flatten()->toArray();
        $invalid_parties = array_diff($upload_parties, $exist_parties);
        if (count($invalid_parties) > 0) {
            $validator->after(function ($validator) use ($invalid_parties) {
                $validator->errors()->add('party_code', 'Không tồn tại tổ sản xuất với mã ' . implode(", ", $invalid_parties));
            });
        }

        return $validator;
    }

    public function uploadData(Request $request)
    {
        if (!$this->canCUD()) {
            return $this->sendError("Không có quyền hạn", [], Response::HTTP_FORBIDDEN);
        } else {
            $fields = $request->all();
            $validator = $this->validate_upload($fields);
            $is_failed = $validator->fails();
            $is_failed = false;
            if ($is_failed) {
                return $this->sendError("Dữ liệu không hợp lệ", $validator->errors(), Response::HTTP_BAD_REQUEST);
            } else {
                $created_count = 0;
                $updated_count = 0;

                try {
                    DB::beginTransaction();
                    $employees = $fields['data'];

                    foreach ($employees as $employee) {
                        $existing_employee = Employee::where('employee_id', $employee['employee_id'])
                            ->first();

                        $company_id = Company::where('name', $employee['company_code'])->first()->id;
                        $department_id = Department::where('code', $employee['department_code'])->first()->id;
                        $workshop_id = isset($employee['workshop_code']) ? Workshop::where('code', $employee['workshop_code'])->first()->id : null;
                        $party_id = isset($employee['party_code']) ? Party::where('code', $employee['party_code'])->first()->id : null;
                        $date_in = date("Y-m-d", strtotime($employee['date_in']));
                        $is_resign = $employee['is_resign'] == "Không" ? false : true;
                        $date_out = $is_resign ? date("Y-m-d", strtotime($employee['date_out'])) : null;

                        if ($existing_employee) {
                            $existing_employee->company_id = $company_id;
                            $existing_employee->department_id = $department_id;
                            $existing_employee->workshop_id = $workshop_id;
                            $existing_employee->party_id = $party_id;
                            $existing_employee->employee_id = $employee['employee_id'];
                            $existing_employee->is_working = !$is_resign;
                            $existing_employee->name = $employee['name'];
                            $existing_employee->gender = $employee['gender'] == 'Nữ' ? 0 : 1;
                            $existing_employee->date_in = $date_in;
                            $existing_employee->date_out = $date_out;
                            $existing_employee->save();

                            $updated_count++;
                        } else {
                            $existing_employee = Employee::create([
                                'company_id' => $company_id,
                                'department_id' => $department_id,
                                'workshop_id' => $workshop_id,
                                'party_id' => $party_id,
                                'employee_id' => $employee['employee_id'],
                                'is_working' => !$is_resign,
                                'name' => $employee['name'],
                                'gender' => $employee['gender'] == 'Nữ' ? 0 : 1,
                                'date_in' => $date_in,
                                'date_out' => $date_out,
                            ]);

                            $created_count++;
                        }
                    }

                    DB::commit();

                    $result = array();
                    $result['created_count'] = $created_count;
                    $result['updated_count'] = $updated_count;
                    return $this->sendResponse($result, "Cập nhật nhân viên thành công");
                } catch (\Exception $e) {
                    DB::rollBack();
                    return $this->sendError($e->getMessage());
                }
            }
        }
    }
}
