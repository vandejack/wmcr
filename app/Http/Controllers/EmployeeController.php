<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeModel;

date_default_timezone_set("Asia/Makassar");

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee.index');
    }

    public function unit()
    {
        return view('employee.unit');
    }

    public function sub_unit()
    {
        return view('employee.sub_unit');
    }

    public function sub_group()
    {
        return view('employee.sub_group');
    }

    public function position()
    {
        return view('employee.position');
    }

    public function profile()
    {
        $data = EmployeeModel::profile_data();

        return view('employee.profile', compact('data'));
    }

    public function profile_post(Request $req)
    {
        EmployeeModel::profile_post($req);

        return redirect('/profile')->with('alerts', [
            ['type' => 'success', 'text' => 'Berhasil Simpan Data Profile!']
        ]);
    }
}
?>