<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DashboardModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Models\OrderModel;
use App\Models\SectorModel;
use App\Models\EmployeeModel;
use App\Models\ScheduleModel;
use App\Models\MasterModel;
use App\Models\Telegram;
use Carbon\Carbon;

date_default_timezone_set("Asia/Makassar");

class SchedulingController extends Controller
{
    public function trial(){
        return view('trial.template');
    }

    public function manage($sector,$periode){
        $sectorList     = SectorModel::show('list',1);
        $getWitel  = MasterModel::show('witel');
        $witel = 1;
        #check periode
        $exp = explode('-',$periode);
        if (count($exp)>1){
            $employee       = EmployeeModel::show_with_id('list',$sector);
            $days           = cal_days_in_month(CAL_GREGORIAN,$exp[1], $exp[0]);
            #get schedule
            if ($sector<>"ALL"){
                $jml_employee   = count($employee);
                foreach ($employee as $r) {
                    for ($x=1;$x<=$days;$x++){
                        $input = $periode.'-'.$x;
                        $date = strtotime($input);
                        $tech       = $r->nik;
                        $sectorID   = $r->sector_id;
                        $tgl        = date('Y-m-d',$date);
                        $scheduleCheck  = ScheduleModel::sectorCheck($tech,$sectorID,$tgl);
                        if (count($scheduleCheck)>0){
                         
                        } else {
                            $scheduleInsert = ScheduleModel::scheduleInsert($tech,$sectorID,$tgl);
                        }
                    }
                }
                $scheduleData = array();
                foreach ($employee as $n => $r){
                    for ($x=1;$x<=$days;$x++){
                        $input = $periode.'-'.$x;
                        $date = strtotime($input);
                        $tech = $r->nik;
                        $scheduleGet = ScheduleModel::scheduleGet($tech,$sector,date('Y-m-d',$date));
                        foreach ($scheduleGet as $s){
                            $scheduleData[$r->nik][date('Y-m-d',$date)]['status'] = $s->status;
                            $scheduleData[$r->nik][date('Y-m-d',$date)]['id'] = $s->id;
                        }
                    }
                }
                $scheduleSectorbyDay = ScheduleModel::scheduleSectorbyDay($sector,$periode);
                $scheduleSectorDaily = array();
                foreach ($scheduleSectorbyDay as $n => $r){
                    $scheduleSectorDaily[$r->date]['hadir'] = $r->jumlah_hadir;
                    $scheduleSectorDaily[$r->date]['tidak_hadir'] = $r->jumlah_tidak_hadir;
                }
            } else {
                $employee               = NULL;
                $scheduleData           = NULL;
                $scheduleSectorDaily    = NULL;
            }
            return view('schedule.manage',compact('witel','getWitel','sectorList','employee','periode','days','scheduleData','scheduleSectorDaily'));
        } else {
            echo "FORMAT PERIODE SALAH";
        }
    }

    public function update($id){
        $scheduleStatus = ScheduleModel::scheduleStatusGet();
        $scheduleGetbyID = ScheduleModel::scheduleGetbyID($id);

        // data 
        $nik = $scheduleGetbyID->technician;
        $periode = $scheduleGetbyID->date;
        $status = $scheduleGetbyID->status;
        $defaultSector = $scheduleGetbyID->defaultSector;
        $bantekWitel = $scheduleGetbyID->bantekWitel;
        $bantekSector = $scheduleGetbyID->bantekSector;

        
        // get witel 
        $getWitel  = MasterModel::show('witel');

        // get sector by witel for bantek
        $getSectorbyWitel = MasterModel::getSectorbyWitelwithExcept($bantekWitel,$defaultSector);


        $back_url =  url()->previous();
        return view('schedule.update',compact('getWitel','nik','periode','scheduleStatus','status','back_url','defaultSector','bantekWitel','bantekSector','getSectorbyWitel'));
    }

    public function updatePost(Request $request, $id){
        $exec = DB::table('wmcr_sector_schedule')
                    ->where('id',$id)
                    ->update([
                        'status' => $request->input('status'),
                        'sector_id' => $request->input('sektor'),
                        'schedule_update_by' => session('auth')->nik,
                        'schedule_update_time' => Carbon::now()
                    ]);
        // return redirect('/schedule/manage/1/2024-07');
        return redirect($request->back_url);


    }

    public function list($sector,$status,$periode,$ishold,$approval){
        $query = ScheduleModel::list($sector,$status,$periode,$ishold,$approval);
        return view('order.listEmployee',compact('status','periode','sector','ishold','query','approval')); 
    }

    public function approval($id,$status){
        $query = DB::table('wmcr_sector_schedule')
                    ->where('id',$id)
                    ->update([
                        'approval' => $status,
                        'approval_time' => date('Y-m-d H:i:s')
                    ]);
                    return redirect()->back()->with('alerts', [
                        ['type' => 'success', 'text' => 'Approval Berhasil']
                    ]);
    }

}