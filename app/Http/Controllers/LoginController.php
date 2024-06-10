<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Validator;
use Session;
use App\Models\LoginModel;
use App\Models\Telegram;

date_default_timezone_set("Asia/Makassar");

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function auth_verification()
    {
        $id      = Input::get('id');
        $encrypt = Input::get('encrypt');

        $check   = DB::table('wmcr_employee')->where([ ['id', $id], ['otp_encrypt', $encrypt] ])->first();

        if (!empty($check))
        {
            $data = $check;
        }
        else
        {
            $data                  = new \stdClass();
            $data->id              = null;
            $data->password        = null;
            $data->otp_valid_until = null;
        }
        return view('auth.verification', compact('id', 'encrypt', 'data'));
    }

    public static function generate_otp($id)
    {
        $key     = random_int(0, 999999);
        $key     = str_pad($key, 6, 0, STR_PAD_LEFT);

        $split   = $id.''.$key.''.$id;
        $encrypt = base64_encode($split);

        $check = DB::table('wmcr_employee')->where('nik', $id)->first();

        $text = "<i>Don't share this secret code with anyone\n\n</i>Your OTP Code is <b>".$key."</b>\n\n<i>Valid until ".date('d/m/Y H:i:s', strtotime("+1 minutes"))." WITA</i>";

        Telegram::sendMessage($check->chat_id, $text);

        DB::table('wmcr_employee')->where('nik', $id)->update([
            'otp_encrypt'     => $encrypt,
            'otp_code'        => $key,
            'otp_login'       => date('Y-m-d H:i:s'),
            'otp_valid_until' => date('Y-m-d H:i:s', strtotime("+1 minutes"))
        ]);

        return $key;
    }

    public static function login_validate(Request $req)
    {
        $rules = [ 'captcha' => 'required|captcha' ];

        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails())
        {
            return redirect('/login')->with('alerts', [
                ['type' => 'error', 'text' => 'Invalid or Expired Captcha!']
            ]);
        }

        $data = LoginModel::login($req->nik, $req->password);

        if ($data)
        {
            if ($data->is_status == 0)
            {
                return back()->with('alerts', [
                    ['type' => 'error', 'text' => 'User is <strong>Disabled!</strong>']
                ]);
            }

            if ($data->chat_id == null || $data->chat_id == '' | $data->chat_id == 0)
            {
                return back()->with('alerts', [
                    ['type' => 'error', 'text' => 'Please Check Your <strong>Chat ID!</strong>']
                ]);

                $token = $data->token;

                if (!$data->token)
                {
                    $token = LoginModel::setToken($data->id, md5($data->nik . microtime()));
                }

                if (Session::has('auth-originalUrl'))
                {
                    $url = Session::pull('auth-originalUrl');
                }
                else
                {
                    $url = '/';
                }

                $response = redirect($url);

                if ($token)
                {
                    $response->withCookie(cookie()->forever('wmcr-token', $token));
                }

                return $response;
            }

            self::generate_otp($req->nik);

            $check = DB::table('wmcr_employee')->where('nik', $req->nik)->first();

            return redirect('/auth-verification?id='.$check->id.'&encrypt='.$check->otp_encrypt)->with('alerts', [
                ['type' => 'success', 'text' => 'Please input your <strong>OTP Code!</strong>']
            ]);
        }
        else
        {
            return back()->with('alerts', [
                ['type' => 'error', 'text' => 'NIK atau Password Salah!']
            ]);
        }
    }

    public function login_post(Request $req)
    {
        $otp_code        = $req->input('otp_code')[1].$req->input('otp_code')[2].$req->input('otp_code')[3].$req->input('otp_code')[4].$req->input('otp_code')[5].$req->input('otp_code')[6];
        $otp_valid_until = $req->input('otp_valid_until');

        if (date('Y-m-d H:i:s') > $otp_valid_until)
        {
            return redirect('/login')->with('alerts', [
                ['type' => 'error', 'text' => 'Your OTP Code is <strong>Expired in 1 Minutes!</strong>']
            ]);
        }

        $data = LoginModel::login_otp($req->id, $req->password, $otp_code);

        if ($data)
        {
            if ($data->is_status == 0)
            {
                return back()->with('alerts', [
                    ['type' => 'error', 'text' => 'User is <strong>Disabled!</strong>']
                ]);
            }

            $token = $data->token;

            if (!$data->token)
            {
                $token = LoginModel::setToken($data->id, md5($data->nik . microtime()));
            }

            if (Session::has('auth-originalUrl'))
            {
                $url = Session::pull('auth-originalUrl');
            }
            else
            {
                $url = '/';
            }

            if (strpos($url, 'update-location') !== false || strpos($url, 'ajax') !== false)
            {
                $url = '/';
            }

            $response = redirect($url);

            if ($token)
            {
                $response->withCookie(cookie()->forever('wmcr-token', $token) );
            }

            return $response;
        }
        else
        {
            return back()->with('alerts', [
                ['type' => 'error', 'text' => 'Invalid <strong>OTP Code!</strong>']
            ]);
        }
    }

    public function login_sso_post(Request $req)
    {
        $rules = [ 'captcha' => 'required|captcha' ];

        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails())
        {
            return redirect('/login')->with('alerts', [
                ['type' => 'error', 'text' => 'Invalid or Expired Captcha!']
            ]);
        }

        $output = LoginModel::login_sso($req->nik, $req->password);

        if ($output == 'success')
        {
            $data = DB::table('wmcr_employee')->where('nik', $req->nik)->first();
            
            $token = $data->token;

            if (!$data->token)
            {
                $token = LoginModel::setToken($data->id, md5($data->nik . microtime()));
            }

            if (Session::has('auth-originalUrl'))
            {
                $url = Session::pull('auth-originalUrl');
            }
            else
            {
                $url = '/';
            }

            if (strpos($url, 'update-location') !== false || strpos($url, 'ajax') !== false)
            {
                $url = '/';
            }

            if ($data->regional_id == 0)
            {
                $url = '/profile';
            }

            $response = redirect($url);

            if ($token)
            {
                $response->withCookie(cookie()->forever('wmcr-token', $token));
            }

            return $response;
        }
        else
        {
            return back()->with('alerts', [
                ['type' => 'error', 'text' => 'NIK atau Password Salah!']
            ]);
        }
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