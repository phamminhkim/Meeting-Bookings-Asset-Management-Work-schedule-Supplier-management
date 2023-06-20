<?php

namespace App\Http\Controllers\api\productivity;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\productivity\HraddMark;
use App\Models\shared\Employee;
use App\Models\shared\File;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
use Illuminate\Support\Facades\Storage;
class HrAddMarkController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', new HraddMark());

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $query = HraddMark::query();
         
        if ( $request->filled('start_date')) {
            if (! $request->filled('end_date')) {
                $request->end_date = $request->start_date;
            }
            $start_date = Carbon::create( $request->start_date);
            $end_date = Carbon::create( $request->end_date);
            $end_date->addHours(23);
            $end_date->addMinutes(59);
            $end_date->addSeconds(59);
            
            $query = $query->whereBetween('year', [$start_date->year, $end_date->year]);
            $query = $query->whereBetween('month', [$start_date->month, $end_date->month]);
        }
        if ($request->filled('staff_id')) {
           $staff_codes  = explode(',', $request->staff_id);
            
           $query = $query->whereIn('staff_code', $staff_codes);
          
        }
        $withModel = ['attachment_file','employee','reminder','user'];
        $list= $query->orderBy('id', 'ASC')->with($withModel)->get();
        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    protected function validate_store($request)
    {
        $validator = Validator::make($request->all(), [

          
            'staff_id' => 'required',  
            'mark_delta' => 'required',  
            'year' => 'required'  ,
            'month' => 'required',
            'mark_delta' => 'required',
            'reason' => 'required'    
        ],
        [
              
            'staff_id.required' => "Nhân viên không được rỗng",       
            'mark_delta.required' => "Điểm nhân viên không được rỗng",       
            'year.required' => "Năm không được rỗng",       
            'month.required' => "Tháng không được rỗng",       
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
        $this->authorize('create', new HraddMark());

        $user = new User();
        $user = auth()->user();

        $validator = $this->validate_store($request);
        $failed = $validator->fails();
        $fields =  $request->all();
        if ($failed) {
            return $this->sendError('Validation Error.', $validator->errors(),200);
            // $result['data']['errors'] = $validator->errors();
        }else{
            try {
                $employee = Employee::where('employee_id', $fields['staff_id'])->first();
                $fields['staff_code'] = $employee->employee_id;
                $fields['company_id'] = $employee->company_id;
                $fields['user_id'] = $user->id;

                $existing = HraddMark::where('staff_code', $employee->employee_id)
                                    ->where('year', $fields['year'])
                                    ->where('month', $fields['month'])->first();
                if ($existing) {
                    $existing->fill($fields);
                    $existing->save();
                }
                else {
                    $existing = HraddMark::create($fields);
                }

                
             
               
                $attachment_file = $fields['attachment_file'];
                   for ($j = 0; $j < count($attachment_file); $j++) {
                       //chỉ lưu  các file mới
                       if (!isset($attachment_file[$j]["id"])) {
                           $file = new File();

                           $file->name = $attachment_file[$j]["name"];
                           //$ext = end(explode('.', $filename));
                           // $file->ext = $attachment_file[$i]["ext"];
                           $file->size = $attachment_file[$j]["size"];
                           $file->user_id = $user->id;
                           $strDate = date("Y"). "/" .date("m"). "/"  .date("d");
                           $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                           $name = "public/productivity/" .$strDate. "/" . uniqid() . '.' . $ext;
                           $file->ext = $ext;
                           $file->url = $name;
                           $existing->attachment_file()->save($file);
                           $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                           $image = base64_decode($base64_str);
                           Storage::disk('local')->put($name, $image);
                           FacadesFileVault::encrypt($name);
                       }
                   }
                   $existing->load(['attachment_file','employee','reminder','user']);
 
            } catch (\Throwable $th) {
                return $this->sendError('Save Error.', $th->getMessage(),200);
            }
        }
        return $this->sendResponse($existing, 'HraddMark save successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', HraddMark::find($id));

        $hraddMark = HraddMark::find($id);  
       
        if (is_null($hraddMark)) {
            return $this->sendError('HraddMark not found.');
        }
        $hraddMark->load(['attachment_file','employee','reminder','user']);
        $hraddMark->staff_id = $hraddMark->staff->id;

        return $this->sendResponse($hraddMark, 'HraddMark retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', HraddMark::find($id));

        $hraddMark = HraddMark::find($id); 
        $hraddMark->load(['attachment_file','employee','reminder','user']);
        if (is_null($hraddMark)) {
            
            return $this->sendError('HraddMark not found.');
        }
        
        $hraddMark->load(['attachment_file','employee','reminder','user']);
        return $this->sendResponse($hraddMark, 'HraddMark retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', HraddMark::find($id));

        $user = new User();
        $user = auth()->user();
        $validator = $this->validate_store($request);
        $failed = $validator->fails();
        $fields =  $request->all();
        if ($failed) {
            return $this->sendError('Validation Error.', $validator->errors(),200);
        }else{
            try {

                $hraddMark = HraddMark::find($id);
                $employee = Employee::where('employee_id', $fields['staff_id'])->first();
                $fields['staff_code'] = $employee->employee_id;
                $fields['company_id'] = $employee->company_id;

                $hraddMark->fill($fields);
                $hraddMark->id = $id;
                $hraddMark->user_id = $user->id;
                $hraddMark->save();
                $hraddMark->load(['employee']);
                //save file
                $attachment_file = $fields['attachment_file'];
                $attachmentFileRemove = $fields['attachmentFileRemove'];
                   for ($j = 0; $j < count($attachment_file); $j++) {

                    //chỉ lưu  các file mới
                    if (!isset($attachment_file[$j]["id"])) {
                        $file = new File();

                        $file->name = $attachment_file[$j]["name"];
                        $file->size = $attachment_file[$j]["size"];
                        $file->user_id = $user->id;
                        $strDate = date("Y"). "/" .date("m"). "/"  .date("d");
                        $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                        $name = "public/productivity/" .$strDate. "/" . uniqid() . '.' . $ext;

                        $file->ext = $ext;
                        $file->url = $name;
                        $hraddMark->attachment_file()->save($file);

                        //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                        $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                        $image = base64_decode($base64_str);
                        //file_put_contents(public_path().$name,  $image );
                        Storage::disk('local')->put($name, $image);
                        FacadesFileVault::encrypt($name);
                    }
                }
                //xoá các file trong mảng del

                for ($k = 0; $k < count($attachmentFileRemove); $k++) {
                    if (isset($attachmentFileRemove[$k]["id"])) {
                        $file = File::findOrFail($attachmentFileRemove[$k]["id"]);
                        if ($file) {

                            Storage::delete($file->url . ".enc");
                            $file->delete();
                        }
                    }
                }
                $hraddMark->load(['attachment_file','employee','reminder','user']);
                $hraddMark->staff_id = $employee->employee_id;
               
            } catch (\Throwable $th) {
                
                return $this->sendError('Save Error.', $th->getMessage(),200);
            }
        }
        return $this->sendResponse($hraddMark, 'HraddMark save successfully.');
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
            $this->authorize('delete', HraddMark::find($id));
            
            $hraddMark = HraddMark::find($id);
            if (is_null($hraddMark)) {
            
                return $this->sendError('HraddMark not found.');
            }
            $hraddMark->delete();
            return $this->sendResponse($hraddMark, 'HraddMark was deleted successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Delete Error.', $th->getMessage(),200);
        }
       
    }
}
