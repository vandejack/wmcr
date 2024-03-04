<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\MasterModel;

date_default_timezone_set("Asia/Makassar");

class MasterController extends Controller
{
    public function regional()
    {
        return view('master.regional');
    }

    public function witel()
    {
        return view('master.witel');
    }

    public function sto()
    {
        return view('master.sto');
    }

    public function mitra()
    {
        return view('master.mitra');
    }

    public function level()
    {
        return view('master.level');
    }
}
?>