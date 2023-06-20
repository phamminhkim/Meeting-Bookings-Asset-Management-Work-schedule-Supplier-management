<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\login;

class LoginController extends Controller
{
    public function username()
    {
        $login = request()->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }
    public function login(Request $request)
    {

        $login = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $login['active'] = 1;//check user đang active
        if (!Auth::attempt($login)) {
            return Response(['message' => 'Invalid login creadentials', 'success' => '0']);
        }
        $user = new User();
        $user = Auth::user();
        /// dd($user);
        $accessToken = $user->createToken('authToken')->accessToken;;
        return Response(['user' => Auth::user(), 'access_token' => $accessToken, 'success' => '1']);
    }
}
