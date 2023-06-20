<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\Company;
use App\Models\shared\ConfigUser;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SoapClient;
use SoapHeader;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Mail\EmailUserChangePass;
use App\Models\shared\Department;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$users = User::whereNotIn('username',['admin','user','leader'])->where('active',1)->with('groups')->orderBy('name')->get();
        $result = array();
        $result['data'] = $users;

         //type: sẽ điều khiển loại danh sách  sẽ trả về
         if($request->filled('type')){

            $ListCompany = array() ;
            $companies = $users->pluck('company_id')->flatten();
            $companies->sort();
            $companies = array_unique($companies->toArray());
            $companies = Company::whereIn('id',$companies)->get();
            foreach ($companies as $key => $comp) {
                $ItemCompany = array();
                $ItemCompany['label'] = $comp->id ."-".$comp->name;
                $ItemCompany['id'] ="c". $comp->id;
                $ItemCompany['children'] = array();
                foreach ($users as $key => $user) {
                    if ($user->company_id == $comp->id ) {
                        $item['label'] =  $user->name.' ('.$user->username.')' ;
                        $item['id'] =  $user->id;
                        array_push($ItemCompany['children'],$item);
                    }

                }

                array_push($ListCompany,$ItemCompany);

            }
            $users = $ListCompany;
         }
		if($result){
			$status = "1";
			$message = "Thành công";
		}
		else{
			$status = "0";
			$message = "Không thành công";
		}
        return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$users], JSON_UNESCAPED_UNICODE);
    }
    public function user_config(Request $request)
    {

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;

        //dd( $request);

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'value' => 'required',
        ]);
        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            $code = $request->code;
            switch ($code) {
                case Ultils::$EXPAND_LEFT_MENU:

                    $expand = ConfigUser::where('user_id',auth()->user()->id)
                    ->where('code','expand_menu')->first();

                        if($expand ==null){
                            $expand = new ConfigUser;
                            //dd("test");
                            $expand->code = Ultils::$EXPAND_LEFT_MENU;
                            $expand->value =  $request->value;
                            $expand->user_id = auth()->user()->id;

                            $expand->save();
                        }else{

                            $expand->value = $request->value;
                            $expand->save();
                        }
                    break;

            }
            $result['data']['success']  = 1;
            $result['data']['config']  = $expand;
        }


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function upload_avatar(Request $request){


      //  dd('test');
      //return response()->json($request->file['base64']);
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;

        //dd( $request);
        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'user_id' => 'required',
        ]);

        $failed = $validator->fails();
            //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {



                $user = User::findOrFail($request->user_id);

                if ($user) {

                    $allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
                    $contentType = explode(':',substr($request->file['base64'],0,strpos($request->file['base64'],';')))[1] ;
                    //kiểm tra có phải là file hình không ?
                    if( in_array($contentType, $allowedMimeTypes) ){

                        if(file_exists(public_path().$user->avatar)){
                            unlink(public_path().$user->avatar);
                        }
                        $name = "/avatars/" . uniqid() . '.' . $request->file['ext'];
                        //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                        $base64_str = substr($request->file['base64'], strpos($request->file['base64'], ",")+1);
                        $image = base64_decode($base64_str);
                        file_put_contents(public_path().$name,  $image );

                        $user->avatar =  $name;
                        $user->update();
                        $result['data']  = $name;

                      }else{
                        $result['data']['errors'] = 'File not image';
                      }


                    // $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function changepass(Request $request)
    {

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;


        $request_data = $request->All();
        $messages = [
            'current-password.required' => 'Nhập mật khẩu hiện tại',
            'password.required' => 'Nhập mật khẩu mới',
            'password_confirmation.same' => 'Mật khẩu nhập lại không khớp nhau',
        ];
        // $this->validate($request, [
        //     'current-password' => 'required',
        //     'password' => 'required|same:password',
        //     'password_confirmation' => 'required|same:password',
        // ], $messages);
         //   dd($request_data);
        $validator = Validator::make($request->all(), [
            'current-password' => 'required',
            'password' => 'required|same:password',
            'password_confirmation' => 'required|same:password',
        ], $messages);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            $current_password = Auth::User()->password;
            if (Hash::check($request_data['current-password'], $current_password)) {
                $user_id = Auth::User()->id;
                $obj_user = User::find($user_id);
                $obj_user->password = Hash::make($request_data['password']);;
                $obj_user->save();

                $result['data']['success']  = 1;
            } else {
               // $result['data']['errors'] =array();
                $err = array("current-password"=>'Mật khẩu hiện tại không đúng');

                $result['data']['errors'] = $err;
               // $result['data']['errors'] = [{'mess':'Mật khẩu hiện tại không đúng'}];

            }

        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);


    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


        return json_encode('OK');
    }

    public function demo()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = User::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $user;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function  ownerInfo()
    {

        $user = auth()->user();

        $result = array();
        $result['data'] =  array();
        $result['data'] = $user;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function tree_user_new(Request $request)
    {
        $users = User::whereNotIn('username',['admin','user','leader'])->orderBy('name')->get();

        // $users = User::all();
        $result = array();
        $result['data'] = $users;

         //type: sẽ điều khiển loại danh sách  sẽ trả về
         if($request->filled('type')){

            $companys = $users->pluck('company_id')->flatten();
            $departments = $users->pluck('department_id')->flatten();
            $companys->sort();
            $departments->sort();
            $companys = array_unique($companys->toArray());
            $departments = array_unique($departments->toArray());

            $ListCompany = array() ;
            $companys = Company::whereIn('id',$companys)->get();
            $departments = Department::whereIn('id',$departments)->get();
            foreach ($companys as $key => $comp) {
                $ItemCompany = array();
                $ItemCompany['label'] = $comp->id."-".$comp->name;
                $ItemCompany['id'] ="c". $comp->id;
                $ItemCompany['children'] = array();
                 foreach ($departments as   $dept) {

                    if($dept->company_id == $comp->id){
                        $ItemDepartment = array();
                        $ItemDepartment['label'] = $dept->name;
                        $ItemDepartment['children'] = array();
                        $ItemDepartment['id'] ="d". $dept->id;
                        $name_active='';
                        foreach ($users as  $grp) {
                            if($grp->active==0){
                                $name_active='(Không hoạt động)';
                            }else{
                                $name_active='';
                            }
                            if($grp->company_id == $comp->id && $grp->department_id == $dept->id){

                                $item['label'] = $grp->username."-".  $grp->name.' '.$name_active;
                                $item['id'] =  $grp->id;
                                array_push($ItemDepartment['children'],$item);
                            }
                        }
                        array_push($ItemCompany['children'],$ItemDepartment);
                    }

                }
               
                array_push($ListCompany,$ItemCompany);
                // dd($ListCompany);
            }
            $users = $ListCompany;
         }
		if($result){
			$status = "1";
			$message = "Thành công";
		}
		else{
			$status = "0";
			$message = "Không thành công";
		}
        return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$users], JSON_UNESCAPED_UNICODE);
    }
}
