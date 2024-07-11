<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DashboardModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Models\OrderModel;
use App\Models\Telegram;

date_default_timezone_set("Asia/Makassar");

class OrderController extends Controller
{
    public function ticket($id)
    {
        return view('order.ticket', compact('id', 'data'));
    }

    public function ticket_post(Request $req)
    {
        dd($req->all());
    }

    public function search()
    {
        $type = $id = '';

        $data = [];

        return view('order.search', compact('type', 'id', 'data'));
    }

    public function search_post(Request $req)
    {
        if ($req->has('search') == true)
        {
            $type = $req->input('type');
            $id   = $req->input('search');

            $data = OrderModel::search_post($type, $id);

            if (count($data) > 0)
            {
                $data = $data;
            }
            else
            {
                $data = [];
            }

            return view('order.search', compact('type', 'id', 'data'));
        }
    }

    public function matrix()
    {
        return view('order.matrix');
    }

    public function matrix_post(Request $req)
    {
        dd($req->all());
    }

    public function undispatch()
    {
        $start_date = date('Y-m-d', strtotime('-1 days'));
        $end_date   = date('Y-m-d');

        return view('order.undispatch', compact('start_date', 'end_date'));
    }

    public function undispatch_post(Request $req)
    {
        if ($req->has('start_date') == true || $req->has('end_date') == true)
        {
            $start_date = $req->input('start_date');
            $end_date   = $req->input('end_date');

            return view('order.undispatch', compact('start_date', 'end_date'));
        }
    }

    public function undispatch_detail()
    {
        $area       = Input::get('area');
        $order      = Input::get('order');
        $start_date = Input::get('start_date');
        $end_date   = Input::get('end_date');

        $order_tag  = OrderModel::order_tag();

        return view('order.undispatch_detail', compact('area', 'order', 'start_date', 'end_date', 'order_tag'));
    }

    public static function alert_ttr_customer($witel = null, $status = null)
    {
        $data = DashboardModel::getListTickets($witel, $status);

        foreach ($data as $k => $v)
        {
            $new_time = date("Y-m-d H:i:s", strtotime('+3 hours', strtotime($v->reported_date)));
            $datenow  = date('Y-m-d H:i:s');
            $selisih  = strtotime($new_time) - strtotime($datenow);
            $hari     = floor( ($selisih / (60 * 60 * 24) ) * 24); // Menghitung selisih dalam hari
            $jam      = floor( ($selisih % (60 * 60 * 24) ) / (60 * 60)); // Menghitung selisih dalam jam
            $menit    = floor( ($selisih % (60 * 60) ) / 60); // Menghitung selisih dalam menit
            $jam_new  = $hari+$jam;

            if ($jam_new >= 0)
            {
                $message = "Alert TTR 3 Hours\n\n";
                $message .= "$v->incident | $v->status | $jam_new Jam $menit Menit";
                if ($witel == 'KALSEL')
                {
                    if ($jam_new >= 1 && $jam_new <= 2)
                    {
                        // kirim ke TL / SM
                        $chat_ids = ['401791818'];
                    }
                    else
                    {
                        // kirim ke AstMgr / Mgr
                        $chat_ids = ['401791818'];
                    }
                }
                else if ($witel == 'BALIKPAPAN')
                {
                    if ($jam_new >= 1 && $jam_new <= 2)
                    {
                        // kirim ke TL / SM
                        $chat_ids = ['401791818'];
                    }
                    else
                    {
                        // kirim ke AstMgr / Mgr
                        $chat_ids = ['401791818'];
                    }
                }
                else if ($witel == 'KALBAR')
                {
                    if ($jam_new >= 1 && $jam_new <= 2)
                    {
                        // kirim ke TL / SM
                        $chat_ids = ['401791818'];
                    }
                    else
                    {
                        // kirim ke AstMgr / Mgr
                        $chat_ids = ['401791818'];
                    }
                }
                else if ($witel == 'KALTENG')
                {
                    if ($jam_new >= 1 && $jam_new <= 2)
                    {
                        // kirim ke TL / SM
                        $chat_ids = ['401791818'];
                    }
                    else
                    {
                        // kirim ke AstMgr / Mgr
                        $chat_ids = ['401791818'];
                    }
                }

                foreach ($chat_ids as $chat_id)
                {
                    Telegram::sendMessage($chat_id, "<code>$message</code>");
                }

                print_r($message);
            }
        }
    }

    public static function starclick_to_basket($witel, $tipe, $date)
    {
        $data = DB::table('wmcr_source_starclick AS wss')
        ->leftJoin('wmcr_master_witel AS wmw', 'wss.witel', '=', 'wmw.name')
        ->leftJoin('wmcr_sector_alpro AS wsa', 'wss.odp_name', '=', 'wsa.name')
        ->select(DB::raw('
            wmw.id AS witel_id,
            wsa.sector_id,
            wss.order_id,
            SUM(CASE WHEN wss.jenis_psb LIKE "AO%" THEN 1 ELSE 0 END) AS is_ao_tsel,
            SUM(CASE WHEN wss.status_resume = "MIE - SEND SURVEY " THEN 1 ELSE 0 END) AS is_survey,
            SUM(CASE WHEN wss.jenis_psb LIKE "PDA%" THEN 1 ELSE 0 END) AS is_pda,
            SUM(CASE WHEN wss.package_name LIKE "%ORBIT%" THEN 1 ELSE 0 END) AS is_orbit
        '))
        ->where([
            ['wss.witel', '=', $witel],
            ['wss.tipe_order', '=', $tipe]
        ])
        ->whereDate('wss.order_date', $date)
        ->whereIn('wss.sto', ['PLE', 'BTB', 'TKI'])
        ->whereNotNull('wsa.name')
        ->groupBy('wmw.id', 'wsa.sector_id', 'wss.order_id')
        ->get();

        foreach ($data as $k => $v)
        {
            if ($v->is_ao_tsel == 1)
            {
                DB::table('wmcr_order_basket')
                ->updateOrInsert(
                    ['source_id' => $v->order_id],
                    [
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => 1
                    ]
                );
            }

            if ($v->is_survey == 1)
            {
                DB::table('wmcr_order_basket')
                ->updateOrInsert(
                    ['source_id' => $v->order_id],
                    [
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => 2
                    ]
                );
            }

            if ($v->is_pda == 1)
            {
                DB::table('wmcr_order_basket')
                ->updateOrInsert(
                    ['source_id' => $v->order_id],
                    [
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => 3
                    ]
                );
            }

            if ($v->is_orbit == 1)
            {
                DB::table('wmcr_order_basket')
                ->updateOrInsert(
                    ['source_id' => $v->order_id],
                    [
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => 4
                    ]
                );
            }

            print_r("$v->order_id (ao tsel = $v->is_ao_tsel) (survey = $v->is_survey) (pda = $v->is_pda) (orbit = $v->is_orbit) \n");
        }

        return response()->json(['message' => 'Update completed!']);
    }

    public static function insera_to_basket($witel, $date)
    {
        $data = DB::table('wmcr_source_insera AS wsi')
        ->leftJoin('wmcr_master_witel AS wmw', 'wsi.witel', '=', 'wmw.name')
        ->leftJoin('wmcr_sector_alpro AS wsa', 'wsi.odp_name', '=', 'wsa.name')
        ->select(DB::raw('
            wmw.id AS witel_id,
            wsa.sector_id,
            wsi.incident,
            wsi.incident_id,
            SUM(CASE WHEN wsi.customer_type = "REGULER" AND wsi.customer_segment IN ("DCS", "PL-TSEL") THEN 1 ELSE 0 END) AS is_reguler_b2c,
            SUM(CASE WHEN wsi.customer_type = "REGULER" AND wsi.customer_segment IN ("DES", "DBS", "DGS", "DPS", "DSS", "REG", "DWS", "TAW") THEN 1 ELSE 0 END) AS is_reguler_b2b,
            SUM(CASE WHEN wsi.source_ticket IN ("PROACTIVE_TICKET", "PROACTIVE") AND wsi.reported_by = "PROACTIVE_TICKET" THEN 1 ELSE 0 END) AS is_procare,
            SUM(CASE WHEN wsi.source_ticket IN ("PROACTIVE_TICKET", "PROACTIVE") AND wsi.reported_by LIKE "PROMAN%" THEN 1 ELSE 0 END) AS is_unspec,
            SUM(CASE WHEN wsi.guarante_status = "GUARANTEE" THEN 1 ELSE 0 END) AS is_ffg
        '))
        ->where([
            ['wsi.incident', 'LIKE', 'INC%'],
            ['wsi.witel', $witel],
        ])
        ->whereDate('wsi.date_reported', $date)
        ->whereIn('wsi.workzone', ['PLE', 'BTB', 'TKI'])
        ->whereNotNull('wsa.name')
        ->groupBy('wmw.id', 'wsa.sector_id', 'wsi.incident')
        ->get();

        foreach ($data as $v)
        {
            if ($v->is_reguler_b2c == 1)
            {
                DB::table('wmcr_order_basket')
                ->updateOrInsert(
                    ['source_id' => $v->incident_id],
                    [
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => 5
                    ]
                );
            }

            if ($v->is_reguler_b2b == 1)
            {
                DB::table('wmcr_order_basket')
                ->updateOrInsert(
                    ['source_id' => $v->incident_id],
                    [
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => 6
                    ]
                );
            }

            if ($v->is_procare == 1)
            {
                DB::table('wmcr_order_basket')
                ->updateOrInsert(
                    ['source_id' => $v->incident_id],
                    [
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => 7
                    ]
                );
            }

            if ($v->is_unspec == 1)
            {
                DB::table('wmcr_order_basket')
                ->updateOrInsert(
                    ['source_id' => $v->incident_id],
                    [
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => 8
                    ]
                );
            }

            if ($v->is_ffg == 1)
            {
                DB::table('wmcr_order_basket')
                ->updateOrInsert(
                    ['source_id' => $v->incident_id],
                    [
                        'witel_id'      => $v->witel_id,
                        'sector_id'     => $v->sector_id,
                        'order_type_id' => 9
                    ]
                );
            }

            print_r("$v->incident (reguler b2c = $v->is_reguler_b2c) (reguler b2b = $v->is_reguler_b2b) (procare = $v->is_procare) (unspec = $v->is_unspec) (ffg = $v->is_ffg) \n");
        }

        return response()->json(['message' => 'Update completed!']);
    }


}
?>