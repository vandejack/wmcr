<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ScheduleModel
{
    public static function sectorCheck($tech,$sector,$periode){
        return DB::table('wmcr_sector_schedule')
                    ->where('sector_id', $sector)
                    ->where('technician', $tech)
                    ->where('date', $periode)
                    ->get();
    }
    public static function scheduleInsert($tech,$sectorID,$tgl){
        return DB::table('wmcr_sector_schedule')
                    ->insert([
                        'technician'    => $tech,
                        'sector_id'     => $sectorID,
                        'date'          => $tgl,
                        'created_by'    => 'SYSTEM',
                        'MH_Available'  => 8,
                        'status'        => 1
                    ]);
    }
    public static function scheduleGet($tech,$sector,$periode){
        return DB::SELECT('select * from wmcr_sector_schedule a where a.technician = "'.$tech.'" AND a.sector_id = "'.$sector.'" AND date(`date`) = "'.$periode.'" ');
    }
    public static function scheduleTeamGetbyDay($sector,$periode,$status,$approval,$isHold){
        if ($approval=="ALL"){
            $whereApproval = '';
        } else {
            $whereApproval = 'AND a.approval = "'.$approval.'"';
        }
        if ($isHold=="ALL"){
            $whereisHold = '';
        } else {
            $whereisHold = 'a.isHold = '.$isHold.' AND ';
        }
        return DB::SELECT('select *,b.id as teamID from wmcr_sector_team b left join wmcr_sector_schedule a ON b.technician1 = a.technician where '.$whereisHold.' a.status = '.$status.' '.$whereApproval.' AND a.sector_id = "'.$sector.'" AND date(`date`) = "'.$periode.'" ');
    }
    public static function scheduleEmployeeGetbyDay($sector,$periode,$status,$approval,$isHold){
        if ($approval=="ALL"){
            $whereApproval = '';
        } else {
            $whereApproval = 'AND a.approval = "'.$approval.'"';
        }
        if ($isHold=="ALL"){
            $whereisHold = '';
        } else {
            $whereisHold = 'a.isHold = '.$isHold.' AND ';
        }
        return DB::SELECT('select * from  wmcr_sector_schedule a where '.$whereisHold.' a.status = '.$status.' '.$whereApproval.' AND a.sector_id = "'.$sector.'" AND date(`date`) = "'.$periode.'" ');
    }
    public static function scheduleSectorbyDay($sector,$periode){
        return DB::SELECT('select a.`date`,sum(case when a.status=1 then 1 else 0 end) as jumlah_hadir,sum(case when a.status=2 then 1 else 0 end) as jumlah_tidak_hadir from wmcr_sector_schedule a where a.sector_id = "'.$sector.'" AND a.`date` like "'.$periode.'%" group by a.`date`');
    }
    public static function employeeSector($sector){
        return DB::SELECT('select * from wmcr_employee a where a.sector_id = "'.$sector.'"');
    }
    public static function scheduleStatusGet(){
        return DB::table('wmcr_sector_schedule_status')->get();
    }
    public static function scheduleGetbyID($id){
        return DB::table('wmcr_sector_schedule')
                ->where('id',$id)
                ->first();
    }
    public static function list($sector,$status,$periode,$ishold,$approval){
        if ($ishold=="ALL"){
            $whereisHold = '';
        } else {
            $whereisHold = ' AND a.isHold = '.$ishold;
        }
        if ($approval=="ALL"){
            $whereApproval = '';
        } else {
            $whereApproval = ' AND a.approval = '.$approval;
        }
        $query = DB::SELECT('SELECT 
                                a.*,a.`date` as periode,b.name as employeeName, c.name as statusName, d.name as sector, e.name as approvalName,b.nik
                            FROM wmcr_sector_schedule a 
                                LEFT JOIN wmcr_employee b ON a.technician = b.nik 
                                LEFT JOIN wmcr_sector_schedule_status c ON a.status=c.status_id 
                                LEFT JOIN wmcr_sector d ON b.sector_id = d.id
                                LEFT JOIN wmcr_sector_schedule_approval_status e ON a.approval = e.id
                            WHERE b.sector_id = "'.$sector.'" AND DATE(a.`date`) = "'.$periode.'" AND a.status = "'.$status.'"  '.$whereisHold.' '.$whereApproval.' ');
        return $query;
    }   
    
}