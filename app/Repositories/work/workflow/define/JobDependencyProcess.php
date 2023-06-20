<?php
namespace App\Repositories\work\workflow\define;

use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wldataobject;
use App\Models\work\workflow\WldataobjectItms;
use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\WljobDependency;
use App\Models\work\workflow\Wlworkflow;
use App\Models\work\workflow\WlworkflowAdmin;
use App\Models\work\workflow\WlworkflowFollow;
use App\Models\work\workflow\WlworkflowMember;
use App\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobDependencyProcess{

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

    protected function validate_update($id)
    {
        $fields = $this->request->all();

        $validator = Validator::make($fields, [
            'jobid' => 'required',
        ],
        [
            'jobid.required' => "JobID không được rỗng",
        ]
        );
        return $validator;
    }
    public function update($id)
    {
        
        try {
            DB::beginTransaction();

            $fields = $this->request->all();

            $validator = $this->validate_update($id);
            $failed = $validator->fails();

            if ($failed) {
                $this->errors = $validator->errors();
            } else {
                $this->wljob = Wljob::findOrFail($id);
                
                if ($this->wljob) {
                    $newItems = $fields['dependjobids'];
                    foreach ($this->wljob->dependencies as $currentDepend) {
                        if (!in_array($currentDepend->dependjobid, $newItems)) {
                            $currentDepend->delete();
                        }
                    }
                    

                    foreach ($newItems as $newItemID) {
                        $existItem = WljobDependency::where('jobid', $id)->where('dependjobid', $newItemID)->get();

                        if (count($existItem) == 0) {
                            $newDependency = array();
                            $newDependency['jobid'] = $id;
                            $newDependency['dependjobid'] = $newItemID;
                            WljobDependency::create($newDependency);
                        }
                    }
                }
                DB::commit();
                $this->wljob->load("dependencies");
                return $this->wljob;
            }

            return null;
        } catch (\Exception $e) {
            DB::rollback();
            //dd( $e->getMessage());
            $this->errors = $e->getMessage();
            return null;

        }
    }
}
