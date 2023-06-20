<?php

namespace App\Repositories\work\workflow\define;

use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wldataobject;
use App\Models\work\workflow\WldataobjectItms;
use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\Wlphase;
use App\Models\work\workflow\Wlworkflow;
use App\Models\work\workflow\WlworkflowAdmin;
use App\Models\work\workflow\WlworkflowFollow;
use App\Models\work\workflow\WlworkflowMember;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PhaseProcess
{

    protected $wlphase;
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

        try {

            if ($this->request->filled('wlworkflow_id')) {
                $query = Wlphase::query();
                $query = $query->where('wlworkflow_id', $this->request->wlworkflow_id);

                $document = $query->orderBy('index', 'ASC')->with(['wljobs', 'wljobs.dependencies', 'wljobs.navigates', 'admins', 'members'])->get();
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
            $this->request->all(),
            [
                'name' => 'required',
            ],
            [
                'name.required' => "Tên không được rỗng",
            ]
        );

        return $validator;
    }
    public function show($id)
    {
        $document = Wlphase::with(['wljobs', 'wljobs.dependencies', 'wljobs.navigates', 'admins', 'members'])->find($id);
        return $document;
    }
    public function edit($id)
    {
        $document = Wlphase::with(['wljobs', 'wljobs.dependencies', 'wljobs.navigates', 'admins', 'members'])->find($id);

        return $document;
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
                $fields['unique_name'] = Ultils::name_to_unique_name($fields['name']);

                DB::beginTransaction();
                $phase = Wlphase::where('wlworkflow_id', $fields['wlworkflow_id'])
                    ->whereNotIn('index', [-999, 999])->orderBy('index', 'desc')->first();
                $index = 0;

                if ($phase != null) {
                    $index = $phase->index + 1;
                }
                $fields['index'] = $index;
                $wlphase = Wlphase::create($fields);

                DB::commit();
                return $wlphase;
            } catch (\Throwable $th) {
                DB::rollback();

                $this->errors = $th->getMessage();
                return null;
            }
        }

        return null;
    }
    protected function validate_update()
    {

        $validator = Validator::make(
            $this->request->all(),
            [

                'name' => 'required',
                'unique_name' => 'required',
            ],
            [
                'name.required' => "Tên không được rỗng",
                'unique_name.required' => "unique_name không được rỗng",

            ]
        );

        return $validator;
    }
    public function update($id)
    {

        try {

            DB::beginTransaction();

            $fields = $this->request->all();

            $validator = $this->validate_update();
            $failed = $validator->fails();

            if ($failed) {
                $this->errors = $validator->errors();
            } else {
                $this->wlphase = Wlphase::findOrFail($id);

                $this->wlphase->fill($fields);
                $this->wlphase->save();


                //lưu admin
                $admins = $fields['admin_values'];
                foreach ($this->wlphase->admins as $admin) {
                    $admin->delete();
                }
                foreach ($this->wlphase->members as $member) {
                    $member->delete();
                }
                if ($admins) {
                    for ($i = 0; $i < count($admins); $i++) {

                        $admin = new WlworkflowAdmin();
                        $admin->user_id = $admins[$i];
                        $this->wlphase->admins()->save($admin);
                    }
                }

                //USER MEMBERS
                $members = $fields['member_values'];
                if ($members) {
                    for ($i = 0; $i < count($members); $i++) {

                        $member = new WlworkflowMember();
                        $member->user_id = $members[$i];
                        $this->wlphase->members()->save($member);
                    }
                }

                //Lưu job

                $wljobs = $fields['wljobs'];
                for ($i = 0; $i < count($wljobs); $i++) {
                    $wljobs[$i]['wlphase_id'] = $this->wlphase->id;
                    if (isset($wljobs[$i]['id']) && $wljobs[$i]['id'] != 0) {
                        $wljob = Wljob::find($wljobs[$i]['id']);
                        if (!isset($wljobs[$i]['action_user'])) {
                            $wljobs[$i]['action_user'] = null;
                        }
                        $wljob->fill($wljobs[$i]);
                        $wljob->save();
                    } else {

                        $wljob = Wljob::create($wljobs[$i]);
                    }
                }
                $wljobs_del = $fields['wljobs_del'];
                for ($i = 0; $i < count($wljobs_del); $i++) {

                    if (isset($wljobs_del[$i]['id']) && $wljobs_del[$i]['id'] != 0) {
                        $wljob = Wljob::find($wljobs_del[$i]['id']);
                        if ($wljob) {
                            $wljob->delete();
                        }
                    }
                }
            }

            DB::commit();

            $this->wlphase = Wlphase::with(['wljobs', 'wljobs.dependencies', 'wljobs.navigates', 'admins', 'members'])->find($id);

            return $this->wlphase;
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

            $wlphase = Wlphase::find($id);
            foreach ($wlphase->admins as $value) {
                $value->delete();
            }
            foreach ($wlphase->members as $value) {
                $value->delete();
            }
            foreach ($wlphase->follows as $value) {
                $value->delete();
            }
            foreach ($wlphase->wljobs as $value) {
                $value->delete();
            }
            if (!$wlphase) {
                abort(404);
            }
            $wlphase->delete();

            return true;
        } catch (\Throwable $th) {
            throw $th;
            $this->errors = $th->getMessage();

            return false;
        }
    }
    public function updatePhaseOrders()
    {
        $list = $this->request->wlphases;

        $index = 0;

        for ($i = 0; $i < count($list); $i++) {
            if (isset($list[$i]['id']) && $list[$i]['id'] != '') {
                $item = Wlphase::find($list[$i]['id']);
                if ($item->index != 999 && $item->index != -999) {

                    $item->index = $index;
                    $item->save();
                    $index++;
                }
            }
        }
        return true;
    }
}
