<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class OrderModel
{
    public static function dispatchSave($request){
        $check = DB::table('wmcr_order_dispatch')
        ->where('order_id',$request->order_id)
        ->first();
        if (count($check)>0) {
        $query = DB::table('wmcr_order_dispatch')
                ->where('order_id',$request->order_id)
                ->update([
                    'order_type_id' => $request->order_type_id,
                    'assigned_date' => $request->assigned_date,
                    'timeStart' => $request->startTime,
                    'timeEnd' => $request->endTime,
                    'sector_team_id' => $request->team,
                    'updated_by' => session('auth')->nik,
                    'updated_at' => DB::raw('NOW()')
                ]);
        if ($query) { $exec = $check->id; } else { $exec = 0; }
        } else {
        $exec = DB::table('wmcr_order_dispatch')
                ->insertGetId([
                    'order_id' => $request->order_id,
                    'order_type_id' => $request->order_type_id,
                    'assigned_date' => $request->assigned_date,
                    'timeStart' => $request->startTime,
                    'timeEnd' => $request->endTime,
                    'sector_team_id' => $request->team,
                    'created_by' => session('auth')->nik
                ]);
        }
        return $exec;
    }
    public static function updateBasket($order_id,$id){
        return DB::table('wmcr_order_basket')
                ->where('source_id',$order_id)
                ->update([
                    'dispatch' => 1,
                    'dispatchId' => $id
                ]);
    }
    public static function sync_to_basket($order_type,$start_date,$end_date){
        switch ($order_type){
            case "AO" :
                $query = DB::SELECT('
                SELECT * FROM wmcr_source_starclick a LEFT JOIN wmcr_sector_alpro b ON a.odp_name = b.name WHERE a.jenis_psb LIKE "%AO%" AND DATE(a.order_date)>"2024-06-31" AND a.sto IN ("PLE","BTB","TKI")
                ');
                return $query;
            break;
        }
    }

    public static function getBasketbyID($id){
        return DB::table('wmcr_order_basket as a')
                    ->leftJoin('wmcr_order_type as b','a.order_type_id','=','b.id')
                    ->leftJoin('wmcr_sector as c','a.sector_id','=','c.id')
                    ->select('a.*','b.*','c.name as sectorName','b.id as typeID')
                    ->where('a.id',$id)
                    ->first();
    }

    public static function orderTypeGet($type,$sector){
        return DB::table('wmcr_order_type AS a')
            ->select('a.name', 'a.id AS order_type', DB::raw('SUM(CASE WHEN b.dispatch IS NULL THEN 1 ELSE 0 END) AS jumlah'))
            ->leftJoin('wmcr_order_basket AS b', 'a.id', '=', 'b.order_type_id')
            ->where('a.type', $type)
            ->where('b.sector_id', $sector)
            ->groupBy('a.id')
            ->orderBy('a.urutan')
            ->get();
    }

    public static function basketList($order_type,$witel,$sektor){
        
        $cekOrderType = DB::table('wmcr_order_type')->where('id',$order_type)->first();
        $orderSource = $cekOrderType->source;
        switch ($orderSource){
            case "wmcr_source_starclick" : 
                $query = DB::SELECT('select a.*,b.customer_addr as ALAMAT,b.jenis_psb as JENISORDER,b.sto as STO, b.customer_name as NAME, b.odp_name as ODP,c.name as orderType,c.MH 
                from wmcr_order_basket a 
                left join wmcr_source_starclick b ON a.source_id = b.order_id 
                left join wmcr_order_type c ON a.order_type_id = c.id
                where a.dispatch is NULL AND  a.order_type_id = '.$order_type.' AND a.sector_id = '.$sektor.' group by a.source_id');
            break;
            case "wmcr_source_insera" : 
                $query = DB::SELECT('select a.*, b.workzone as STO, b.customer_name as NAME, b.odp_name as ODP, b.summary as ALAMAT, c.name as JENISORDER,c.name as orderType,c.MH
                    from wmcr_order_basket a
                    left join wmcr_source_insera b ON a.source_id = b.incident_id 
                    left join wmcr_order_type c ON a.order_type_id = c.id
                    where a.dispatch is NULL AND  a.order_type_id = '.$order_type.' AND a.sector_id = '.$sektor.'');
            break;
            default :
                $query = DB::SELECT('
                    select a.*, NULL as STO, b.customer_name as NAME, NULL as ODP, NULL as ALAMAT, NULL as JENISORDER
                    from wmcr_order_basket a 
                    where a.dispatch is NULL AND a.order_type_id = '.$order_type.' AND a.sector_id = '.$sektor.' 
                '); 
            break;
        }
        

        return $query;
    }

    public static function search_post($type, $id)
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

    public static function undispatch_post($start_date, $end_date)
    {
        $master_sto = DB::table('wmcr_master_sto')
        ->select('name AS area', 'datel')
        ->where('witel_id', session('auth')->witel_id)
        ->get();

        $query1 = DB::table('wmcr_source_mia AS wsm')
        ->leftJoin('wmcr_master_sto AS wms', 'wsm.sto', '=', 'wms.name')
        ->leftJoin('wmcr_order_dispatch AS wod', 'wsm.order_code', '=', 'wod.order_code')
        ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
        ->select(DB::raw('
            wsm.sto AS area,
            SUM(CASE WHEN wsm.order_status_name = "Open" THEN 1 ELSE 0 END) AS order_survey
        '))
        ->where([
            ['wsm.order_status_name', 'Open'],
            ['wsm.witel', session('auth')->witel_name],
        ])
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
            SUM(CASE WHEN SUBSTRING_INDEX(wss.jenis_psb, "|", 1) = "AO" THEN 1 ELSE 0 END) AS order_ao,
            SUM(CASE WHEN SUBSTRING_INDEX(wss.jenis_psb, "|", 1) = "MO" THEN 1 ELSE 0 END) AS order_mo,
            SUM(CASE WHEN SUBSTRING_INDEX(wss.jenis_psb, "|", 1) IN ("PDA", "PDA LOCAL") THEN 1 ELSE 0 END) AS order_pda
        '))
        ->whereIn('wss.order_status', ['51', '300', '1202'])
        ->whereRaw('DATE(wss.order_date) BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND wss.witel = "'.session('auth')->witel_name.'" AND wss.sto != ""')
        ->whereNull('wod.order_code')
        ->groupBy('wss.sto')
        ->get();

        $query3 = DB::table('wmcr_source_insera AS wsi')
        ->leftJoin('wmcr_master_sto AS wms', 'wsi.workzone', '=', 'wms.name')
        ->leftJoin('wmcr_order_dispatch AS wod', 'wsi.incident_id', '=', 'wod.order_code')
        ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
        ->select(DB::raw('
            wsi.workzone AS area,
            SUM(CASE WHEN wsi.customer_segment IN ("DCS", "PL-TSEL") AND wsi.source_ticket != "PROACTIVE" THEN 1 ELSE 0 END) AS order_b2c,
            SUM(CASE WHEN wsi.customer_segment IN ("DGS", "DWS", "DES", "DBS", "DSS", "DPS", "REG") AND wsi.source_ticket != "PROACTIVE" THEN 1 ELSE 0 END) AS order_b2b,
            SUM(CASE WHEN wsi.source_ticket = "PROACTIVE" THEN 1 ELSE 0 END) AS order_proactive
        '))
        ->where([
            ['wsi.ticket_id_gamas', ''],
            ['wsi.service_type', '!=', 'NON-NUMBERING'],
            ['wsi.witel', session('auth')->witel_name]
        ])
        ->whereIn('wsi.status', ['NEW', 'DRAFT', 'ANALYSIS', 'PENDING', 'BACKEND'])
        ->whereBetween('wsi.date_reported', [$start_date, $end_date])
        ->whereNull('wod.order_code')
        ->groupBy('wsi.workzone')
        ->get();

        $query4 = DB::table('wmcr_source_access_quality AS wsaq')
        ->leftJoin('wmcr_master_sto AS wms', 'wsaq.cmdf', '=', 'wms.name')
        ->leftJoin('wmcr_order_dispatch AS wod', 'wsaq.nd', '=', 'wod.order_code')
        ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
        ->select(DB::raw('
            wsaq.cmdf AS area,
            SUM(CASE WHEN wsaq.jenis = "non_warranty" THEN 1 ELSE 0 END) AS order_non_warranty,
            SUM(CASE WHEN wsaq.jenis = "warranty" THEN 1 ELSE 0 END) AS order_warranty
        '))
        ->where('wsaq.witel', session('auth')->witel_name)
        ->whereBetween('wsaq.tanggal_order', [$start_date, $end_date])
        ->whereNull('wod.order_code')
        ->groupBy('wsaq.cmdf')
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
                        $query[$val->area]['order_b2c'] = $val_c3->order_b2c;
                        $query[$val->area]['order_b2b'] = $val_c3->order_b2b;
                        $query[$val->area]['order_proactive'] = $val_c3->order_proactive;
                    }
                }
                else
                {
                    $query['NONE']['order_b2c'] = $val_c3->order_b2c;
                    $query['NONE']['order_b2b'] = $val_c3->order_b2b;
                    $query['NONE']['order_proactive'] = $val_c3->order_proactive;
                }
            }

            foreach($query4 as $val_c4)
            {
                if (!empty($val_c4->area))
                {
                    if ($val_c4->area == $val->area)
                    {
                        $query[$val->area]['order_non_warranty'] = $val_c4->order_non_warranty;
                        $query[$val->area]['order_warranty'] = $val_c4->order_warranty;
                    }
                }
                else
                {
                    $query['NONE']['order_non_warranty'] = $val_c4->order_non_warranty;
                    $query['NONE']['order_warranty'] = $val_c4->order_warranty;
                }
            }
        }

        return $query;
    }

    public static function undispatch_detail($area, $order, $start_date, $end_date)
    {
        if (in_array($order, ['order_survey', 'order_ao', 'order_mo', 'order_pda', 'order_provisioning']))
        {
            if ($order == 'order_survey')
            {
                $data = DB::table('wmcr_source_mia AS wsm')
                ->leftJoin('wmcr_master_sto AS wms', 'wsm.sto', '=', 'wms.name')
                ->leftJoin('wmcr_order_dispatch AS wod', 'wsm.order_code', '=', 'wod.order_code')
                ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
                ->select('wsm.*')
                ->where([
                    ['wsm.order_status_name', 'Open'],
                    ['wsm.witel', session('auth')->witel_name],
                ])
                ->whereBetween('wsm.order_created_date', [$start_date, $end_date])
                ->whereNull('wod.order_code');

                if ($area != 'all')
                {
                    $data->where('wsm.sto', $area);
                }
            }
            else if (in_array($order, ['order_ao', 'order_mo', 'order_pda']))
            {
                $data = DB::table('wmcr_source_starclick AS wss')
                ->leftJoin('wmcr_master_sto AS wms', 'wss.sto', '=', 'wms.name')
                ->leftJoin('wmcr_order_dispatch AS wod', 'wss.order_id', '=', 'wod.order_code')
                ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
                ->select('wss.*')
                ->whereIn('wss.order_status', ['51', '300', '1202'])
                ->whereRaw('DATE(wss.order_date) BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND wss.witel = "'.session('auth')->witel_name.'"')
                ->whereNull('wod.order_code');

                if ($area != 'all')
                {
                    $data->where('wss.sto', $area);
                }

                if ($order == 'order_ao')
                {
                    $data->whereRaw('SUBSTRING_INDEX(wss.jenis_psb, "|", 1) = "AO"');
                }
                else if ($order == 'order_mo')
                {
                    $data->whereRaw('SUBSTRING_INDEX(wss.jenis_psb, "|", 1) = "MO"');
                }
                else if ($order == 'order_pda')
                {
                    $data->whereRaw('SUBSTRING_INDEX(wss.jenis_psb, "|", 1) IN ("PDA", "PDA LOCAL")');
                }
            }
        }
        else if (in_array($order, ['order_b2c', 'order_b2b', 'order_proactive', 'order_assurance']))
        {
            $data = DB::table('wmcr_source_insera AS wsi')
            ->leftJoin('wmcr_master_sto AS wms', 'wsi.workzone', '=', 'wms.name')
            ->leftJoin('wmcr_order_dispatch AS wod', 'wsi.incident_id', '=', 'wod.order_code')
            ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
            ->select('wsi.*')
            ->where([
                ['wsi.ticket_id_gamas', ''],
                ['wsi.service_type', '!=', 'NON-NUMBERING'],
                ['wsi.witel', session('auth')->witel_name]
            ])
            ->whereIn('wsi.status', ['NEW', 'DRAFT', 'ANALYSIS', 'PENDING', 'BACKEND'])
            ->whereBetween('wsi.date_reported', [$start_date, $end_date])
            ->whereNull('wod.order_code');

            if ($area != 'all')
            {
                $data->where('wsi.workzone', $area);
            }

            if ($order == 'order_b2c')
            {
                $data->whereIn('wsi.customer_segment', ['DCS', 'PL-TSEL'])->where('wsi.source_ticket', '!=', 'PROACTIVE');
            }
            else if ($order == 'order_b2b')
            {
                $data->whereIn('wsi.customer_segment', ['DGS', 'DWS', 'DES', 'DBS', 'DSS', 'DPS', 'REG'])->where('wsi.source_ticket', '!=', 'PROACTIVE');
            }
            else if ($order == 'order_proactive')
            {
                $data->where('wsi.source_ticket', 'PROACTIVE');
            }
        }
        else if (in_array($order, ['order_non_warranty', 'order_warranty', 'order_maintenance']))
        {
            $data = DB::table('wmcr_source_access_quality AS wsaq')
            ->leftJoin('wmcr_master_sto AS wms', 'wsaq.cmdf', '=', 'wms.name')
            ->leftJoin('wmcr_order_dispatch AS wod', 'wsaq.nd', '=', 'wod.order_code')
            ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
            ->select('wsaq.*')
            ->where('wsaq.witel', session('auth')->witel_name)
            ->whereBetween('wsaq.tanggal_order', [$start_date, $end_date])
            ->whereNull('wod.order_code');

            if ($area != 'all')
            {
                $data->where('wsaq.cmdf', $area);
            }

            if ($order == 'order_non_warranty')
            {
                $data->where('wsaq.jenis', 'non_warranty');
            }
            else if ($order == 'order_warranty')
            {
                $data->where('wsaq.jenis', 'warranty');
            }
        }

        return $data->get();
    }

    public static function undispatch_search($order, $id)
    {
        if (in_array($order, ['order_survey', 'order_ao', 'order_mo', 'order_pda']))
        {
            if ($order == 'order_survey')
            {
                $data = DB::table('wmcr_source_mia AS wsm')
                ->leftJoin('wmcr_master_sto AS wms', 'wsm.sto', '=', 'wms.name')
                ->leftJoin('wmcr_order_dispatch AS wod', 'wsm.order_code', '=', 'wod.order_code')
                ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
                ->select('wsm.*')
                ->where([
                    ['wsm.order_status_name', 'Open'],
                    ['wsm.order_code', $id]
                ])
                ->whereNull('wod.order_code');
            }
            else if (in_array($order, ['order_ao', 'order_mo', 'order_pda']))
            {
                $data = DB::table('wmcr_source_starclick AS wss')
                ->leftJoin('wmcr_master_sto AS wms', 'wss.sto', '=', 'wms.name')
                ->leftJoin('wmcr_order_dispatch AS wod', 'wss.order_id', '=', 'wod.order_code')
                ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
                ->select('wss.*')
                ->whereIn('wss.order_status', ['51', '300', '1202'])
                ->where('wss.order_id', $id)
                ->whereNull('wod.order_code');
            }
        }
        else if (in_array($order, ['order_b2c', 'order_b2b', 'order_proactive']))
        {
            $data = DB::table('wmcr_source_insera AS wsi')
            ->leftJoin('wmcr_master_sto AS wms', 'wsi.workzone', '=', 'wms.name')
            ->leftJoin('wmcr_order_dispatch AS wod', 'wsi.incident_id', '=', 'wod.order_code')
            ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
            ->select('wsi.*')
            ->where([
                ['wsi.ticket_id_gamas', ''],
                ['wsi.service_type', '!=', 'NON-NUMBERING'],
                ['wsi.incident', $id]
            ])
            ->whereIn('wsi.status', ['NEW', 'DRAFT', 'ANALYSIS', 'PENDING', 'BACKEND'])
            ->whereNull('wod.order_code');
        }
        else if (in_array($order, ['order_non_warranty', 'order_warranty']))
        {
            $data = DB::table('wmcr_source_access_quality AS wsaq')
            ->leftJoin('wmcr_master_sto AS wms', 'wsaq.cmdf', '=', 'wms.name')
            ->leftJoin('wmcr_order_dispatch AS wod', 'wsaq.nd', '=', 'wod.order_code')
            ->leftJoin('wmcr_order_type AS wot', 'wod.order_type_id', '=', 'wot.id')
            ->select('wsaq.*')
            ->where('wsaq.nd', $id)
            ->whereNull('wod.order_code');
        }

        return $data->first();
    }

    public static function order_tag()
    {
        return DB::table('wmcr_order_tag')->select(DB::raw('name AS id, UPPER(REPLACE(name, "_", " ")) AS text'))->get();
    }

    public static function order_type($aliases)
    {
        return DB::table('wmcr_order_type')->where('aliases', $aliases)->first();
    }
}
?>