<?php

namespace App\Http\Controllers\BaseController;

use App\Http\Controllers\Controller;
use App\login;
use App\Models\shared\ConfigSys;
use App\Models\shared\Menu;
use App\Models\shared\ConfigUser;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $access_token = "";
            $version = "";
            $expand_menu = "sidebar-mini accent-info";
            $expand_menu_value = 0;
            $user = Auth()->user();
            view()->share(compact('access_token', 'expand_menu', 'expand_menu_value', 'version', 'user'));

            return $next($request);
        });
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {

            $menu_module = array();
            $list = array();
            $access_token = "";
            //load menu từ Role_user
            $user = new User();
            $user =  Auth()->user();
            //dd('xin chao'. Auth()->user()->token());
            //dd($request);
            ////nếu không phải login từ API thì tạo token cho user
            if (!Auth()->user()->token()) {
                if ($request->session() && $request->session()->get('user')) {
                    $access_token = $request->session()->get('user');
                } else {

                    $access_token = $user->createToken('authToken')->accessToken;
                    $user->withAccessToken($access_token);
                    $request->session()->put('user', $access_token);
                }
            }
            //dd($user->hasPermission('upload_mvc'));
            Auth::user()->roles->load('menus');
            //$role_menus = Auth::user()->roles->pluck('menus')->unique('title')->flatten();
            $raw_role_menus = Auth::user()->roles->pluck('menus')->flatten();
            $role_menus = array();

            foreach ($raw_role_menus as $menu) {
                $role_menus[$menu->id] = $menu;
            }

            foreach ($role_menus as  $role_menu) {
                $menu_tree = Ultils::getMenuTreeTop_NestedSet($role_menu);

                if (count($menu_tree) > 0) {
                    if (count($menu_tree) > 1) {
                        $menu_root = Ultils::getMenuRoot_NestedSet($role_menu);
                        
                        if ($menu_root && !in_array($menu_root->id, $menu_module)) {
                            array_push($menu_module, $menu_root->id);
                        }
                    } else {
                        array_push($menu_module, $role_menu->id);
                    }
                }
            }




            // $menus = Menu::where('parent', 0)->get();
            $menus = Menu::whereIn('id', $menu_module)->with('childs')->orderBy('sort')->get();
            foreach ($menus as $menu) {
                $menu = Ultils::getRecursiveMenuChildren($menu);
            }

            $link = $request->path();

            $menucurr = Menu::where('link', $link)->get()->first();


            // dd($menucurr->parent()->get()!=null);
            $menucurr_root = 0;
            if ($menucurr) {
                $menucurr_root = Ultils::getMenuRoot_NestedSet($menucurr);
            } else {
                $menucurr = new Menu();
                $menucurr->id = 0;
            }

            if (!$menucurr_root) {
                $menucurr_root = new Menu();
                $menucurr_root->id = 0;
            }

            //Cấu hình expand_menu left
            $config_user  =  ConfigUser::where('user_id', auth()->user()->id)
                ->where('code', Ultils::$EXPAND_LEFT_MENU)->first();

            $expand_menu = "sidebar-mini accent-info";
            $expand_menu_value = 0;
            if ($config_user) {
                if ($config_user->value == 1) {
                    $expand_menu_value = 1;
                    $expand_menu = 'sidebar-mini accent-info  sidebar-collapse';
                } else {
                    $expand_menu_value = 0;
                    $expand_menu = 'sidebar-mini accent-info  ';
                }
            }
            $version = ConfigSys::where('id', 'VERSION')->pluck('value')->first();
            view()->share(compact('menus', 'menucurr', 'menucurr_root', 'access_token', 'expand_menu', 'expand_menu_value', 'version'));
            return $next($request);
        });


        //  dd(Auth::logout());

    }
    public function sendResponse($result, $message = '', $code = 200)
    {

        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
    public function sendError($error, $errorMessage = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error
        ];
        if (!empty($errorMessage)) {
            $response['errors'] = $errorMessage;
        }
        return response()->json($response, $code);
    }
    public function sendSuccess($message)
    {
        $response = [
            'success' => true,
            'message' => $message
        ];
        return response()->json($response, 200);
    }
    public function sendFailedWithMessage($message)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        return response()->json($response, 200);
    }
    public function sendFailedWithStatusCode($message, $code)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        return response()->json($response, $code);
    }
}
