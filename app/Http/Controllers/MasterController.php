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
        $data = MasterModel::show('regional');

        return view('master.regional', compact('data'));
    }

    public function witel()
    {
        $data = MasterModel::show('witel');

        return view('master.witel', compact('data'));
    }

    public function sto()
    {
        $data = MasterModel::show('sto');

        return view('master.sto', compact('data'));
    }

    public function mitra()
    {
        $data = MasterModel::show('mitra');

        return view('master.mitra', compact('data'));
    }

    public function level()
    {
        $data = MasterModel::show('level');

        return view('master.level', compact('data'));
    }
}
?>