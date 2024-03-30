<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\OrderModel;

date_default_timezone_set("Asia/Makassar");

class OrderController extends Controller
{
    public function ticket($id)
    {
        return view('order.ticket', compact('id', 'data'));
    }

    public function ticketPost(Request $req)
    {
        dd($req->all());
    }

    public function search()
    {
        $type = $id = '';

        $data = [];

        return view('order.search', compact('type', 'id', 'data'));
    }

    public function searchPost(Request $req)
    {
        if ($req->has('search') == true)
        {
            $type = $req->input('type');
            $id   = $req->input('search');

            $data = OrderModel::searchPost($type, $id);

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

    public function matrixPost(Request $req)
    {
        dd($req->all());
    }

    public function undispatch()
    {
        $start_date = date('Y-m-d', strtotime('-1 days'));
        $end_date = date('Y-m-d');

        $data = [];

        return view('order.undispatch', compact('start_date', 'end_date', 'data'));
    }

    public function undispatchPost(Request $req)
    {
        if ($req->has('start_date') == true || $req->has('end_date') == true)
        {
            $start_date = $req->input('start_date');
            $end_date   = $req->input('end_date');

            $data       = OrderModel::undispatchPost($start_date, $end_date);

            if (count($data) > 0)
            {
                $data = $data;
            }
            else
            {
                $data = [];
            }

            // dd($start_date, $end_date, $data);

            return view('order.undispatch', compact('start_date', 'end_date', 'data'));
        }
    }
}
?>