<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class GrabController extends Controller
{
    public static function WitelStarclicktoBasket($id){
        $data = DB::table('wmcr_sector')
                ->leftJoin('wmcr_master_witel','wmcr_master_witel.id','=','wmcr_sector.witel_id')
                ->where('wmcr_master_witel.name',$id)
                ->select('wmcr_sector.id as sector_id')
                ->get();
        $hari = 7;
        $dateStart = date('Y-m-d',strtotime("-$hari days"));
        $dateEnd = date('Y-m-d');
        foreach ($data as $r){
            // starclick AO_TSEL
            self::StarclicktoBasket($r->sector_id,'TSEL',$id,$dateStart,$dateEnd);
            // starclick AO_TLKM
            self::StarclickOnetoBasket($r->sector_id,'TLKM',$id,$dateStart,$dateEnd);
            self::api_insera_to_wmcr($r->sector_id,$dateStart,$dateEnd);
        }
        
    }

    public static function StarclicktoBasket($sector,$order_type,$witel,$dateStart,$dateEnd){ 
        $data = DB::table('wmcr_source_starclick AS wss')
                    ->leftJoin('wmcr_master_witel AS wmw', 'wss.witel', '=', 'wmw.name')
                    ->leftJoin('wmcr_sector_alpro AS wsa', 'wss.odp_name', '=', 'wsa.name')
                    ->select(DB::raw('
                        wmw.id AS witel_id,
                        wsa.sector_id,
                        wss.order_id,
                        (
                            CASE
                                WHEN wss.jenis_psb LIKE "AO%" THEN "ao_tsel"
                                WHEN wss.status_resume = "MIE - SEND SURVEY " THEN "survey"
                                WHEN wss.jenis_psb LIKE "PDA%" THEN "pda"
                                WHEN wss.jenis_psb LIKE "MO%" THEN "mo"
                                WHEN wss.package_name LIKE "%ORBIT%" THEN "orbit"
                                ELSE null
                            END
                        ) AS tipe_order
                    '))
                    ->where([
                        ['wss.witel', '=', $witel],
                        ['wss.tipe_order', '=', $order_type]
                    ])
                    ->whereBetween('order_date', [$dateStart, $dateEnd])
                    ->where('wsa.sector_id',$sector)
                    ->whereNotNull('wsa.name')
                    ->groupBy('wmw.id', 'wsa.sector_id', 'wss.order_id')
                    ->get();

        $insertData = $data->map(function($item) use ($order_type,$sector) {
            return [
                'witel_id' => $item->witel_id,
                'sector_id' => $sector,
                'source_id' => $item->order_id,
                'order_type_id' => self::ConvertOrderTypeStarclick($item->tipe_order)
            ];
        })->toArray();

        if (!empty($insertData)) {
            self::insertOrUpdate($insertData, 'wmcr_order_basket');
        }

    }

    public static function sendOrderOrbit($order_id,$sector){
        $data = DB::table('wmcr_source_starclick AS wss')
                    ->leftJoin('wmcr_master_witel AS wmw', 'wss.witel', '=', 'wmw.name')
                    ->select(DB::raw('
                        wmw.id AS witel_id,
                        wss.order_id,
                        "orbit" AS tipe_order
                    '))
                    ->where('wss.order_id',$order_id)
                    ->groupBy('wmw.id',  'wss.order_id')
                    ->get();
                    $insertData = $data->map(function($item) use ($sector) {
                        return [
                            'witel_id' => $item->witel_id,
                            'sector_id' => $sector,
                            'source_id' => $item->order_id,
                            'order_type_id' => self::ConvertOrderTypeStarclick($item->tipe_order)
                        ];
                    })->toArray();
            
                    if (!empty($insertData)) {
                        print_r($insertData);
                        self::insertOrUpdate($insertData, 'wmcr_order_basket');
                    }

    }

    public static function StarclicktoBasketbyID($order_id){ 
        $data = DB::table('wmcr_source_starclick AS wss')
                    ->leftJoin('wmcr_master_witel AS wmw', 'wss.witel', '=', 'wmw.name')
                    ->leftJoin('wmcr_sector_alpro AS wsa', 'wss.odp_name', '=', 'wsa.name')
                    ->select(DB::raw('
                        wmw.id AS witel_id,
                        wsa.sector_id,
                        wss.order_id,
                        (
                            CASE
                                WHEN wss.jenis_psb LIKE "AO%" AND wss.tipe_order = "TSEL" THEN "ao_tsel"
                                WHEN wss.status_resume = "MIE - SEND SURVEY " AND wss.tipe_order = "TSEL" THEN "survey"
                                WHEN wss.jenis_psb LIKE "PDA%" AND wss.tipe_order = "TSEL" THEN "pda"
                                WHEN wss.jenis_psb LIKE "MO%" AND wss.tipe_order = "TSEL" THEN "mo"
                                WHEN wss.jenis_psb LIKE "AO%" AND wss.tipe_order = "TLKM" THEN "ao_b2b"
                                WHEN wss.status_resume = "MIE - SEND SURVEY " AND wss.tipe_order = "TLKM" THEN "survey_tlkm"
                                WHEN wss.jenis_psb LIKE "PDA%" AND wss.tipe_order = "TLKM" THEN "pda_tlkm"
                                WHEN wss.jenis_psb LIKE "MO%" AND wss.tipe_order = "TLKM" THEN "mo_tlkm"
                                WHEN wss.package_name LIKE "%ORBIT%" AND wss.tipe_order = "TSEL" THEN "orbit"
                                ELSE null
                            END
                        ) AS tipe_order
                    '))
                    ->whereNotNull('wsa.name')
                    ->where('wss.order_id',$order_id)
                    ->groupBy('wmw.id', 'wsa.sector_id', 'wss.order_id')
                    ->get();

        $insertData = $data->map(function($item) {
            return [
                'witel_id' => $item->witel_id,
                'sector_id' => $item->sector_id,
                'source_id' => $item->order_id,
                'order_type_id' => self::ConvertOrderTypeStarclick($item->tipe_order)
            ];
        })->toArray();

        if (!empty($insertData)) {
            print_r($insertData);
            self::insertOrUpdate($insertData, 'wmcr_order_basket');
        }

    }

    public static function StarclickOnetoBasket($sector,$order_type,$witel,$dateStart,$dateEnd){ 
        $data = DB::table('wmcr_source_starclick AS wss')
                    ->leftJoin('wmcr_master_witel AS wmw', 'wss.witel', '=', 'wmw.name')
                    ->leftJoin('wmcr_sector_alpro AS wsa', 'wss.odp_name', '=', 'wsa.name')
                    ->select(DB::raw('
                        wmw.id AS witel_id,
                        wsa.sector_id,
                        wss.order_id,
                        (
                            CASE
                                WHEN wss.jenis_psb LIKE "AO%" THEN "ao_b2b"
                                WHEN wss.status_resume = "MIE - SEND SURVEY " THEN "survey_tlkm"
                                WHEN wss.jenis_psb LIKE "PDA%" THEN "pda_tlkm"
                                WHEN wss.jenis_psb LIKE "MO%" THEN "mo_tlkm"
                                WHEN wss.package_name LIKE "%ORBIT%" THEN "orbit"
                                ELSE null
                            END
                        ) AS tipe_order
                    '))
                    ->where([
                        ['wss.witel', '=', $witel],
                        ['wss.tipe_order', '=', $order_type]
                    ])
                    ->whereBetween('order_date', [$dateStart, $dateEnd])
                    ->where('wsa.sector_id',$sector)
                    ->whereNotNull('wsa.name')
                    ->groupBy('wmw.id', 'wsa.sector_id', 'wss.order_id')
                    ->get();

        $insertData = $data->map(function($item) use ($order_type,$sector) {
            return [
                'witel_id' => $item->witel_id,
                'sector_id' => $sector,
                'source_id' => $item->order_id,
                'order_type_id' => self::ConvertOrderTypeStarclick($item->tipe_order)
            ];
        })->toArray();

        if (!empty($insertData)) {
            self::insertOrUpdate($insertData, 'wmcr_order_basket');
        }

    }

    public static function ConvertWitelStarclick($id){
        $data = DB::table('wmcr_master_witel')
                ->where('name',$id)
                ->select('id')
                ->first();
        return $data->id;
    }

    public static function ConvertOrderTypeStarclick($id){
        $data = DB::table('wmcr_order_type')
        ->where('aliases', $id)
        ->first(); // Use first() to get a single record

        // Check if $data is not null before accessing properties
        if ($data) {
            return $data->id; // Access the id property
        } else {
            // Handle the case where no data is found
            return null; // Or return an appropriate default value
        }
    }

    public static function insertOrUpdate(array $rows,$table)
    {
        $first = reset($rows);
        $columns = implode(
            ',',
            array_map(function ($value) {
                return "$value";
            }, array_keys($first))
        );
        $values = implode(
            ',',
            array_map(function ($row) {
                return '(' . implode(
                    ',',
                    array_map(function ($value) {
                        return '"' . str_replace('"', '""', $value) . '"';
                    }, $row)
                ) . ')';
            }, $rows)
        );
        $updates = implode(
            ',',
            array_map(function ($value) {
                return "$value = VALUES($value)";
            }, array_keys($first))
        );
        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";
        return \DB::statement($sql);
    }   
    
    public static function api_insera_to_wmcr($sector, $dateStart, $dateEnd)
    {
        DB::statement('
                DELETE
                    a1
                FROM wmcr_source_insera a1
                INNER JOIN wmcr_source_insera a2
                WHERE
                    a1.id > a2.id
                    AND
                    a1.incident_id = a2.incident_id
            ');


        $data = DB::table('wmcr_source_insera AS wsi')
                    ->leftJoin('wmcr_master_witel AS wmw', 'wsi.witel', '=', 'wmw.name')
                    ->leftJoin('wmcr_sector_alpro AS wsa', 'wsi.odp_name', '=', 'wsa.name')
                    ->select(DB::raw('
                        wmw.id AS witel_id,
                        wsa.sector_id,
                        wsi.incident_id,
                        wsi.incident,
                        (
                            CASE
                                WHEN (wsi.customer_segment IN ("DCS", "PL-TSEL") AND wsi.customer_type IN ("HVC_GOLD", "HVC_SILVER", "REGULER") AND wsi.owner_group LIKE "TA HD WITEL%") THEN "reguler_b2c"
                                WHEN (wsi.customer_segment IN ("DES", "DBS", "DGS", "DPS", "DSS", "REG", "DWS", "TAW") AND wsi.owner_group LIKE "TA HD WITEL%") THEN "reguler_b2b"
                                WHEN (wsi.customer_segment IN ("DCS", "PL-TSEL") AND wsi.customer_type IN ("HVC_VVIP", "HVC_DIAMOND", "HVC_PLATINUM") AND wsi.owner_group LIKE "TA HD WITEL%") THEN "hvc_b2c"
                                WHEN (wsi.source_ticket IN ("PROACTIVE_TICKET", "PROACTIVE") AND wsi.reported_by = "PROACTIVE_TICKET") THEN "sqm"
                                WHEN (wsi.source_ticket IN ("PROACTIVE_TICKET", "PROACTIVE") AND wsi.reported_by LIKE "PROMAN%") THEN "unspec"
                                WHEN (wsi.guarante_status = "GUARANTEE") THEN "ffg"
                                ELSE null
                            END
                        ) AS tipe_order
                    '))
                    ->where([
                        ['wsi.incident', 'LIKE', 'INC%'],
                        ['wsa.sector_id', '=', $sector]
                    ])
                    ->whereBetween('wsi.date_reported', [$dateStart, $dateEnd])
                    ->whereNotNull('wsa.name')
                    ->groupBy('wsi.incident_id')
                    ->get();

        $insert = [];

        foreach ($data as $v)
        {
            if ($v->tipe_order !== null)
            {
                $tipe_order = DB::table('wmcr_order_type')
                                ->where('aliases', $v->tipe_order)
                                ->first();

                if ($tipe_order)
                {
                    $insert[] = [
                        'source_id'     => $v->incident_id,
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => $tipe_order->id
                    ];
                }
            }

            // print_r("$v->incident tipe order $v->tipe_order \n");
        }

        if (!empty($insert)) {
            self::insertOrUpdate($insert, 'wmcr_order_basket');
        }

        DB::statement('
                DELETE
                    a1
                FROM wmcr_order_basket a1
                INNER JOIN wmcr_order_basket a2
                WHERE
                    a1.id > a2.id
                    AND
                    a1.source_id = a2.source_id
            ');
    }

}