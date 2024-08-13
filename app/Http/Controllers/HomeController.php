<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
date_default_timezone_set("Asia/Makassar");

class HomeController extends Controller
{
    public function index()
    {
        // $start_date = Input::get('start_date') ?? date('Y-m-01');
        // $end_date   = Input::get('end_date') ?? date('Y-m-d');

        // return view('home.index', compact('start_date', 'end_date'));
        return view('home.index2');
    }

    public function saveLocation(Request $request){
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $kordinat = $latitude.','.$longitude;
        DB::table('wmcr_employee')
            ->where('nik',session('auth')->nik)
            ->update([
                'location' => $kordinat,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'last_location_update' => date('Y-m-d H:i:s')
            ]);
            return response()->json(['success' => true]);
    }

}
?>