<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LangController extends Controller
{
    public function setLanguage($language)
    {
        
        // Session::put('language', $language);
        // dd(Session::get('language',''));
        // return $language;
        Session::put('language', $language);
        dd( Session::get('language'));
        return redirect()->back();
    }
}
