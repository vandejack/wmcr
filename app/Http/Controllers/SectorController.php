<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\SectorModel;

date_default_timezone_set("Asia/Makassar");

class SectorController extends Controller
{
    public function index()
    {
        $data = SectorModel::show('list');

        return view('sector.index', compact('data'));
    }

    public function rayon()
    {
        $data = SectorModel::show('rayon');

        return view('sector.rayon', compact('data'));
    }

    public function team()
    {
        $data = SectorModel::show('team');

        return view('sector.team', compact('data'));
    }

    public function alpro()
    {
        $data = SectorModel::show('alpro');

        return view('sector.alpro', compact('data'));
    }

    public function schedule()
    {
        $data = SectorModel::show('schedule');

        return view('sector.schedule', compact('data'));
    }

    public function brifieng()
    {
        $data = SectorModel::show('brifieng');

        return view('sector.brifieng', compact('data'));
    }

    public function alker()
    {
        // $data = SectorModel::show('alker');

        return view('sector.alker', compact('data'));
    }
}
?>