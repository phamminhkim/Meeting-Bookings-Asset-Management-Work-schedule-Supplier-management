<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\shared\ConfigSys;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //truyền version xuống view login
        $version = ConfigSys::where('id', 'VERSION')->pluck('value')->first();

        view()->share(compact('version'));
        //$this->middleware('auth')->except('logout');
    }

    //overwrite
    //overrite hàm này để thay đổi trường login
    public function username()
    {
        return 'username';
    }

    protected $dbUser = null;
    //override
    protected function credentials(Request $request)
    {
        $this->dbUser = User::where('username', $request->username)->first();
        if ($this->dbUser) {
            $credentails =  $request->only($this->username(), 'password');
            $credentails['active'] = 1;// $this->dbUser->active; //check user đang active

            return $credentails; // $request->only($this->username(), 'password');
        } else {
            return [];
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        if ($this->dbUser) {
            if ($this->dbUser->active == 1) {
                throw ValidationException::withMessages([
                    $this->username() => [trans('auth.failed')],

                ]);
            } else {
                throw ValidationException::withMessages([
                    $this->username() => [trans('auth.disabled')],
                ]);
            }
        } else {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.nonexisted')],
            ]);
        }
    }
}
