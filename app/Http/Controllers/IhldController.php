<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Imports\ExcelPreviewModel;
date_default_timezone_set("Asia/Makassar");

class IhldController extends Controller
{

    public function uploadForm(){
        return view('ihld.uploadForm');
    }

    public function upload(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $import = new ExcelPreviewModel();
        Excel::import($import, $request->file('file'));

        // Store the data in the session for preview
        Session::put('import_data', $import->data);

        return redirect('/ihld/preview');
    }

    public function preview(){
        $data = Session::get('import_data');
        $newData = array();
        foreach($data as $r){
            $kordinatODP = $r[5].','.$r[6];
            $kordinatPelanggan = $r[2].','.$r[3];
            $materialKabel = $r[7];
            $newCoordinate = $this->adjustCoordinates($kordinatODP,$kordinatPelanggan,$materialKabel); 
            // print_r($newCoordinate);
            $newData[] = [
                'WO'                    => $r[0],
                'NO_INET'               => $r[1],
                'LATITUDE_CUSTOMER'     => $r[2],
                'LONGITUDE_CUSTOMER'    => $r[3],
                'ODP_NAME'              => $r[4],
                'LATITUDE_ODP'          => $r[5],
                'LONGITUDE_ODP'         => $r[6],
                'DROPCORE'              => $r[7],
                'NEW_LATITUDE'          => $newCoordinate['latitude'],
                'NEW_LONGITUDE'         => $newCoordinate['longitude']
            ];
        }

        // dd($newData);

        // $newData = json_encode($newData);

        return view('ihld.preview',compact('data','newData'));
    }

    public function adjustCoordinates($kordinatODP,$kordinatPelanggan,$materialKabel)
    {
        $materialKabel = $materialKabel + (($materialKabel*10)/100);
        $kordinatODP = explode(',', $kordinatODP);
        $kordinatPelanggan = explode(',', $kordinatPelanggan);
        // $materialKabel = 150;

        $odpLat = (float)$kordinatODP[0];
        $odpLng = (float)$kordinatODP[1];
        $pelangganLat = (float)$kordinatPelanggan[0];
        $pelangganLng = (float)$kordinatPelanggan[1];

        // Hitung jarak antara dua koordinat dalam meter
        $jarak = $this->haversineGreatCircleDistance($odpLat, $odpLng, $pelangganLat, $pelangganLng);

        // Jika jarak kurang dari panjang kabel, sesuaikan koordinat pelanggan
        if ($jarak < $materialKabel) {
            $newCoordinates = $this->calculateNewCoordinates($odpLat, $odpLng, $materialKabel);
            return $newCoordinates;
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Koordinat pelanggan disesuaikan.',
            //     'newCoordinates' => $newCoordinates,
            //     'originalDistance' => $jarak,
            //     'materialKabel' => $materialKabel,
            // ]);
        } else {
            return 0;
        }


        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Koordinat pelanggan tidak perlu disesuaikan.',
        //     'originalDistance' => $jarak,
        //     'materialKabel' => $materialKabel,
        // ]);
    }

    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    private function calculateNewCoordinates($latitudeFrom, $longitudeFrom, $distance, $bearing = 0)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);

        $bearing = deg2rad($bearing);

        $latTo = asin(sin($latFrom) * cos($distance / $earthRadius) +
            cos($latFrom) * sin($distance / $earthRadius) * cos($bearing));

        $lonTo = $lonFrom + atan2(sin($bearing) * sin($distance / $earthRadius) * cos($latFrom),
            cos($distance / $earthRadius) - sin($latFrom) * sin($latTo));

        $latTo = rad2deg($latTo);
        $lonTo = rad2deg($lonTo);

        return [
            'latitude' => $latTo,
            'longitude' => $lonTo,
        ];
    }


}