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
}
?>