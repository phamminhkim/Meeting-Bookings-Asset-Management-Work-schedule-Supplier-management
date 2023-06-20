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
use App\Models\work\workflow\WlworkflowConfig;
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

class WlConfigProcess
{
    protected $request;
    public $errors;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $fields = $this->request->all();


        try {
            $objectType = $fields['objectType'];
            $objectID = $fields['objectID'];
            $objectClass = $this->getObjectClass($objectType);

            $configs = $this->getConfigList($objectClass, $objectID);

            return $configs;
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
            return null;
        }
    }
    protected function validateUpdate()
    {
        $fields = $this->request->all();

        $validator = Validator::make(
            $fields,
            [
                'configs' => 'present|array',
                'configs.*.keyword' => 'required',
            ]
        );

        $activeCount = array_count_values(array_column($fields['configs'], 'active'))['1'];
        if ($activeCount > 1) {
            $validator->after(function ($validator) {
                $validator->errors()->add('onlyoneactive', 'Chỉ có thể chọn 1 bộ cấu hình tại 1 thời điểm');
            });
        }

        foreach ($fields['configs'] as $config) {
            $keywordCount = array_count_values(array_column($fields['configs'], 'keyword'))[$config['keyword']];
            if ($keywordCount > 1) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('duplicatekeyword', 'Trùng keyword');
                });
            }

            foreach ($config['options'] as $option) {
                if (!isset($option['key'])) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('emptyoptionkey', 'Option key bị rỗng');
                    });
                } else {
                    $keyCount = array_count_values(array_column($config['options'], 'key'))[$option['key']];
                    if ($keyCount > 1) {
                        $validator->after(function ($validator) {
                            $validator->errors()->add('duplicateoptionkey', 'Trùng option key');
                        });
                    }
                }
            }
        }

        return $validator;
    }
    public function update($objectID)
    {
        try {
            $fields = $this->request->all();

            $objectClass = null;

            DB::beginTransaction();

            $validator = $this->validateUpdate();
            $failed = $validator->fails();

            if ($failed) {
                $this->errors = $validator->errors();
            } else {

                foreach ($fields['configs'] as $config) {
                    $existingConfig = null;
                    if (isset($config['id'])) {
                        $existingConfig = WlworkflowConfig::find($config['id']);
                    }

                    if ($existingConfig) {
                        $existingConfig->keyword = $config['keyword'];
                        $existingConfig->options = json_encode($config['options']);
                        $existingConfig->active = $config['active'];
                        $existingConfig->save();

                        $objectClass = $existingConfig->objectable_type;
                    } else {
                        $newConfig = new WlworkflowConfig();
                        $newConfig->keyword = $config['keyword'];
                        $newConfig->objectable_id = $config['objectable_id'];
                        $newConfig->objectable_type = $this->getObjectClass($config['objectable_type']);
                        $newConfig->options = json_encode($config['options']);
                        $newConfig->active = $config['active'];
                        $newConfig->save();

                        $objectClass = $newConfig->objectable_type;
                    }
                }

                foreach ($fields['removeConfigs'] as $removedConfig) {
                    $item = WlworkflowConfig::find($removedConfig['id']);
                    if ($item) {
                        $item->delete();
                    }
                }
                //Lưu
                DB::commit();

                $configs = $this->getConfigList($objectClass, $objectID);

                return $configs;
            }
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;
        }
    }

    private function getConfigList($objectClass, $objectID)
    {
        $query = WlworkflowConfig::query();
        $query = $query->whereHasMorph('objectable', $objectClass)->where('objectable_id', $objectID);
        $configs = $query->get();

        foreach ($configs as $key => $config) {
            $configs[$key]->options = json_decode($config->options, true);
        }

        return $configs;
    }

    private function getObjectClass($objectType)
    {
        switch ($objectType) {
            case 'workflow':
                return Wlworkflow::class;
            case 'phase':
                return Wlphase::class;
            case 'job':
                return returnWljob::class;
            case 'dataobject':
                return Wldataobject::class;
        }
        return $objectType;
    }
}
