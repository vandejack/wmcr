<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class DashboardModel
{
    public static function ttr_hvc($witel)
    {
        $reported_date = 'reported_date';
        if (in_array($witel, ['KALSEL', 'BALIKPAPAN']))
        {
            $reported_date = "DATE_ADD(reported_date, INTERVAL 1 HOUR)";
            $datenow = "NOW()";
        }
        else
        {
            $datenow = "DATE_ADD(NOW(), INTERVAL 1 HOUR)";
        }

        $data = DB::table('wmcr_source_insera')
            ->select(DB::raw("
                witel,

                SUM(CASE WHEN status IN ('NEW', 'DRAFT', 'ANALYSIS', 'PENDING', 'BACKEND') AND (TIMESTAMPDIFF( HOUR , $reported_date, $datenow) >= 0 AND TIMESTAMPDIFF( HOUR , $reported_date, $datenow) < 1) THEN 1 ELSE 0 END) AS ttr_0hours,
                SUM(CASE WHEN status IN ('NEW', 'DRAFT', 'ANALYSIS', 'PENDING', 'BACKEND') AND (TIMESTAMPDIFF( HOUR , $reported_date, $datenow) >= 1 AND TIMESTAMPDIFF( HOUR , $reported_date, $datenow) < 2) THEN 1 ELSE 0 END) AS ttr_1hours,
                SUM(CASE WHEN status IN ('NEW', 'DRAFT', 'ANALYSIS', 'PENDING', 'BACKEND') AND (TIMESTAMPDIFF( HOUR , $reported_date, $datenow) >= 2 AND TIMESTAMPDIFF( HOUR , $reported_date, $datenow) < 3) THEN 1 ELSE 0 END) AS ttr_2hours,
                SUM(CASE WHEN status IN ('NEW', 'DRAFT', 'ANALYSIS', 'PENDING', 'BACKEND') AND (TIMESTAMPDIFF( HOUR , $reported_date, $datenow) >= 3) THEN 1 ELSE 0 END) AS ttr_3hours,

                SUM(CASE WHEN status IN ('FINALCHECK', 'RESOLVED', 'MEDIACARE', 'SALAMSIM', 'CLOSED') AND (TIMESTAMPDIFF( HOUR , $reported_date, status_date) < 3) THEN 1 ELSE 0 END) AS ttr_comply,
                SUM(CASE WHEN status IN ('FINALCHECK', 'RESOLVED', 'MEDIACARE', 'SALAMSIM', 'CLOSED') AND (TIMESTAMPDIFF( HOUR , $reported_date, status_date) >= 3) THEN 1 ELSE 0 END) AS ttr_notcomply
            "))
            ->whereIn('customer_type', ['HVC_GOLD', 'HVC_PLATINUM', 'HVC_DIAMOND'])
            ->where([
                ['source_ticket', 'CUSTOMER'],
                // ['service_type', 'NON-NUMBERING'],
                ['solution', '!=', 'CABUT/DEACTIVE'],
                ['summary', 'NOT LIKE', '%Z_PERMINTAAN%'],
                ['related_to_gamas', 'NO']
            ])
            ->whereDate('date_reported', DB::raw('CURDATE()'));

        if ($witel != 'ALL')
        {
            $data->where('witel', $witel);
        }

        return $data->groupBy('witel')->get();
    }

    public static function productivity_order()
    {
        $witel = DB::table('wmcr_master_witel')->get();

        $query1 = DB::table('wmcr_source_kpro_provi AS wskp')
        ->leftJoin('wmcr_master_sto AS wms', 'wskp.sto', '=', 'wms.name')
        ->leftJoin('wmcr_master_witel AS wmw', 'wms.witel_id', '=', 'wmw.id')
        ->select(DB::raw('
            wmw.aliases,

            SUM(CASE WHEN wskp.jenispsb = "AO" AND wskp.provider = "PL-TSEL" AND wskp.product = "INDIHOME" THEN 1 ELSE 0 END) AS ao_tsel,
            SUM(CASE WHEN wskp.jenispsb = "AO" AND wskp.provider = "PL-TSEL" AND wskp.product = "ORBIT" THEN 1 ELSE 0 END) AS orbit_tsel,
            SUM(CASE WHEN wskp.jenispsb = "MO" AND wskp.provider = "PL-TSEL" AND wskp.product = "INDIHOME" THEN 1 ELSE 0 END) AS mo_tsel,
            SUM(CASE WHEN wskp.jenispsb IN ("PDA", "PDA LOCAL") AND wskp.provider = "PL-TSEL" AND wskp.product = "INDIHOME" THEN 1 ELSE 0 END) AS pda_tsel
        '))
        ->where([
            ['wskp.status_message', 'Completed']
        ])
        ->whereDate('wskp.last_updated_date', date('Y-m-d'))
        ->groupBy('wmw.aliases')
        ->get();

        $query2 = DB::table('wmcr_source_kpro_provi_survey AS wskps')
        ->leftJoin('wmcr_master_sto AS wms', 'wskps.sto', '=', 'wms.name')
        ->leftJoin('wmcr_master_witel AS wmw', 'wms.witel_id', '=', 'wmw.id')
        ->select(DB::raw('
            wmw.aliases,

            SUM(CASE WHEN wskps.jenispsb = "AO" AND wskps.provider = "PL-TSEL" AND wskps.product = "INDIHOME" THEN 1 ELSE 0 END) AS ao_tlkm,
            SUM(CASE WHEN wskps.jenispsb = "AO" AND wskps.provider = "PL-TSEL" AND wskps.product = "ORBIT" THEN 1 ELSE 0 END) AS orbit_tlkm,
            SUM(CASE WHEN wskps.jenispsb = "MO" AND wskps.provider = "PL-TSEL" AND wskps.product = "INDIHOME" THEN 1 ELSE 0 END) AS mo_tlkm,
            SUM(CASE WHEN wskps.jenispsb IN ("PDA", "PDA LOCAL") AND wskps.provider = "PL-TSEL" AND wskps.product = "INDIHOME" THEN 1 ELSE 0 END) AS pda_tlkm
        '))
        ->where([
            ['wskps.status_message', 'Completed']
        ])
        ->whereDate('wskps.last_updated_date', date('Y-m-d'))
        ->groupBy('wmw.aliases')
        ->get();

        $query3 = DB::table('wmcr_source_insera AS wsi')
        ->leftJoin('wmcr_master_sto AS wms', 'wsi.workzone', '=', 'wms.name')
        ->leftJoin('wmcr_master_witel AS wmw', 'wms.witel_id', '=', 'wmw.id')
        ->select(DB::raw('
            wmw.aliases,

            SUM(CASE WHEN wsi.customer_type = "HVC_VVIP" AND wsi.source_ticket = "CUSTOMER" AND wsi.customer_segment IN ("DCS", "PL-TSEL") THEN 1 ELSE 0 END) AS insera_b2c_vvip,
            SUM(CASE WHEN wsi.customer_type = "HVC_DIAMOND" AND wsi.source_ticket = "CUSTOMER" AND wsi.customer_segment IN ("DCS", "PL-TSEL") THEN 1 ELSE 0 END) AS insera_b2c_diamond,
            SUM(CASE WHEN wsi.customer_type = "HVC_PLATINUM" AND wsi.source_ticket = "CUSTOMER" AND wsi.customer_segment IN ("DCS", "PL-TSEL") THEN 1 ELSE 0 END) AS insera_b2c_platinum,
            SUM(CASE WHEN wsi.customer_type = "HVC_GOLD" AND wsi.source_ticket = "CUSTOMER" AND wsi.customer_segment IN ("DCS", "PL-TSEL") THEN 1 ELSE 0 END) AS insera_b2c_gold,
            SUM(CASE WHEN wsi.customer_type = "REGULER" AND wsi.source_ticket = "CUSTOMER" AND wsi.customer_segment IN ("DCS", "PL-TSEL") THEN 1 ELSE 0 END) AS insera_b2c_reguler,
            SUM(CASE WHEN wsi.source_ticket = "PROACTIVE" AND wsi.customer_segment IN ("DCS", "PL-TSEL") THEN 1 ELSE 0 END) AS insera_b2c_proactive,

            SUM(CASE WHEN wsi.customer_segment = "DES" THEN 1 ELSE 0 END) AS insera_b2b_des,
            SUM(CASE WHEN wsi.customer_segment = "DBS" THEN 1 ELSE 0 END) AS insera_b2b_dbs,
            SUM(CASE WHEN wsi.customer_segment = "DGS" THEN 1 ELSE 0 END) AS insera_b2b_dgs,
            SUM(CASE WHEN wsi.customer_segment = "DPS" THEN 1 ELSE 0 END) AS insera_b2b_dps,
            SUM(CASE WHEN wsi.customer_segment = "DSS" THEN 1 ELSE 0 END) AS insera_b2b_dss,
            SUM(CASE WHEN wsi.customer_segment = "REG" THEN 1 ELSE 0 END) AS insera_b2b_reg,
            SUM(CASE WHEN wsi.customer_segment = "DWS" THEN 1 ELSE 0 END) AS insera_b2b_dws,
            SUM(CASE WHEN wsi.customer_segment = "TAW" THEN 1 ELSE 0 END) AS insera_b2b_taw
        '))
        ->where([
            ['wsi.status', 'CLOSED'],
            ['wsi.service_type', '!=', 'NON-NUMBERING'],
            ['wsi.solution', '!=', 'CABUT/DEACTIVE'],
            ['wsi.related_to_gamas', 'NO'],
            ['wsi.summary', 'NOT LIKE', '%Z_PERMINTAAN%']
        ])
        ->whereDate('wsi.date_reported', date('Y-m-d'))
        ->groupBy('wmw.aliases')
        ->get();

        foreach ($witel as $val)
        {
            foreach ($query1 as $val_c1)
            {
                if (!empty($val_c1->aliases))
                {
                    if ($val_c1->aliases == $val->aliases)
                    {
                        $query[$val->aliases]['ao_tsel']    = $val_c1->ao_tsel;
                        $query[$val->aliases]['orbit_tsel'] = $val_c1->orbit_tsel;
                        $query[$val->aliases]['mo_tsel']    = $val_c1->mo_tsel;
                        $query[$val->aliases]['pda_tsel']   = $val_c1->pda_tsel;
                    }
                }
                else
                {
                    $query['NONE']['ao_tsel']    = $val_c1->ao_tsel;
                    $query['NONE']['orbit_tsel'] = $val_c1->orbit_tsel;
                    $query['NONE']['mo_tsel']    = $val_c1->mo_tsel;
                    $query['NONE']['pda_tsel']   = $val_c1->pda_tsel;
                }
            }

            foreach ($query2 as $val_c2)
            {
                if (!empty($val_c2->aliases))
                {
                    if ($val_c2->aliases == $val->aliases)
                    {
                        $query[$val->aliases]['ao_tlkm']    = $val_c2->ao_tlkm;
                        $query[$val->aliases]['orbit_tlkm'] = $val_c2->orbit_tlkm;
                        $query[$val->aliases]['mo_tlkm']    = $val_c2->mo_tlkm;
                        $query[$val->aliases]['pda_tlkm']   = $val_c2->pda_tlkm;
                    }
                }
                else
                {
                    $query['NONE']['ao_tlkm']    = $val_c2->ao_tlkm;

                    $query['NONE']['orbit_tlkm'] = $val_c2->orbit_tlkm;
                    $query['NONE']['mo_tlkm']    = $val_c2->mo_tlkm;
                    $query['NONE']['pda_tlkm']   = $val_c2->pda_tlkm;
                }
            }

            foreach ($query3 as $val_c3)
            {
                if (!empty($val_c3->aliases))
                {
                    if ($val_c3->aliases == $val->aliases)
                    {
                        $query[$val->aliases]['insera_b2c_vvip']     = $val_c3->insera_b2c_vvip;
                        $query[$val->aliases]['insera_b2c_diamond']  = $val_c3->insera_b2c_diamond;
                        $query[$val->aliases]['insera_b2c_platinum'] = $val_c3->insera_b2c_platinum;
                        $query[$val->aliases]['insera_b2c_gold']     = $val_c3->insera_b2c_gold;
                        $query[$val->aliases]['insera_b2c_reguler']  = $val_c3->insera_b2c_reguler;

                        $query[$val->aliases]['insera_b2b_des']      = $val_c3->insera_b2b_des;
                        $query[$val->aliases]['insera_b2b_dbs']      = $val_c3->insera_b2b_dbs;
                        $query[$val->aliases]['insera_b2b_dgs']      = $val_c3->insera_b2b_dgs;
                        $query[$val->aliases]['insera_b2b_dps']      = $val_c3->insera_b2b_dps;
                        $query[$val->aliases]['insera_b2b_dss']      = $val_c3->insera_b2b_dss;

                        $query[$val->aliases]['insera_b2b_reg']      = $val_c3->insera_b2b_reg;
                        $query[$val->aliases]['insera_b2b_dws']      = $val_c3->insera_b2b_dws;
                        $query[$val->aliases]['insera_b2b_taw']      = $val_c3->insera_b2b_taw;
                    }
                }
                else
                {
                    $query['NONE']['insera_b2b_des'] = $val_c3->insera_b2b_des;
                    $query['NONE']['insera_b2b_dbs'] = $val_c3->insera_b2b_dbs;
                    $query['NONE']['insera_b2b_dgs'] = $val_c3->insera_b2b_dgs;
                    $query['NONE']['insera_b2b_dps'] = $val_c3->insera_b2b_dps;
                    $query['NONE']['insera_b2b_dss'] = $val_c3->insera_b2b_dss;

                    $query['NONE']['insera_b2b_reg'] = $val_c3->insera_b2b_reg;
                    $query['NONE']['insera_b2b_dws'] = $val_c3->insera_b2b_dws;
                    $query['NONE']['insera_b2b_taw'] = $val_c3->insera_b2b_taw;
                }
            }
        }

        return $query;
    }

    public static function dashboard_produktif($start_date, $end_date)
    {
      return DB::connection('t1')
      ->select('
          SELECT
            md.sektor_prov AS sektor,
            (
              SELECT
                COUNT(*)
              FROM 1_2_employee emp
              LEFT JOIN absen ab ON emp.nik = ab.nik
              LEFT JOIN group_telegram gtt ON emp.mainsector = gtt.chat_id
              WHERE
                gtt.ket_posisi = "PROV" AND
                gtt.id NOT IN (97, 103) AND
                emp.posisi_id = 1 AND
                DATE(ab.date_created) = "'.date('Y-m-d').'" AND
                gtt.title = md.sektor_prov
            ) AS jml_teknisi,

            SUM(CASE WHEN pls.laporan_status IN ("NEED PROGRESS", "BERANGKAT", "TIBA", "OGP", "PROSES SETTING", "ODP FULL", "ODP LOSS", "INSERT TIANG") AND dt.jenis_layanan IN ("1P (Inet) [PSB]", "2P (Inet+Useetv) [PSB]", "1P (Inet) [PSB]", "2P (Inet+Useetv) [PSB]", "2P (Inet+Voice) [PSB]", "1P (Voice) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "1P (Inet) [BYOD]") THEN 1 ELSE 0 END) AS order_ao,
            SUM(CASE WHEN pls.laporan_status IN ("NEED PROGRESS", "BERANGKAT", "TIBA", "OGP", "PROSES SETTING", "ODP FULL", "ODP LOSS", "INSERT TIANG") AND dt.jenis_layanan = "1P (Inet) + Orbit [PSB]" THEN 1 ELSE 0 END) AS order_orbit,
            SUM(CASE WHEN pls.laporan_status IN ("NEED PROGRESS", "BERANGKAT", "TIBA", "OGP", "PROSES SETTING", "ODP FULL", "ODP LOSS", "INSERT TIANG") AND dt.jenis_layanan IN ("1P (Inet) [SettingOnly]", "ONT_PREMIUM", "STB 1st [ADDON]", "PLC 1st [ADDON]", "STB 2nd [ADDON]", "1P (Voice) [PSB]", "CABUT_NTE", "ONT_PREMIUM", "PLC 1st [ADDON]", "STB 2nd [ADDON]", "STB 3rd [ADDON]", "3P (Inet+Voice+Useetv) [SettingOnly]", "WIFI EXT 1st [ADDON]", "1P (Voice) [SettingOnly]") THEN 1 ELSE 0 END) AS order_addon,
            SUM(CASE WHEN pls.laporan_status IN ("NEED PROGRESS", "BERANGKAT", "TIBA", "OGP", "PROSES SETTING", "ODP FULL", "ODP LOSS", "INSERT TIANG") AND dt.jenis_layanan IN ("1P (Inet) [PDA]", "3P (Inet+Voice+Useetv) [PDA]", "1P (Inet) [PDA]", "2P (Inet+Voice) [PDA]", "2P (Inet+Useetv) [PDA]", "1P (Voice) [PDA]") THEN 1 ELSE 0 END) AS order_pda,

            SUM(CASE WHEN dps.orderStatus IN ("COMPLETED", "Completed (PS)") AND dt.jenis_layanan IN ("1P (Inet) [PSB]", "2P (Inet+Useetv) [PSB]", "1P (Inet) [PSB]", "2P (Inet+Useetv) [PSB]", "2P (Inet+Voice) [PSB]", "1P (Voice) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "1P (Inet) [BYOD]") THEN 1 ELSE 0 END) AS ps_ao,
            SUM(CASE WHEN dps.orderStatus IN ("COMPLETED", "Completed (PS)") AND dt.jenis_layanan = "1P (Inet) + Orbit [PSB]" THEN 1 ELSE 0 END) AS ps_orbit,
            SUM(CASE WHEN dps.orderStatus IN ("COMPLETED", "Completed (PS)") AND dt.jenis_layanan IN ("1P (Inet) [SettingOnly]", "ONT_PREMIUM", "STB 1st [ADDON]", "PLC 1st [ADDON]", "STB 2nd [ADDON]", "1P (Voice) [PSB]", "CABUT_NTE", "ONT_PREMIUM", "PLC 1st [ADDON]", "STB 2nd [ADDON]", "STB 3rd [ADDON]", "3P (Inet+Voice+Useetv) [SettingOnly]", "WIFI EXT 1st [ADDON]", "1P (Voice) [SettingOnly]") THEN 1 ELSE 0 END) AS ps_addon,
            SUM(CASE WHEN dps.orderStatus IN ("COMPLETED", "Completed (PS)") AND dt.jenis_layanan IN ("1P (Inet) [PDA]", "3P (Inet+Voice+Useetv) [PDA]", "1P (Inet) [PDA]", "2P (Inet+Voice) [PDA]", "2P (Inet+Useetv) [PDA]", "1P (Voice) [PDA]") THEN 1 ELSE 0 END) AS ps_pda,

            SUM(CASE WHEN pls.grup = "KP" THEN 1 ELSE 0 END) AS order_kp,
            SUM(CASE WHEN pls.grup = "KT" AND pls.laporan_status NOT IN ("ODP FULL", "ODP LOSS", "INSERT TIANG") THEN 1 ELSE 0 END) AS order_kt
          FROM Data_Pelanggan_Starclick dps
          LEFT JOIN maintenance_datel md ON dps.sto = md.sto
          LEFT JOIN dispatch_teknisi dt ON dps.orderIdInteger = dt.NO_ORDER
          LEFT JOIN psb_laporan pl ON dt.id = pl.id_tbl_mj
          LEFT JOIN psb_laporan_status pls ON pl.status_laporan = pls.laporan_status_id
          LEFT JOIN regu r ON dt.id_regu = r.id_regu
          LEFT JOIN group_telegram gt ON r.mainsector = gt.chat_id
          WHERE
            (pls.grup IS NOT NULL OR pls.grup NOT IN ("CABUT_NTE", "ONT_PREMIUM")) AND
            (DATE(dps.orderDate) BETWEEN "'.$start_date.'" AND "'.$end_date.'")
          GROUP BY md.sektor_prov
      ');
    }

    public static function dashboard_produktif_detail($sektor, $status, $start_date, $end_date)
    {
      $where_sektor = $where_status = '';

      if ($sektor != 'all')
      {
        if ($status != 'teknisi')
        {
          $where_sektor = 'AND md.sektor_prov = "'.$sektor.'"';
        }
        else
        {
          $where_sektor = 'AND gtt.title = "'.$sektor.'"';
        }
      }

      if ($status == 'teknisi')
      {
        $data = DB::connection('t1')
        ->select('
          SELECT
            gtt.title AS sektor,
            ab.date_created AS tgl_absen,
            emp.*
          FROM 1_2_employee emp
          LEFT JOIN absen ab ON emp.nik = ab.nik
          LEFT JOIN group_telegram gtt ON emp.mainsector = gtt.chat_id
          WHERE
            gtt.ket_posisi = "PROV" AND
            gtt.id NOT IN (97, 103) AND
            emp.posisi_id = 1 AND
            DATE(ab.date_created) = "'.date('Y-m-d').'"
            '.$where_sektor.'
        ');
      }
      else
      {
        switch ($status) {
          case 'order_ao':
              $where_status = 'AND pls.laporan_status IN ("NEED PROGRESS", "BERANGKAT", "TIBA", "OGP", "PROSES SETTING", "ODP FULL", "ODP LOSS", "INSERT TIANG") AND dt.jenis_layanan IN ("1P (Inet) [PSB]", "2P (Inet+Useetv) [PSB]", "1P (Inet) [PSB]", "2P (Inet+Useetv) [PSB]", "2P (Inet+Voice) [PSB]", "1P (Voice) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "1P (Inet) [BYOD]")';
            break;
          case 'order_orbit':
              $where_status = 'AND pls.laporan_status IN ("NEED PROGRESS", "BERANGKAT", "TIBA", "OGP", "PROSES SETTING", "ODP FULL", "ODP LOSS", "INSERT TIANG") AND dt.jenis_layanan = "1P (Inet) + Orbit [PSB]"';
            break;
          case 'order_addon':
              $where_status = 'AND pls.laporan_status IN ("NEED PROGRESS", "BERANGKAT", "TIBA", "OGP", "PROSES SETTING", "ODP FULL", "ODP LOSS", "INSERT TIANG") AND dt.jenis_layanan IN ("1P (Inet) [SettingOnly]", "ONT_PREMIUM", "STB 1st [ADDON]", "PLC 1st [ADDON]", "STB 2nd [ADDON]", "1P (Voice) [PSB]", "CABUT_NTE", "ONT_PREMIUM", "PLC 1st [ADDON]", "STB 2nd [ADDON]", "STB 3rd [ADDON]", "3P (Inet+Voice+Useetv) [SettingOnly]", "WIFI EXT 1st [ADDON]", "1P (Voice) [SettingOnly]")';
            break;
          case 'order_pda':
              $where_status = 'AND pls.laporan_status IN ("NEED PROGRESS", "BERANGKAT", "TIBA", "OGP", "PROSES SETTING", "ODP FULL", "ODP LOSS", "INSERT TIANG") AND dt.jenis_layanan IN ("1P (Inet) [PDA]", "3P (Inet+Voice+Useetv) [PDA]", "1P (Inet) [PDA]", "2P (Inet+Voice) [PDA]", "2P (Inet+Useetv) [PDA]", "1P (Voice) [PDA]")';
            break;
  
          case 'ps_ao':
              $where_status = 'AND dps.orderStatus IN ("COMPLETED", "Completed (PS)") AND dt.jenis_layanan IN ("1P (Inet) [PSB]", "2P (Inet+Useetv) [PSB]", "1P (Inet) [PSB]", "2P (Inet+Useetv) [PSB]", "2P (Inet+Voice) [PSB]", "1P (Voice) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "3P (Inet+Voice+Useetv) [PSB]", "1P (Inet) [BYOD]")';
            break;
          case 'ps_orbit':
              $where_status = 'AND dps.orderStatus IN ("COMPLETED", "Completed (PS)") AND dt.jenis_layanan = "1P (Inet) + Orbit [PSB]"';
            break;
          case 'ps_addon':
              $where_status = 'AND dps.orderStatus IN ("COMPLETED", "Completed (PS)") AND dt.jenis_layanan IN ("1P (Inet) [SettingOnly]", "ONT_PREMIUM", "STB 1st [ADDON]", "PLC 1st [ADDON]", "STB 2nd [ADDON]", "1P (Voice) [PSB]", "CABUT_NTE", "ONT_PREMIUM", "PLC 1st [ADDON]", "STB 2nd [ADDON]", "STB 3rd [ADDON]", "3P (Inet+Voice+Useetv) [SettingOnly]", "WIFI EXT 1st [ADDON]", "1P (Voice) [SettingOnly]")';
            break;
          case 'ps_pda':
              $where_status = 'AND dps.orderStatus IN ("COMPLETED", "Completed (PS)") AND dt.jenis_layanan IN ("1P (Inet) [PDA]", "3P (Inet+Voice+Useetv) [PDA]", "1P (Inet) [PDA]", "2P (Inet+Voice) [PDA]", "2P (Inet+Useetv) [PDA]", "1P (Voice) [PDA]")';
            break;
  
          case 'order_kp':
              $where_status = 'AND pls.grup = "KP"';
            break;
  
          case 'order_kt':
              $where_status = 'AND pls.grup = "KT" AND pls.laporan_status NOT IN ("ODP FULL", "ODP LOSS", "INSERT TIANG")';
            break;
          default:
              # code...
            break;
        }
  
        $data = DB::connection('t1')
        ->select('
          SELECT
            dps.sto,
            md.sektor_prov AS sektor,
            dps.orderId,
            dps.orderName,
            dps.orderDate,
            dps.orderDatePS,
            dps.orderStatus,
            dps.jenisPsb,
            dps.provider
          FROM Data_Pelanggan_Starclick dps
          LEFT JOIN maintenance_datel md ON dps.sto = md.sto
          LEFT JOIN dispatch_teknisi dt ON dps.orderIdInteger = dt.NO_ORDER
          LEFT JOIN psb_laporan pl ON dt.id = pl.id_tbl_mj
          LEFT JOIN psb_laporan_status pls ON pl.status_laporan = pls.laporan_status_id
          LEFT JOIN regu r ON dt.id_regu = r.id_regu
          LEFT JOIN group_telegram gt ON r.mainsector = gt.chat_id
          WHERE
            (pls.grup IS NOT NULL OR pls.grup NOT IN ("CABUT_NTE", "ONT_PREMIUM"))
            '.$where_sektor.'
            '.$where_status.'
            AND (DATE(dps.orderDate) BETWEEN "'.$start_date.'" AND "'.$end_date.'")
        ');
      }

      return $data;
    }

    public static function getListTickets($witel,$status)
    {
        if ($witel == "ALL")
        {
            $getWitel = '';
        }
        else
        {
            $getWitel = 'a.witel = "'.$witel.'" AND ';
        }

        $query = DB::SELECT('
            select 
            *
            from 
            wmcr_source_insera a 
            where 
            a.status IN ("ANALYSIS","BACKEND")            
            AND
            '.$getWitel.'
            a.source_ticket = "CUSTOMER" AND 
            a.summary NOT LIKE "%Z_PERMINTAAN%" AND 
            a.customer_type IN ("HVC_GOLD","HVC_PLATINUM","HVC_DIAMOND")
            order by a.reported_date DESC
        ');
        return $query; 
    }

    public static function getWitel($regional)
    {
        $query = DB::SELECT('select
        * 
        from 
        wmcr_master_witel a
        where 
        a.regional_id = 6 and 
        a.id <> 7');
        return $query;
    }

    public static function getDashboardTTRHVC($witel)
    {
        $query = DB::SELECT('
        SELECT 
        FROM 
        wmcr_source_insera a 
        
        ');
    }

    public static function getCountTicket($witel)
    {
        $query = DB::SELECT('
        SELECT 
        sum(case when (TIMESTAMPDIFF( HOUR , a.reported_date, "'.date('Y-m-d H:i:s').'") > 3) then 1 else 0 end) as lebih3jam,
        sum(case when (TIMESTAMPDIFF( HOUR , a.reported_date, "'.date('Y-m-d H:i:s').'") > 2 AND TIMESTAMPDIFF( HOUR , a.reported_date, "'.date('Y-m-d H:i:s').'") <= 3) then 1 else 0 end) as dua3jam,
        sum(case when (TIMESTAMPDIFF( HOUR , a.reported_date, "'.date('Y-m-d H:i:s').'") > 1 AND TIMESTAMPDIFF( HOUR , a.reported_date, "'.date('Y-m-d H:i:s').'") <= 2) then 1 else 0 end) as satu2jam,
        sum(case when (TIMESTAMPDIFF( HOUR , a.reported_date, "'.date('Y-m-d H:i:s').'") > 0 AND TIMESTAMPDIFF( HOUR , a.reported_date, "'.date('Y-m-d H:i:s').'") <= 1) then 1 else 0 end) as nol1jam,
        count(*) as total 
        from 
            wmcr_source_insera a 
            where 
            a.status IN ("ANALYSIS","BACKEND")            
            AND
            a.witel = "'.$witel.'" AND 
            a.source_ticket = "CUSTOMER" AND 
            a.summary NOT LIKE "%Z_PERMINTAAN%" AND 
            a.customer_type IN ("HVC_GOLD","HVC_PLATINUM","HVC_DIAMOND")
            order by a.reported_date DESC
        ');
        return $query;
    }
}

?>