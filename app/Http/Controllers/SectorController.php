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
        return view('sector.index');
    }

    public function rayon()
    {
        return view('sector.rayon');
    }

    public function team()
    {
        return view('sector.team');
    }

    public function alpro()
    {
        return view('sector.alpro');
    }

    public function schedule()
    {
        return view('sector.schedule');
    }

    public function brifieng()
    {
        return view('sector.brifieng');
    }

    public function alker()
    {
        return view('sector.alker');
    }
}
?>