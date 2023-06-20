<?php

namespace App\Repositories\work\workflow\define;

use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\Wlphase;
use App\Models\work\workflow\Wlworkflow;
use App\Repositories\work\workflow\runtime\WorkflowBase;
use App\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WlJobProcess
{

    protected $wljob;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $form_name;

    public function __construct($request)
    {
        $this->request = $request;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }

    public function index()
    {
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Wljob::query();

        try {

            if ($this->request->filled('wlphase_id')) {
                $query = $query->where('wlphase_id', $this->request->wlphase_id);
                $document = $query->orderBy('id', 'ASC')->get();
                $result['data'] =  $document;
            }
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }

        return $result;
    }
    protected function validate_store()
    {
        $fields = $this->request->all();

        $validator = Validator::make(
            $fields,
            [
                'wlphase_id' => 'required',
                'unique_name' => 'required'
            ],
            [
                'wlphase_id.required' => "wlphase_id không được rỗng",
                'unique_name.required' => "unique_name không được rỗng",
            ]
        );

        return $validator;
    }
    public function edit($id)
    {
        return Wljob::find($id);
    }
    public function store()
    {

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $fields = $this->request->all();


        $validator = $this->validate_store();

        $failed = $validator->fails();
        if ($failed) {

            $this->errors = $validator->errors();
        } else {
            try {
                DB::beginTransaction();
                $Wljob = Wljob::create($fields);

                DB::commit();
                $Wljob->datedue = $this->getDateDue($Wljob->id);

                return $Wljob->load(['user', 'assigning_user', 'dependencies', 'navigates', 'job_type']);
            } catch (\Throwable $th) {
                DB::rollback();
                dd($th);
                $this->errors = $th->getMessage();
                return null;
            }
        }

        return null;
    }
    public static function getDateDue($wljob_id)
    {
        //Ngày tới hạn sẽ tính từ lúc nào ?
        //1. Ngày qui trình bắt đầu
        //2. Ngày giai đoạn bắt đầu
        //3. Ngày tạo job

        $wljob = Wljob::find($wljob_id);

        $str = null;
        if ($wljob->date_expected != null) {
            $date = date_create($wljob->date_expected);


            // if($wljob->time_execution != null ){
            //    date_add($date,date_interval_create_from_date_string($wljob->time_execution. ' hours'));
            // }
            $str = date_format($date, "Y-m-d H:i:s");
            $str = str_replace("00:00:00", "", $str);
        }
        return  $str;
    }
    protected function validate_update($id)
    {
        $validator = Validator::make(
            $this->request->all(),
            [
                'name' => 'required',
                'unique_name' => 'required',
                'wlphase_id' => 'required',
            ],
            [
                'name.required' => "Tên không được rỗng",
                'unique_name.required' => "unique_name không được rỗng",
                'wlphase_id.required' => "wlphase_id không được rỗng",
            ]
        );

        return $validator;
    }
    public function update($id)
    {

        try {
            $current_user = Auth()->user();
            DB::beginTransaction();

            $fields = $this->request->all();

            $validator = $this->validate_store();
            $failed = $validator->fails();

            if ($failed) {
                $this->errors = $validator->errors();
            } else {
                if ($fields['is_assign_user_by_ref']) {
                    $fields['assign_user'] = null;
                }
                if ($fields['is_action_user_by_ref']) {
                    $fields['action_user'] = null;
                }

                $this->Wljob = Wljob::findOrFail($id);
                $this->Wljob->fill($fields);
                $this->Wljob->save();
            }

            DB::commit();
            $documentBase = new  WorkflowBase(new Request());
            $wlphase = Wlphase::find($this->Wljob->wlphase_id);

            $wlworkflow = Wlworkflow::find($wlphase->wlworkflow_id);
            $documentBase->wlworkflow = $wlworkflow;
            //Trả lại đúng định dạng của job
            $jobs = $documentBase->getJobReports($this->Wljob->wlphase_id, $current_user->id);
            $job = null;
            for ($i = 0; $i < count($jobs); $i++) {
                if ($jobs[$i]->id == $this->Wljob->id) {
                    $this->Wljob = $jobs[$i];
                    break;
                }
            }

            $this->Wljob->datedue = $this->getDateDue($this->Wljob->id);
            // $this->Wljob->reports = $job!=null?$job->reports:[];
            return $this->Wljob->load(['user', 'assigning_user', 'dependencies', 'navigates', 'job_type']);
        } catch (\Exception $e) {
            DB::rollback();
            //dd( $e->getMessage());
            $this->errors = $e->getMessage();
            return null;
        }
    }

    public function destroy($id)
    {
        try {

            $Wljob = Wljob::find($id);

            if (!$Wljob) {
                abort(404);
            }

            foreach ($Wljob->wldataobjects as $value) {
                $wldataobject = new DataObjectProcess(new Request());
                $wldataobject->destroy($value->id);
                // $value->delete();
            }
            $Wljob->delete();

            return true;
        } catch (\Throwable $th) {
            throw $th;
            $this->errors = $th->getMessage();

            return false;
        }
    }
}
