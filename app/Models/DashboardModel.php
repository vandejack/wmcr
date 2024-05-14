<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class DashboardModel
{
    public static function getListTickets($witel,$status){
        $query = DB::SELECT('
            select 
            *
            from 
            wmcr_source_insera a 
            where 
            a.status IN ("ANALYSIS","BACKEND")            
            AND
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