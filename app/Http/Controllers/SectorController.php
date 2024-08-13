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

    public function alproUpdate(){
        return view('sector.alproUpdate');
    }

    public function mapAlpro($witel){
        $sector = DB::table('wmcr_sector as a')
                    ->leftJoin('wmcr_master_witel as b','a.witel_id','=','b.id')
                    ->where('b.name',$witel)
                    ->select('a.id','a.name')
                    ->get();
        return view('sector.mapAlpro',compact('sector','witel'));
    }

    public function odpSector($witel){
        $data = DB::table('wmcr_source_odp')
                    ->select('id','odp_name as title','latitude as lat','longitude as lng')
                    ->where('witel',$witel)
                    ->get();
        return response()->json(['locations' => $data]);
    }
    public function saveOdpSector(Request $request){
          // Validate the input
          $validatedData = $request->validate([
            'sector_id' => 'required|integer',
            'coordinates' => 'required|string',
            'alpro' => 'required|string',
        ]);

        DB::table('wmcr_sector')
            ->where('id',$request->sector_id)
            ->update([
                'polygons' => $request->coordinates
            ]);

        $alpro = json_decode($validatedData['alpro'], true);
        $alproArray = array();
        foreach ($alpro as $location) {
            $odp = $location['title'];
            $cek = DB::table('wmcr_sector_alpro')
                    ->where('name',$odp)
                    ->first();
            if ($cek){
                DB::table('wmcr_sector_alpro')
                    ->where('name',$odp)
                    ->update([
                        'sector_id' => $request->sector_id,
                        'updated_time' => date('Y-m-d H:i:s'),
                        'updated_by' => session('auth')->nik
                    ]);
            } else {
                DB::table('wmcr_sector_alpro')
                    ->insert([
                        'sector_id' => $request->sector_id,
                        'name' => $odp,
                        'updated_time' => date('Y-m-d H:i:s'),
                        'updated_by' => session('auth')->nik
                    ]);
            }
            echo $odp.'<br />';
        }

    }
    public function insertOrUpdate(array $rows,$table)
    {
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