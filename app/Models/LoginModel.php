<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class LoginModel
{
    const TABLE = 'wmcr_employee';

    public static function getAll()
    {
        return DB::table(self::TABLE)->get();
    }

    public static function getById($id)
    {
        return DB::table(self::TABLE)->where('wmcr_employee.id', $id)->first();
    }

    public static function getByToken($token)
    {
        return DB::table(self::TABLE)
        ->leftJoin('wmcr_master_regional', 'wmcr_employee.regional_id', '=', 'wmcr_master_regional.id')
        ->leftJoin('wmcr_master_witel', 'wmcr_employee.witel_id', '=', 'wmcr_master_witel.id')
        ->leftJoin('wmcr_master_mitra', 'wmcr_employee.mitra_id', '=', 'wmcr_master_mitra.id')
        ->leftJoin('wmcr_employee_unit', 'wmcr_employee.unit_id', '=', 'wmcr_employee_unit.id')
        ->leftJoin('wmcr_employee_sub_unit', 'wmcr_employee.sub_unit_id', '=', 'wmcr_employee_sub_unit.id')
        ->leftJoin('wmcr_employee_sub_group', 'wmcr_employee.sub_group_id', '=', 'wmcr_employee_sub_group.id')
        ->leftJoin('wmcr_employee_position', 'wmcr_employee.position_id', '=', 'wmcr_employee_position.id')
        ->leftJoin('wmcr_master_level', 'wmcr_employee.level_id', '=', 'wmcr_master_level.id')
        ->leftJoin('wmcr_employee_timezone', 'wmcr_employee.timezone_id', '=', 'wmcr_employee_timezone.id')
        ->select(
            'wmcr_employee.*',
            'wmcr_master_regional.name AS regional_name',
            'wmcr_master_witel.name AS witel_name',
            'wmcr_master_witel.aliases AS witel_aliases',
            'wmcr_master_mitra.name AS mitra_name',
            'wmcr_employee_unit.name AS unit_name',
            'wmcr_employee_sub_unit.name AS sub_unit_name',
            'wmcr_employee_sub_group.name AS sub_group_name',
            'wmcr_employee_position.name AS position_name',
            'wmcr_master_level.name AS level_name',
            'wmcr_employee_timezone.text AS timezone_name'
        )
        ->where('wmcr_employee.token', $token)
        ->first();
    }

    public static function login($nik, $pwd)
    {
        return DB::table(self::TABLE)
        ->where([
            ['wmcr_employee.nik', $nik],
            ['wmcr_employee.password', MD5($pwd)]
        ])
        ->first();
    }

    public static function login_otp($id, $pwd, $otp)
    {
        return DB::table(self::TABLE)
        ->where([
            ['wmcr_employee.id', $id],
            ['wmcr_employee.password', $pwd],
            ['wmcr_employee.otp_code', $otp]
        ])
        ->first();
    }

    public static function insert($input)
    {
        return DB::table(self::TABLE)->insertGetId($input);
    }

    public static function update($id, $input)
    {
        DB::table(self::TABLE)->where('wmcr_employee.id', $id)->update($input);
    }

    public static function delete($id)
    {
        DB::table(self::TABLE)->where('wmcr_employee.id', $id)->delete();
    }

    public static function setToken($id, $token)
    {
        $ip_address = null;

        if (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
        {
            $ip_address = $_SERVER['HTTP_X_FORWARDED'];
        }
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        {
            $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
        }
        else if(isset($_SERVER['HTTP_FORWARDED']))
        {
            $ip_address = $_SERVER['HTTP_FORWARDED'];
        }
        else if(isset($_SERVER['REMOTE_ADDR']))
        {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        else
        {
            $ip_address = 'UNKNOWN';
        }

        DB::table(self::TABLE)
        ->where('wmcr_employee.id', $id)
        ->update([
            'token'      => $token,
            'ip_address' => $ip_address,
            'login_at'   => date('Y-m-d H:i:s')
        ]);

        return $token;
    }

    public static function login_sso($user, $password)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.telkomakses.co.id/API/sso/auth_sso_post.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'username='.$user.'&password='.$password,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $data = 'failed';

        $result = json_decode($response);

        if ($result->auth == 'Yes')
        {
            $check = DB::table('wmcr_employee')
            ->where('nik', $user)
            ->first();

            if ($check != null)
            {
                DB::table('wmcr_employee')
                ->where('nik', $user)
                ->update([
                    'nik'        => $user,
                    'name'       => $result->nama,
                    'password'   => $password,
                    'is_status'  => 1,
                    'login_at'   => date('Y-m-d H:i:s'),
                    'created_by' => 20981020,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            else
            {
                DB::table('wmcr_employee')
                ->insert([
                    'nik'        => $user,
                    'name'       => $result->nama,
                    'password'   => $password,
                    'is_status'  => 1,
                    'login_at'   => date('Y-m-d H: i: s'),
                    'created_by' => 20981020,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            $data = 'success';
        }

        return $data;
    }

}
?>