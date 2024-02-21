<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class EmployeeModel
{
    public static function show($id)
    {
        switch ($id) {
            case 'list':
                    $data = DB::table('wmcr_employee')
                    ->leftJoin('wmcr_master_regional', 'wmcr_employee.regional_id', '=', 'wmcr_master_regional.id')
                    ->leftJoin('wmcr_master_witel', 'wmcr_employee.witel_id', '=', 'wmcr_master_witel.id')
                    ->leftJoin('wmcr_master_mitra', 'wmcr_employee.mitra_id', '=', 'wmcr_master_mitra.id')
                    ->leftJoin('wmcr_employee_unit', 'wmcr_employee.unit_id', '=', 'wmcr_employee_unit.id')
                    ->leftJoin('wmcr_employee_sub_unit', 'wmcr_employee.sub_unit_id', '=', 'wmcr_employee_sub_unit.id')
                    ->leftJoin('wmcr_employee_sub_group', 'wmcr_employee.sub_group_id', '=', 'wmcr_employee_sub_group.id')
                    ->leftJoin('wmcr_employee_position', 'wmcr_employee.position_id', '=', 'wmcr_employee_position.id')
                    ->leftJoin('wmcr_master_level', 'wmcr_employee.level_id', '=', 'wmcr_master_level.id')
                    ->select('wmcr_employee.*', 'wmcr_master_regional.name AS regional_name', 'wmcr_master_witel.name AS witel_name', 'wmcr_master_mitra.name AS mitra_name', 'wmcr_employee_unit.name AS unit_name', 'wmcr_employee_sub_unit.name AS sub_unit_name', 'wmcr_employee_sub_group.name AS sub_group_name', 'wmcr_employee_position.name AS position_name', 'wmcr_master_level.name AS level_name');
                break;
            
            case 'unit':
                    $data = DB::table('wmcr_employee_unit');
                break;

            case 'sub_unit':
                    $data = DB::table('wmcr_employee_sub_unit')
                    ->leftJoin('wmcr_employee_unit', 'wmcr_employee_sub_unit.unit_id', '=', 'wmcr_employee_unit.id')
                    ->select('wmcr_employee_sub_unit.*', 'wmcr_employee_unit.name AS unit_name');
                break;

            case 'sub_group':
                    $data = DB::table('wmcr_employee_sub_group');
                break;

            case 'position':
                    $data = DB::table('wmcr_employee_position');
                break;
        }

        return $data->get();
    }
}
?>