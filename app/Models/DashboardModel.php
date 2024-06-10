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

    public static function kpro_provi($witel)
    {
        $witel = DB::table('wmcr_master_witel')->get();

        // TSEL
        $query1 = DB::table('wmcr_source_kpro_provi')
        ->select(DB::raw('
            witel,
            SUM(CASE WHEN status_resume = "Completed (PS)" AND jenispsb = "AO" AND provider = "PL-TSEL" AND product = "INDIHOME" THEN 1 ELSE 0 END) AS ao_tsel,
            SUM(CASE WHEN status_resume = "Completed (PS)" AND jenispsb = "AO" AND provider = "PL-TSEL" AND product = "ORBIT" THEN 1 ELSE 0 END) AS orbit_tsel,
            SUM(CASE WHEN status_resume = "Completed (PS)" AND jenispsb = "MO" AND provider = "PL-TSEL" AND product = "INDIHOME" THEN 1 ELSE 0 END) AS mo_tsel,
            SUM(CASE WHEN status_resume = "Completed (PS)" AND jenispsb IN ("PDA", "PDA LOCAL") AND provider = "PL-TSEL" AND product = "INDIHOME" THEN 1 ELSE 0 END) AS pda_tsel
        '))
        ->whereDate('last_updated_date', date('Y-m-d'))
        ->groupBy('witel')
        ->get();

        // TLKM
        $query2 = DB::table('wmcr_source_kpro_provi_survey')
        ->select(DB::raw('
            witel,
            SUM(CASE WHEN status_resume = "Completed (PS)" AND jenispsb = "AO" AND provider = "PL-TSEL" AND product = "INDIHOME" THEN 1 ELSE 0 END) AS ao_tlkm,
            SUM(CASE WHEN status_resume = "Completed (PS)" AND jenispsb = "AO" AND provider = "PL-TSEL" AND product = "ORBIT" THEN 1 ELSE 0 END) AS orbit_tlkm,
            SUM(CASE WHEN status_resume = "Completed (PS)" AND jenispsb = "MO" AND provider = "PL-TSEL" AND product = "INDIHOME" THEN 1 ELSE 0 END) AS mo_tlkm,
            SUM(CASE WHEN status_resume = "Completed (PS)" AND jenispsb IN ("PDA", "PDA LOCAL") AND provider = "PL-TSEL" AND product = "INDIHOME" THEN 1 ELSE 0 END) AS pda_tlkm
        '))
        ->whereDate('last_updated_date', date('Y-m-d'))
        ->groupBy('witel')
        ->get();

        foreach ($witel as $val)
        {
            foreach ($query1 as $val_c1)
            {
                if (!empty($val_c1->witel))
                {
                    if ($val_c1->witel == $val->aliases)
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
                if (!empty($val_c2->witel))
                {
                    if ($val_c2->witel == $val->aliases)
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
        }

        return $query;
    }

    public static function getListTickets($witel,$status){
        if ($witel=="ALL") {
            $getWitel = '';
        } else {
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

    public static function getWitel($regional){
        $query = DB::SELECT('select
        * 
        from 
        wmcr_master_witel a
        where 
        a.regional_id = 6 and 
        a.id <> 7');
        return $query;
    }

    public static function getDashboardTTRHVC($witel){
        $query = DB::SELECT('
        SELECT 
        FROM 
        wmcr_source_insera a 
        
        ');
    }

    public static function getCountTicket($witel){
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