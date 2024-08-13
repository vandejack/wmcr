<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\MasterModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelPreviewModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;


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

    
    public function odpUpdate(){
        return view('master.odpUpdate');
    }
    public function importData(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $import = new ExcelPreviewModel();
        Excel::import($import, $request->file('file'));

        // Store the data in the session for preview
        Session::put('import_data', $import->data);

        return redirect('/master/importODPPreview');
    }
    public function importODPPreview()
    {
        $columns = Schema::getColumnListing('wmcr_source_odp');
        $data = Session::get('import_data');
        foreach ($data as $r){
            $insert[] = [
                "odp"           => $r[0],
                "odp_name"      => $r[1],
                "latitude"      => $r[2],
                "longitude"     => $r[3],
                "avai"          => $r[4],
                "isi"           => $r[5],
                "occ"           => $r[6],
                "kategori_odp"  => $r[7],
                "total"         => $r[8],
                "tahun"         => $r[9],
                "mitra"         => $r[10],
                "lop"           => $r[11],
                "project"       => $r[12],
                "tgl_r2c"       => $r[13],
                "bulan"         => $r[14],
                "jenis_odp"     => $r[15],
                "olt"           => $r[16],
                "type_olt"      => $r[17],
                "regional"      => $r[18],
                "witel"         => $r[19],
                "datel"         => $r[20],
                "sto"           => $r[21],
                "sto_name"      => $r[22]
            ];

        }

        self::insertOrUpdate($insert,'wmcr_source_odp');
        
        echo "Jumlah data : ".count($data);
        // return view('master.odpImportPreview',compact('data','columns'));
    }

    public static function insertOrUpdate(array $rows,$table)
    {
        // $table = 'source_starclick';
        $first = reset($rows);
        $columns = implode(
            ',',
            array_map(function ($value) {
                return "$value";
            }, array_keys($first))
        );
        $values = implode(
            ',',
            array_map(function ($row) {
                return '(' . implode(
                    ',',
                    array_map(function ($value) {
                        return '"' . str_replace('"', '""', $value) . '"';
                    }, $row)
                ) . ')';
            }, $rows)
        );
        $updates = implode(
            ',',
            array_map(function ($value) {
                return "$value = VALUES($value)";
            }, array_keys($first))
        );
        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";
        return \DB::statement($sql);
    }
}
?>