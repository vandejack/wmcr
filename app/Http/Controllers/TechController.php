<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;

use App\Models\TechModel;

date_default_timezone_set("Asia/Makassar");

class TechController extends Controller
{
    public function home(){
        $defaultPeriode = date('Y-m-d');
        $nik = session('auth')->nik;
        $yesterday = date('d', strtotime($defaultPeriode . "-1 days"));
        $dateStartTimeline = $yesterday;
        $viewDay = 10;
        $getTechOrder = array();
        for ($x=-1;$x<=$viewDay;$x++){
            $periode = date('Y-m-d', strtotime($defaultPeriode . "$x days"));
            $date = date('d', strtotime($defaultPeriode . "$x days"));
            $getTechOrder[$date] = TechModel::orderList($nik,$periode);
        }
        
        return view('tech.home',compact('getTechOrder','defaultPeriode','viewDay'));
    }

    public function startProgress($id){
        DB::table('wmcr_order_dispatch')
            ->where('id',$id)
            ->update([
                'status' => 1,
                'report_by' => session('auth')->nik,
                'report_time' => date('Y-m-d H:i:s')
            ]);
        return redirect()->back()->with('alerts', [
            ['type' => 'success', 'text' => 'STARTWORK Order Successfull']
        ]);
    }

    public function orderview($dispatchID){
        $getSchedule = TechModel::getKehadiran(session('auth')->nik);
            if (($getSchedule && $getSchedule->approval==1) || session('auth')->level_id<>12){
                $backUrl =  url()->previous();
                $order = TechModel::getOrderbyID($dispatchID);
                $getKendalaTeknik = TechModel::getStatusSub(2);
                $getKendalaPelanggan = TechModel::getStatusSub(3);
                $getKendalaLainnya = TechModel::getStatusSub(4);
                $getStatus = TechModel::getStatus();
            if ($order->source=="wmcr_source_starclick"){
                $orderDetail = TechModel::getOrderDetailbyID($order->source,$order->order_id);
            }
            return view('tech.orderview',compact('dispatchID','order','orderDetail','backUrl','getKendalaTeknik','getKendalaPelanggan','getKendalaLainnya','getStatus'));
            } else {
                return redirect()->back()->with('alerts', [
                    ['type' => 'error', 'text' => 'Anda Belum Absen !']
                ]);
            }
     
    }

    public function saveProv(Request $request){
            switch($request->status){
                case 2 : 
                    $statusSub = $request->kendala_teknik;
                    break;
                break;
                case 3 : 
                    $statusSub = $request->kendala_pelanggan;
                break;
                case 4 : 
                    $statusSub = $request->kendala_lainnya;
                break;
                default :
                    $statusSub = 0;
                break;
            }
            if ($request->status==5){
                $isHR = 1;
            } else {
                $isHR = $request->has('isHR') ? 1 : 0;
            }
           
            $save = DB::table('wmcr_order_dispatch')
                    ->where('id',$request->dispatch_id)
                    ->update([
                        'status' => $request->status,
                        'statusSub' => $statusSub,
                        'engineMemo' => $request->engineMemo,
                        'odp_installation' => $request->odp_installation,
                        'kordinat_pelanggan' => $request->kordinat_pelanggan,
                        'report_by' => session('auth')->nik,
                        'isHR' => $isHR,
                        'report_time' => date('Y-m-d H:i:s')
                    ]);
            $this->uploadFiles($request);                      
    }
    public function uploadFiles($request)
    {
        $order_id = $request->dispatch_id;
        // Define the target directory
        $targetPath = '/images/evidence/'.$order_id.'/'; // Specify your desired directory
        // Ensure the directory exists
        if (!Storage::exists($targetPath)) {
            Storage::makeDirectory($targetPath,0755,true);
        }

        // Define an array of file inputs
        $fileInputs = ['Rumah_Pelanggan', 'ODP', 'Capture'];

        // Initialize an array to store file paths
        $filePaths = [];

        // Process each file input
        foreach ($fileInputs as $input) {
            if ($request->hasFile($input)) {
                $file = $request->file($input);

                // Generate a unique name for the file
                $fileName = $input . '.' . $file->getClientOriginalExtension();
                // $filePath = $targetPath . 'Comp_' . $fileName;
                // $image = Image::make($file)
                //         ->encode('jpg', 75) // Compress to JPG with quality 75
                //         ->save(public_path($filePath));           
                // Store the file and get the path
                            //   ->encode('jpg', 75) // Compress to JPG with quality 75
                            //   ->save(public_path($filePath));
                $file->move(public_path($targetPath), $fileName);
                
                
                // Add the file path to the array
                // $filePaths[$input] = $filePath;
            } 
        }

        // Return a JSON response with the paths
        return response()->json([
            'message' => 'Files successfully uploaded!',
            'file_paths' => $filePaths,
        ]);
    }
    public function createUploadDirectory($dirname)
    {
        $directory = public_path($dirname); // Define the path within the public directory
        echo $directory;
        // Create the directory if it does not exist
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true); // 0755 is the permission, true allows recursive creation
        }
    }

    public function absensi(){
        $getSchedule = TechModel::getKehadiran(session('auth')->nik);
        return view('tech.absensi',compact('getSchedule'));
    }

    public function requestApproval(){
        $update = DB::table('wmcr_sector_schedule')
                    ->where('technician',session('auth')->nik)
                    ->where('date',date('Y-m-d'))
                    ->update([
                        'approval' => 3,
                        'request_time' => date('Y-m-d H:i:s')
                    ]);
                    return redirect()->back()->with('alerts', [
                        ['type' => 'success', 'text' => 'Menunggu Proses Approval Team Leader']
                    ]);
    }

    public function location($sector){
        $data = DB::table('wmcr_employee')
                ->where('sector_id',$sector)
                ->get();
        return response()->json($data);
    }

}

?>