<?php

namespace App\Repositories\work\workflow\define;

use App\Models\shared\File;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wldataobject;
use App\Models\work\workflow\WldataobjectItms;
use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\Wlphase;
use App\Models\work\workflow\Wlworkflow;
use App\Models\work\workflow\WlworkflowAdmin;
use App\Models\work\workflow\WlworkflowFollow;
use App\Models\work\workflow\WlworkflowMember;
use App\Repositories\work\workflow\runtime\WorkflowBase;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;

class DataObjectProcess
{

    protected $wldataobject;
    protected $request;
    protected $errors;
    protected $message;

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
        try {
            $query = Wldataobject::query();
            if ($this->request->filled('wljob_id')) {
                $query = $query->where('wljob_id', $this->request->wljob_id);
            } else if ($this->request->filled('wlworkflow_id')) {
                $query = $query->where('wlworkflow_id', $this->request->wlworkflow_id);
                $query = $query->whereNull('wlphase_id');
                $query = $query->whereNull('wljob_id');
            } else {
                $query = $query->where('wlworkflow_id', $this->request->wlworkflow_id);
            }

            $objects = $query->orderBy('order')->with(['items'])->get();

            $list = array();
            foreach ($objects as $root) {
                if ($root->index_after_by == null || $root->index_after_by == "") {
                    array_push($list, $root);
                    $this->getTreeObj($root, $list);
                }
            }
            return $list;
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
            return null;
        }
    }

    public function getTreeObj($root, &$list)
    {


        if (count($root->childs) > 0) {

            foreach ($root->childs as $item) {
                $item->load('items');
                array_push($list, $item);
                if (count($item->childs) > 0) {
                    $this->getTreeObj($item, $list);
                }
            }
        }
    }
    //Kiểm tra node root thay đổi: không cho trỏ và các node con của nó
    public function validateRootChange($id, $index_after_by)
    {

        $node = Wldataobject::find($id);
        if ($node == null) {
            return true;
        }
        if ($id == $index_after_by) {
            return false;
        }
        $list = array();
        $this->getTreeObj($node, $list);
        foreach ($list as   $value) {

            if ($index_after_by == $value->id) {
                return false;
            }
        }

        return true;
    }


    protected function validate_store()
    {
        $fields = $this->request->all();

        $validator = Validator::make(
            $fields,
            [
                'name' => 'required',
                'items.*.name' => 'required'
            ],
            [
                'name.required' => "Tên không được rỗng",
                'items.*.name.required' => "Key không được rỗng",
            ]
        );

        return $validator;
    }

    public function store()
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $fields = $this->request->all();

        $validator = $this->validate_store();

        $failed = $validator->fails();

        if ($failed) {
            $this->errors = $validator->errors();
        } else {
            try {
                DB::beginTransaction();

                $data_object = Wldataobject::create($fields);
                if ($data_object) {
                    $sub_items = $fields['items'];
                    for ($i = 0; $i < count($sub_items); $i++) {
                        $sub_item = $sub_items[$i];
                        $sub_item['wldataobject_id'] = $data_object->id;

                        if (isset($sub_item['id']) && $sub_item['id'] != 0) {
                            $wldataobjectItm = WldataobjectItms::find($sub_item['id']);
                            $wldataobjectItm->fill($sub_item);
                            $wldataobjectItm->save();
                        } else {
                            $wldataobjectItm = WldataobjectItms::create($sub_items[$i]);
                        }
                    }
                    DB::commit();

                    $data_object->load("items");
                    return $data_object;
                }
                return null;
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
            ],
            [
                'name.required' => "Tên không được rỗng",
            ]
        );

        $fields = $this->request->all();
        //Kiểm tra không cho node root liên kết ngược tới các node con

        if (!$this->validateRootChange($fields['id'], $fields['index_after_by'])) {
            $validator->after(function ($validator) {

                $validator->errors()->add('motcap', 'Không thể gán node root vào con của nó');
            });
        }
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


                $this->wldataobject = Wldataobject::findOrFail($id);
                $fields['value'] =  $this->wldataobject->value;
                $fields['value_num'] =  $this->wldataobject->value_num;
                $fields['value_date'] =  $this->wldataobject->value_date;
                $this->wldataobject->fill($fields);

                $this->wldataobject->save();
                if ($this->wldataobject) {
                    if ($this->wldataobject->wldatatype_id != $fields['wldatatype_id']) {
                        foreach ($this->wldataobject->items as $item) {
                            $item->delete();
                        }
                    }

                    $items = $fields['items'];
                    for ($i = 0; $i < count($items); $i++) {
                        $items[$i]['wldataobject_id'] = $this->wldataobject->id;
                        if (isset($items[$i]['id']) && $items[$i]['id'] != "") {

                            $item = WldataobjectItms::find($items[$i]['id']);
                            $item->fill($items[$i]);
                            $item->save();
                        } else {
                            WldataobjectItms::create($items[$i]);
                        }
                    }
                    if (isset($fields['itemsRemoved'])) {
                        $itemsRemoved = $fields['itemsRemoved'];
                        for ($i = 0; $i < count($itemsRemoved); $i++) {
                            $item = WldataobjectItms::find($itemsRemoved[$i]['id']);
                            if ($item) {
                                $item->delete();
                            }
                        }
                    }
                }
                //Lưu job
                DB::commit();
                $this->wldataobject->load("items")->orderby('order');
                return $this->wldataobject;
            }

            return null;
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

            $wlphase = Wldataobject::find($id);

            if (!$wlphase) {
                abort(404);
            }
            //gán các node con lên node ông nội (nếu có)
            foreach ($wlphase->childs as $child) {
                $child->index_after_by = $wlphase->parent ? $wlphase->parent->id : null;
                $child->save();
            }
            foreach ($wlphase->items as $value) {

                $value->delete();
            }
            $wlphase->delete();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
