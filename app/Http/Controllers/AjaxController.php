<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeModel;
use App\Models\MasterModel;
use App\Models\SectorModel;
use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Makassar");

class AjaxController extends Controller
{
    public function master_data($id)
    {
        $result['data'] = [];
        $columns = [];

        $data = MasterModel::show($id);

        switch ($id) {
            case 'regional':
                    $columns = ['name', 'aliases'];
                break;
            
            case 'witel':
                    $columns = ['regional_name', 'name', 'aliases'];
                break;
            
            case 'sto':
                    $columns = ['regional_name', 'witel_name', 'name'];
                break;

            case 'mitra':
                    $columns = ['regional_name', 'witel_name', 'name'];
                break;

            case 'level':
                    $columns = ['name'];
                break;
        }

        foreach ($data as $k => $v)
        {
            $rowData = [];

            foreach ($columns as $column)
            {
                $rowData[] = $v->$column;
            }

            array_unshift($rowData, ++$k);
            $result['data'][] = $rowData;
        }

        return response()->json($result);
    }

    public function employee_data($id)
    {
        $result['data'] = [];
        $columns = [];
        $editLink = '';

        $data = EmployeeModel::show($id);

        switch ($id) {
            case 'list':
                    $columns = ['nik', 'name', 'chat_id', 'regional_name', 'witel_name', 'mitra_name', 'unit_name', 'sub_unit_name', 'sub_group_name', 'position_name', 'level_name'];
                    $editLink = '/employee/edit/';
                break;
            
            case 'unit':
                    $columns = ['name'];
                break;
            
            case 'sub_unit':
                    $columns = ['unit_name', 'name'];
                break;

            case 'sub_group':
                    $columns = ['name'];
                break;

            case 'position':
                    $columns = ['name'];
                break;
        }

        foreach ($data as $k => $v)
        {
            $rowData = [];
    
            foreach ($columns as $column) {
                $rowData[] = $v->$column;
            }
    
            if ($editLink !== '')
            {
                $editButton =
                '<a href="' . $editLink . $v->id . '" type="button" class="btn btn-icon btn-sm btn-primary btn-rounded text-center">' .
                    '<i class="fa fa-edit" aria-hidden="true"></i>' .
                '</a>';

                $rowData[] = $editButton;
            }
    
            array_unshift($rowData, ++$k);
            $result['data'][] = $rowData;
        }

        return response()->json($result);   
    }

    public function sector_data($id)
    {
        $result['data'] = [];
        $columns = [];
        $editLink = '';

        $data = SectorModel::show($id);

        switch ($id) {
            case 'list':
                    $columns = ['name', 'group_chat_id', 'owner1_name', 'owner2_name', 'rayon_name'];
                    $editLink = '/sector/edit/';
                break;
            
            case 'rayon':
                    $columns = ['name', 'owner_name', 'manager_name'];
                break;
            
            case 'team':
                    $columns = ['sector_name', 'name', 'technician1_name', 'technician2_name'];
                break;

            case 'alpro':
                    $columns = ['sector_name', 'name'];
                break;

            case 'schedule':
                    $columns = ['technician_name', 'date'];
                break;

            case 'brifieng':
                    $columns = ['name', 'date'];
                break;
            
            case 'alker':
                    $columns = [];
                break;
        }

        foreach ($data as $k => $v) {
            $rowData = [];

            foreach ($columns as $column)
            {
                if ($column == 'owner1_name' && isset($v->owner1_name) && isset($v->owner1))
                {
                    $owner1Info = $v->owner1_name . ' (' . $v->owner1 . ')';
                    $rowData[] = $owner1Info;
                }

                if ($column == 'owner2_name' && isset($v->owner2_name) && isset($v->owner2))
                {
                    $owner2Info = $v->owner2_name . ' (' . $v->owner2 . ')';
                    $rowData[] = $owner2Info;
                }

                if ($column == 'owner_name' && isset($v->owner_name) && isset($v->owner))
                {
                    $ownerInfo = $v->owner_name . ' (' . $v->owner . ')';
                    $rowData[] = $ownerInfo;
                }

                if ($column == 'manager_name' && isset($v->manager_name) && isset($v->manager))
                {
                    $managerInfo = $v->manager_name . ' (' . $v->manager . ')';
                    $rowData[] = $managerInfo;
                }

                if ($column == 'technician1_name' && isset($v->technician1_name) && isset($v->technician1))
                {
                    $technician1Info = $v->technician1_name . ' (' . $v->technician1 . ')';
                    $rowData[] = $technician1Info;
                }

                if ($column == 'technician2_name' && isset($v->technician2_name) && isset($v->technician2))
                {
                    $technician2Info = $v->technician2_name . ' (' . $v->technician2 . ')';
                    $rowData[] = $technician2Info;
                }

                if ($column == 'technician_name' && isset($v->technician_name) && isset($v->technician))
                {
                    $technicianInfo = $v->technician_name . ' (' . $v->technician . ')';
                    $rowData[] = $technicianInfo;
                }

                $rowData[] = $v->$column;

                if ($column == 'name' && $editLink !== '')
                {
                    $editButton =
                    '<a href="' . $editLink . $v->id . '" type="button" class="btn btn-icon btn-sm btn-primary btn-rounded text-center">' .
                        '<i class="fa fa-edit" aria-hidden="true"></i>' .
                    '</a>';
                    $rowData[] = $editButton;
                }
            }

            array_unshift($rowData, ++$k);
            $result['data'][] = $rowData;
        }

        return response()->json($result);
    }
}
?>