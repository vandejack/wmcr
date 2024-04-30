<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Models\OrderModel;

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
}
?>