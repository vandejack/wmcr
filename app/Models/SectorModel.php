<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class SectorModel
{
    public static function show($id)
    {
        switch ($id) {
            case 'list':
                    $data = DB::table('wmcr_sector AS ws')
                    ->leftJoin('wmcr_employee AS weo1', 'ws.owner1', '=', 'weo1.nik')
                    ->leftJoin('wmcr_employee AS weo2', 'ws.owner2', '=', 'weo2.nik')
                    ->leftJoin('wmcr_sector_rayon AS wcr', 'ws.rayon_id', '=', 'wcr.id')
                    ->select('ws.*', 'weo1.name AS owner1_name', 'weo2.name AS owner2_name');
                break;
            
            case 'rayon':
                    $data = DB::table('wmcr_sector_rayon AS wcr')
                    ->leftJoin('wmcr_employee AS weo', 'wcr.owner', '=', 'weo.nik')
                    ->leftJoin('wmcr_employee AS wem', 'wcr.manager', '=', 'wem.nik')
                    ->select('wcr.*', 'weo.name AS owner_name', 'wem.name AS manager_name');
                break;

            case 'team':
                    $data = DB::table('wmcr_sector_team AS wst')
                    ->leftJoin('wmcr_sector AS ws', 'wst.sector_id', '=', 'ws.id')
                    ->leftJoin('wmcr_employee AS we1', 'wst.technician1', '=', 'we1.nik')
                    ->leftJoin('wmcr_employee AS we2', 'wst.technician2', '=', 'we2.nik')
                    ->select('wst.*', 'ws.name AS sector_name', 'we1.name AS technician1_name', 'we2.name AS technician2_name');
                break;

            case 'alpro':
                    $data = DB::table('wmcr_sector_alpro');
                break;

            case 'schedule':
                    $data = DB::table('wmcr_sector_schedule AS wss')
                    ->leftJoin('wmcr_employee AS we', 'wss.technician', '=', 'we.nik')
                    ->select('wss.*', 'we.name AS technician_name');
                break;

            case 'brifieng':
                    $data = DB::table('wmcr_sector_brifieng');
                break;

            case 'alker':
                # code...
                break;
        }

        return $data->get();
    }
}
?>