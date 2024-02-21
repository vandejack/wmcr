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
        $data = EmployeeModel::show('list');

        return view('employee.index', compact('data'));
    }

    public function unit()
    {
        $data = EmployeeModel::show('unit');

        return view('employee.unit', compact('data'));
    }

    public function sub_unit()
    {
        $data = EmployeeModel::show('sub_unit');

        return view('employee.sub_unit', compact('data'));
    }

    public function sub_group()
    {
        $data = EmployeeModel::show('sub_group');

        return view('employee.sub_group', compact('data'));
    }

    public function position()
    {
        $data = EmployeeModel::show('position');

        return view('employee.position', compact('data'));
    }
}
?>