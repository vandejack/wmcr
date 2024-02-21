<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class MasterModel
{
    public static function show($id)
    {
        switch ($id) {
            case 'regional':
                    $data = DB::table('wmcr_master_regional');
                break;
            
            case 'witel':
                    $data = DB::table('wmcr_master_witel')
                    ->leftJoin('wmcr_master_regional', 'wmcr_master_witel.regional_id', '=', 'wmcr_master_regional.id')
                    ->select('wmcr_master_witel.*', 'wmcr_master_regional.name AS regional_name');
                break;

            case 'sto':
                    $data = DB::table('wmcr_master_sto')
                    ->leftJoin('wmcr_master_witel', 'wmcr_master_sto.witel_id', '=', 'wmcr_master_witel.id')
                    ->leftJoin('wmcr_master_regional', 'wmcr_master_witel.regional_id', '=', 'wmcr_master_regional.id')
                    ->select('wmcr_master_sto.*', 'wmcr_master_witel.name AS witel_name', 'wmcr_master_regional.name AS regional_name');
                break;
            
            case 'mitra':
                    $data = DB::table('wmcr_master_mitra')
                    ->leftJoin('wmcr_master_witel', 'wmcr_master_mitra.witel_id', '=', 'wmcr_master_witel.id')
                    ->leftJoin('wmcr_master_regional', 'wmcr_master_witel.regional_id', '=', 'wmcr_master_regional.id')
                    ->select('wmcr_master_mitra.*', 'wmcr_master_witel.name AS witel_name', 'wmcr_master_regional.name AS regional_name')
                    ->where('wmcr_master_mitra.is_active', 1);
                break;

            case 'level':
                    $data = DB::table('wmcr_master_level');
                break;
        }

        return $data->get();
    }
}
?>