<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class TechModel
{
    public static function orderList($nik,$periode)
    {

        $queryx = DB::table('wmcr_order_dispatch as a')
            ->select('a.*', 'b.*', 'c.*', 'd.*', 'a.id as dispatchID','e.name as statusName')
            ->leftJoin('wmcr_sector_team as b', 'a.sector_team_id', '=', 'b.id')
            ->leftJoin('wmcr_order_basket as c', 'a.order_id', '=', 'c.source_id')
            ->leftJoin('wmcr_order_type as d', 'c.order_type_id', '=', 'd.id')
            ->leftJoin('wmcr_order_status as e','a.status','=','e.id')
            ->whereDate('a.assigned_date', '=', $periode)
            ->where(function ($query) use ($nik) {
                $query->where('b.technician1', '=', $nik)
                    ->orWhere('b.technician2', '=', $nik); 
            })
            ->get();
        return $queryx;
    }

    public static function getOrderbyID($id){
        return DB::table('wmcr_order_dispatch as a')
                    ->leftJoin('wmcr_order_basket as b','a.order_id','=','b.source_id')
                    ->leftJoin('wmcr_order_type as c','b.order_type_id','=','c.id')
                    ->select('a.*','c.name as orderType','c.source')
                    ->where('a.id',$id) 
                    ->first();
    }

    public static function getOrderDetailbyID($source,$id){
        return DB::table($source)
        ->where('order_id',$id)
        ->first();
    }

    public static function getStatusSub($id){
        return DB::table('wmcr_order_status_sub')
                ->where('status_id',$id)
                ->get();
    }
    public static function getStatus(){
        return DB::table('wmcr_order_status')
                ->where('id','!=','1')
                ->where('id','!=','0')
                ->get();
    }

    public static function getKehadiran($nik){
        return DB::table('wmcr_sector_schedule')
                        ->where('technician',$nik)
                        ->where('date',date('Y-m-d'))
                        ->first();
    }

}