<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class OrderModel
{
    public static function searchPost($type, $id)
    {
        switch ($type) {
            case 'Provisioning':
                    $data = DB::table('wmcr_source_starclick as wss')
                    ->leftJoin('wmcr_source_mia AS wsm', 'wss.order_id', '=', 'wsm.order_code')
                    ->leftJoin('wmcr_order_dispatch AS wod', 'wss.order_id', '=', 'wod.order_code')
                    ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
                    ->leftJoin('wmcr_order_unit AS wou', 'wot.order_unit_id', '=', 'wou.id')
                    ->leftJoin('wmcr_sector_team AS wst', 'wod.sector_team_id', '=', 'wst.id')
                    ->leftJoin('wmcr_sector AS ws', 'wst.sector_id', '=', 'ws.id')
                    ->select()
                    ->where(function ($query) use ($id) {
                        $query->where('wss.order_id', '=', $id)
                              ->orWhere('wss.speedy', 'LIKE', $id.'%')
                              ->orWhere('wsm.order_code', '=', $id);
                    });

                    return $data->orderBy('wss.order_date', 'DESC')
                    ->orderBy('wsm.order_created_date', 'DESC')
                    ->groupBy(['wss.order_id', 'wsm.order_code', 'wod.order_code'])
                    ->get();
                break;
            
            case 'Migration':
                # code...
                break;

            case 'Assurance':
                    $data = DB::table('wmcr_source_insera AS wsi')
                    ->leftJoin('wmcr_order_dispatch AS wod', 'wsi.incident_id', '=', 'wod.order_code')
                    ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
                    ->leftJoin('wmcr_order_unit AS wou', 'wot.order_unit_id', '=', 'wou.id')
                    ->leftJoin('wmcr_sector_team AS wst', 'wod.sector_team_id', '=', 'wst.id')
                    ->leftJoin('wmcr_sector AS ws', 'wst.sector_id', '=', 'ws.id')
                    ->select()
                    ->where(function ($query) use ($id) {
                        $query->where('wsi.incident', '=', $id)
                              ->orWhere('wsi.service_no', 'LIKE', $id.'%');
                    });

                    return $data->orderBy('wsi.reported_date', 'DESC')
                    ->groupBy(['wsi.incident_id', 'wod.order_code'])
                    ->get();
                break;

            case 'Maintenance':
                # code...
                break;
        }
    }

    public static function undispatchPost($start_date, $end_date)
    {
        $master_sto = DB::table('wmcr_master_sto')->select('name AS area', 'datel')->where('witel_id', session('auth')->witel_id)->get();

        $query1 = DB::table('wmcr_source_mia AS wsm')
        ->leftJoin('wmcr_master_sto AS wms', 'wsm.sto', '=', 'wms.name')
        ->leftJoin('wmcr_order_dispatch AS wod', 'wsm.order_code', '=', 'wod.order_code')
        ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
        ->select(DB::raw('
            wsm.sto AS area,
            SUM(CASE WHEN wsm.order_status_name = "Open" THEN 1 ELSE 0 END) AS order_survey
        '))
        ->where('wsm.order_status_name', 'Open')
        ->whereBetween('wsm.order_created_date', [$start_date, $end_date])
        ->whereNull('wod.order_code')
        ->groupBy('wsm.sto')
        ->get();

        $query2 = DB::table('wmcr_source_starclick AS wss')
        ->leftJoin('wmcr_master_sto AS wms', 'wss.sto', '=', 'wms.name')
        ->leftJoin('wmcr_order_dispatch AS wod', 'wss.order_id', '=', 'wod.order_code')
        ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
        ->select(DB::raw('
            wss.sto AS area,
            SUM(CASE WHEN wss.jenis_psb LIKE "AO%" THEN 1 ELSE 0 END) AS order_ao,
            SUM(CASE WHEN wss.jenis_psb LIKE "MO%" THEN 1 ELSE 0 END) AS order_mo,
            SUM(CASE WHEN wss.jenis_psb LIKE "PDA%" THEN 1 ELSE 0 END) AS order_pda
        '))
        ->whereRaw('DATE(wss.order_date) BETWEEN "'.$start_date.'" AND "'.$end_date.'"')
        ->whereNull('wod.order_code')
        ->groupBy('wss.sto')
        ->get();

        $query3 = DB::table('wmcr_source_insera AS wsi')
        ->leftJoin('wmcr_master_sto AS wms', 'wsi.workzone', '=', 'wms.name')
        ->leftJoin('wmcr_order_dispatch AS wod', 'wsi.incident_id', '=', 'wod.order_code')
        ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
        ->select(DB::raw('
            wsi.workzone AS area,
            SUM(CASE WHEN wsi.ticket_id_gamas = "" AND wsi.source_ticket = "CUSTOMER" THEN 1 ELSE 0 END) AS order_customer,
            SUM(CASE WHEN wsi.ticket_id_gamas = "" AND wsi.source_ticket = "PROACTIVE" THEN 1 ELSE 0 END) AS order_proactive
        '))
        ->whereBetween('wsi.date_reported', [$start_date, $end_date])
        ->whereNull('wod.order_code')
        ->groupBy('wsi.workzone')
        ->get();

        $query = [];

        foreach($master_sto as $val)
        {
            foreach($query1 as $val_c1)
            {
                if (!empty($val_c1->area))
                {
                    if ($val_c1->area == $val->area)
                    {
                        $query[$val->area]['order_survey'] = $val_c1->order_survey;
                    }
                }
                else
                {
                    $query['NONE']['order_survey'] = $val_c1->order_survey;
                }
            }

            foreach($query2 as $val_c2)
            {
                if (!empty($val_c2->area))
                {
                    if ($val_c2->area == $val->area)
                    {
                        $query[$val->area]['order_ao'] = $val_c2->order_ao;
                        $query[$val->area]['order_mo'] = $val_c2->order_mo;
                        $query[$val->area]['order_pda'] = $val_c2->order_pda;
                    }
                }
                else
                {
                    $query['NONE']['order_ao'] = $val_c2->order_ao;
                    $query['NONE']['order_mo'] = $val_c2->order_mo;
                    $query['NONE']['order_pda'] = $val_c2->order_pda;
                }
            }

            foreach($query3 as $val_c3)
            {
                if (!empty($val_c3->area))
                {
                    if ($val_c3->area == $val->area)
                    {
                        $query[$val->area]['order_customer'] = $val_c3->order_customer;
                        $query[$val->area]['order_proactive'] = $val_c3->order_proactive;
                    }
                }
                else
                {
                    $query['NONE']['order_customer'] = $val_c3->order_customer;
                    $query['NONE']['order_proactive'] = $val_c3->order_proactive;
                }
            }
        }

        return $query;
    }
}
?>