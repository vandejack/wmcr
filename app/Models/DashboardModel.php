<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class DashboardModel
{
    public static function ttr_hvc()
    {
        return DB::table('wmcr_source_insera')
        ->select(DB::raw('
            witel,
            
            SUM(CASE WHEN status IN ("NEW", "DRAFT", "ANALYSIS", "PENDING", "BACKEND") AND (TIMESTAMPDIFF( HOUR , reported_date, "'.date('Y-m-d H:i:s').'") >= 0 AND TIMESTAMPDIFF( HOUR , reported_date, "'.date('Y-m-d H:i:s').'") < 1) THEN 1 ELSE 0 END) AS ttr_0hours,
            SUM(CASE WHEN status IN ("NEW", "DRAFT", "ANALYSIS", "PENDING", "BACKEND") AND (TIMESTAMPDIFF( HOUR , reported_date, "'.date('Y-m-d H:i:s').'") >= 1 AND TIMESTAMPDIFF( HOUR , reported_date, "'.date('Y-m-d H:i:s').'") < 2) THEN 1 ELSE 0 END) AS ttr_1hours,
            SUM(CASE WHEN status IN ("NEW", "DRAFT", "ANALYSIS", "PENDING", "BACKEND") AND (TIMESTAMPDIFF( HOUR , reported_date, "'.date('Y-m-d H:i:s').'") >= 2 AND TIMESTAMPDIFF( HOUR , reported_date, "'.date('Y-m-d H:i:s').'") < 3) THEN 1 ELSE 0 END) AS ttr_2hours,
            SUM(CASE WHEN status IN ("NEW", "DRAFT", "ANALYSIS", "PENDING", "BACKEND") AND (TIMESTAMPDIFF( HOUR , reported_date, "'.date('Y-m-d H:i:s').'") >= 3) THEN 1 ELSE 0 END) AS ttr_3hours,

            SUM(CASE WHEN status IN ("FINALCHECK", "RESOLVED", "MEDIACARE", "SALAMSIM", "CLOSED") AND (TIMESTAMPDIFF( HOUR , reported_date, status_date) < 3) THEN 1 ELSE 0 END) AS ttr_comply,
            SUM(CASE WHEN status IN ("FINALCHECK", "RESOLVED", "MEDIACARE", "SALAMSIM", "CLOSED") AND (TIMESTAMPDIFF( HOUR , reported_date, status_date) >= 3) THEN 1 ELSE 0 END) AS ttr_notcomply
        '))
        ->whereIn('customer_type', ['HVC_GOLD', 'HVC_PLATINUM', 'HVC_DIAMOND'])
        ->where([
            ['source_ticket', 'CUSTOMER'],
            ['service_type', 'NON-NUMBERING'],
            ['solution', '!=', 'CABUT/DEACTIVE'],
            ['summary', 'NOT LIKE', '%Z_PERMINTAAN%'],
            ['related_to_gamas', 'NO']
        ])
        ->whereDate('date_reported', date('Y-m-d'))
        ->groupBy('witel')
        ->get();
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