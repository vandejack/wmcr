<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

date_default_timezone_set("Asia/Makassar");

class HomeController extends Controller
{
    public function index()
    {
        $start_date = Input::get('start_date') ?? date('Y-m-01');
        $end_date   = Input::get('end_date') ?? date('Y-m-d');

        return view('home.index', compact('start_date', 'end_date'));
    }
}
?>