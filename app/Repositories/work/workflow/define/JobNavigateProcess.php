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
use App\Models\work\workflow\WlworkflowJobNavigate;
use App\Models\work\workflow\WlworkflowMember;
use App\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobNavigateProcess{

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
            'job_id' => 'required',
        ],
        [
            'job_id.required' => "Job ID không được rỗng",
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
                    $new_items = $fields['navigate_jobs_id'];
                    foreach ($this->wljob->navigates as $current_navigates) {
                        if (!in_array($current_navigates->navigate_job_id, $new_items)) {
                            $current_navigates->delete();
                        }
                    }
                    

                    foreach ($new_items as $item_id) {
                        $existing_item = WlworkflowJobNavigate::where('job_id', $id)->where('navigate_job_id', $item_id)->get();

                        if (count($existing_item) == 0) {
                            $new_navigate = array();
                            $new_navigate['job_id'] = $id;
                            $new_navigate['navigate_job_id'] = $item_id;
                            WlworkflowJobNavigate::create($new_navigate);
                        }
                    }
                }
                DB::commit();
                $this->wljob->load("navigates");
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
