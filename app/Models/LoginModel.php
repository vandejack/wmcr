<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class LoginModel
{
    const TABLE = 'wmcr_employee AS we';

    public static function getAll()
    {
        return DB::table(self::TABLE)->get();
    }

    public static function getById($id)
    {
        return DB::table(self::TABLE)->where('we.id', $id)->first();
    }

    public static function getByToken($token)
    {
        return DB::table(self::TABLE)
        ->leftJoin('wmcr_master_regional AS wr', 'we.regional_id', '=', 'wr.id')
        ->leftJoin('wmcr_master_witel AS wmw', 'we.witel_id', '=', 'wmw.id')
        ->leftJoin('wmcr_master_mitra AS wmm', 'we.mitra_id', '=', 'wmm.id')
        ->leftJoin('wmcr_employee_unit AS weu', 'we.unit_id', '=', 'weu.id')
        ->leftJoin('wmcr_employee_sub_unit AS wesu', 'we.sub_unit_id', '=', 'wesu.id')
        ->leftJoin('wmcr_employee_sub_group AS wesg', 'we.sub_group_id', '=', 'wesg.id')
        ->leftJoin('wmcr_employee_position AS wep', 'we.position_id', '=', 'wep.id')
        ->leftJoin('wmcr_master_level AS wml', 'we.level_id', '=', 'wml.id')
        ->select('we.*', 'wr.name AS regional_name', 'wmw.name AS witel_name', 'wmm.name AS mitra_name', 'weu.name AS unit_name', 'wesu.name AS sub_unit_name', 'wesg.name AS sub_group_name', 'wep.name AS position_name', 'wml.name AS level_name')
        ->where('we.token', $token)
        ->first();
    }

    public static function login($nik, $pwd)
    {
        return DB::table(self::TABLE)
        ->where([
            ['we.nik', $nik],
            ['we.password', MD5($pwd)]
        ])
        ->first();
    }

    public static function login_otp($id, $pwd, $otp)
    {
        return DB::table(self::TABLE)
        ->where([
            ['we.id', $id],
            ['we.password', $pwd],
            ['we.otp_code', $otp]
        ])
        ->first();
    }

    public static function insert($input)
    {
        return DB::table(self::TABLE)->insertGetId($input);
    }

    public static function update($id, $input)
    {
        DB::table(self::TABLE)->where('we.id', $id)->update($input);
    }

    public static function delete($id)
    {
        DB::table(self::TABLE)->where('we.id', $id)->delete();
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

        DB::table(self::TABLE)->where('we.id', $id)
        ->update([
            'token'      => $token,
            'ip_address' => $ip_address,
            'login_at'   => date('Y-m-d H:i:s')
        ]);

        return $token;
    }

}
?>