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
        dd($req);
    }

    public function search()
    {
        return view('order.search');
    }

    public function searchPost(Request $req)
    {
        dd($req);
    }

    public function matrix()
    {
        return view('order.matrix');
    }

    public function matrixPost(Request $req)
    {
        dd($req);
    }

    public function undispatch()
    {
        return view('order.undispatch');
    }

    public function undispatchPost(Request $req)
    {
        dd($req);
    }
}
?>