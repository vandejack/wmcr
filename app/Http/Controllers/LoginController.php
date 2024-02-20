<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Validator;
use Session;
use App\Models\LoginModel;

date_default_timezone_set("Asia/Makassar");

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function logout()
    {
        Session::forget('auth');

        return redirect('/login')->withCookie(cookie()->forever('wmcr-token', ''));
    }

    public function reloadCaptcha()
    {
        $result['captcha'] = urldecode(captcha_img('math'));
        return $result;
    }
}
?>