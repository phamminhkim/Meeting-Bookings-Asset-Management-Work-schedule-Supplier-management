<?php

namespace App\Http\Controllers\UserProfile;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class ProfileController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('userprofile.index');
    }
    public function changepass(Request $request)
    {

        $request_data = $request->All();
        $messages = [
            'current-password.required' => 'Nhập mật khẩu hiện tại',
            'password.required' => 'Nhập mật khẩu mới',
            'password_confirmation.same' => 'Mật khẩu nhập lại không khớp nhau',
        ];
        $this->validate($request, [
            'current-password' => 'required',
            'password' => 'required|same:password',
            'password_confirmation' => 'required|same:password',
        ], $messages);


        // if ($validator->fails()) {
        // return back()->with('error',   $validator->getMessageBag()->toArray());
        //} else {
        $current_password = Auth::User()->password;
        if (Hash::check($request_data['current-password'], $current_password)) {
            $user_id = Auth::User()->id;
            $obj_user = User::find($user_id);
            $obj_user->password = Hash::make($request_data['password']);;
            $obj_user->save();
        } else {
            return back()->with('error',  'Mật khẩu hiện tại không đúng');
        }
        // }
        return back()->with('success',  'Đổi mật khẩu thành công');
        //  return view('userprofile.index');
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
