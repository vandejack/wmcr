<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\DashboardModel;

date_default_timezone_set("Asia/Makassar");

class DashboardController extends Controller
{
    public function TicketsMonitoring(){
        $witel = DashboardModel::getWitel('6');
        $dataWitel = array();
        foreach ($witel as $w){
            $getData = DashboardModel::getCountTicket($w->name);
            $dataWitel[$w->name]['nol1jam'] = $getData[0]->nol1jam;
            $dataWitel[$w->name]['satu2jam'] = $getData[0]->satu2jam;
            $dataWitel[$w->name]['dua3jam'] = $getData[0]->dua3jam;
            $dataWitel[$w->name]['lebih3jam'] = $getData[0]->lebih3jam;
            $dataWitel[$w->name]['total'] = $getData[0]->total;
        }
        return view('dashboard.monitoring',compact('witel','dataWitel'));
    }

    public function TicketsMonitoringList($witel,$status){
        $list = DashboardModel::getListTickets($witel,$status);
        return view('dashboard.monitoringList',compact('witel','status','list'));
    }

}