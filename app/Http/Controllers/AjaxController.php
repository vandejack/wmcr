<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DashboardModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\EmployeeModel;
use App\Models\MasterModel;
use App\Models\SectorModel;
use App\Models\OrderModel;

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
                    $columns = ['regional_name', 'witel_name', 'name', 'datel'];
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

    public function select_data(Request $req, $id, $x)
    {
        $search = trim($req->searchTerm);

        switch ($id) {
            case 'sector':
                    $data = DB::table('wmcr_sector')->where([ ['is_active', 1], ['witel_id', session('auth')->witel_id] ])->select('id', 'name as text');

                    if ($search)
                    {
                        $data->Where('name', 'LIKE', "%$search%");
                    }

                    $data = $data->orderBy('urut', 'ASC')->get();
                break;

            case 'team':
                # code...
                break;
            
            case 'timezone':
                    $data = DB::table('wmcr_employee_timezone');

                    if ($search)
                    {
                        $data->Where('text', 'LIKE', "%$search%");
                    }

                    $data = $data->get();
                break;
            
            default:
                # code...
                break;
        }

        return response()->json($data);
    }

    public function undispatch_order($start_date, $end_date)
    {
        $jml_provisioning = $ttl_order_survey = $ttl_order_ao = $ttl_order_mo = $ttl_order_pda = $ttl_provisioning = $jml_assurance = $ttl_order_b2c = $ttl_order_b2b = $ttl_order_proactive = $ttl_assurance = $jml_maintenance = $ttl_order_non_warranty = $ttl_order_warranty = $ttl_maintenance = $jumlah = $k = 0;
        $total = 0;

        $data = OrderModel::undispatch_post($start_date, $end_date);
        $result = ['data' => [], 'footer' => []];

        foreach ($data as $area => $v)
        {
            $jml_provisioning       =  @$v['order_survey'] + @$v['order_ao'] + @$v['order_mo'] + @$v['order_pda'];
            $ttl_order_survey       += @$v['order_survey'];
            $ttl_order_ao           += @$v['order_ao'];
            $ttl_order_mo           += @$v['order_mo'];
            $ttl_order_pda          += @$v['order_pda'];
            $ttl_provisioning       += $jml_provisioning;

            $jml_assurance          =  @$v['order_b2c'] + @$v['order_b2b'] + @$v['order_proactive'];
            $ttl_order_b2c          += @$v['order_b2c'];
            $ttl_order_b2b          += @$v['order_b2b'];
            $ttl_order_proactive    += @$v['order_proactive'];
            $ttl_assurance          += $jml_assurance;

            $jml_maintenance        =  @$v['order_non_warranty'] + @$v['order_warranty'];
            $ttl_order_non_warranty += @$v['order_non_warranty'];
            $ttl_order_warranty     += @$v['order_warranty'];
            $ttl_maintenance        += $jml_maintenance;

            $jumlah                 =  ($jml_provisioning + $jml_assurance + $jml_maintenance);
            $total                  += $jumlah;
 
            $result['data'][$k][] = $area;
            $result['data'][$k][] = '<a href="/order/undispatch-detail?area='.$area.'&order=order_survey&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format(@$v['order_survey'])).'</a>';
            $result['data'][$k][] = '<a href="/order/undispatch-detail?area='.$area.'&order=order_ao&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format(@$v['order_ao'])).'</a>';
            $result['data'][$k][] = '<a href="/order/undispatch-detail?area='.$area.'&order=order_mo&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format(@$v['order_mo'])).'</a>';
            $result['data'][$k][] = '<a href="/order/undispatch-detail?area='.$area.'&order=order_pda&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format(@$v['order_pda'])).'</a>';
            $result['data'][$k][] = str_replace(',', '.', number_format($jml_provisioning));
            $result['data'][$k][] = '<a href="/order/undispatch-detail?area='.$area.'&order=order_b2c&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format(@$v['order_b2c'])).'</a>';
            $result['data'][$k][] = '<a href="/order/undispatch-detail?area='.$area.'&order=order_b2b&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format(@$v['order_b2b'])).'</a>';
            $result['data'][$k][] = '<a href="/order/undispatch-detail?area='.$area.'&order=order_proactive&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format(@$v['order_proactive'])).'</a>';
            $result['data'][$k][] = str_replace(',', '.', number_format($jml_assurance));
            $result['data'][$k][] = '<a href="/order/undispatch-detail?area='.$area.'&order=order_non_warranty&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format(@$v['order_non_warranty'])).'</a>';
            $result['data'][$k][] = '<a href="/order/undispatch-detail?area='.$area.'&order=order_warranty&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format(@$v['order_warranty'])).'</a>';
            $result['data'][$k][] = str_replace(',', '.', number_format($jml_maintenance));
            $result['data'][$k][] = str_replace(',', '.', number_format($jumlah));

            $k++;
        }

        $result['footer'][] = '<b><a href="/order/undispatch-detail?area=all&order=order_survey&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format($ttl_order_survey)).'</a></b>';
        $result['footer'][] = '<b><a href="/order/undispatch-detail?area=all&order=order_ao&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format($ttl_order_ao)).'</a></b>';
        $result['footer'][] = '<b><a href="/order/undispatch-detail?area=all&order=order_mo&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format($ttl_order_mo)).'</a></b>';
        $result['footer'][] = '<b><a href="/order/undispatch-detail?area=all&order=order_pda&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format($ttl_order_pda)).'</a></b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_provisioning)).'</b>';
        $result['footer'][] = '<b><a href="/order/undispatch-detail?area=all&order=order_b2c&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format($ttl_order_b2c)).'</a></b>';
        $result['footer'][] = '<b><a href="/order/undispatch-detail?area=all&order=order_b2b&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format($ttl_order_b2b)).'</a></b>';
        $result['footer'][] = '<b><a href="/order/undispatch-detail?area=all&order=order_proactive&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format($ttl_order_proactive)).'</a></b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_assurance)).'</b>';
        $result['footer'][] = '<b><a href="/order/undispatch-detail?area=all&order=order_non_warranty&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format($ttl_order_non_warranty)).'</a></b>';
        $result['footer'][] = '<b><a href="/order/undispatch-detail?area=all&order=order_warranty&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.str_replace(',', '.', number_format($ttl_order_warranty)).'</a></b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_maintenance)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($total)).'</b>';

        return response()->json($result);
    }

    public function undispatch_detail()
    {   
        $area           = Input::get('area');
        $order          = Input::get('order');
        $start_date     = Input::get('start_date');
        $end_date       = Input::get('end_date');

        $result['data'] = [];

        $data           = OrderModel::undispatch_detail($area, $order, $start_date, $end_date);
        $order_type     = OrderModel::order_type($order);

        foreach ($data as $k => $v)
        {
            if (in_array($order, ['order_survey', 'order_ao', 'order_mo', 'order_pda']))
            {
                if ($order == 'order_survey')
                {
                    $result['data'][] = [
                        ++$k,
                        '<a type="button" data-bs-toggle="modal" data-bs-target="#dispatch_data" class="btn btn-sm btn-icon btn-primary btn-rounded btn-hover-scale dispatch_modal" data-order_type_id="'.$order_type->id.'" data-order_data="'.$order.'" data-id_data="'.$v->order_code.'">
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        </a>',
                        $v->order_code,
                        $v->customer_desc,
                        $v->order_created_date,
                        $v->customer_name,
                        $v->customer_phone,
                        $v->sto,
                        $v->witel,
                        $v->myir,
                        $v->order_type_name,
                        $v->order_status_name
                    ];
                }
                else if (in_array($order, ['order_ao', 'order_mo', 'order_pda']))
                {
                    $result['data'][] = [
                        ++$k,
                        '<a type="button" data-bs-toggle="modal" data-bs-target="#dispatch_data" class="btn btn-sm btn-icon btn-primary btn-rounded btn-hover-scale dispatch_modal" data-order_type_id="'.$order_type->id.'" data-order_data="'.$order.'" data-id_data="'.$v->order_id.'">
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        </a>',
                        $v->order_id,
                        $v->order_date,
                        $v->order_date_ps,
                        $v->ncli,
                        $v->customer_name,
                        $v->witel,
                        $v->jenis_psb,
                        $v->sto,
                        $v->speedy,
                        $v->pots,
                        $v->package_name,
                        $v->status_resume,
                        $v->customer_addr,
                        $v->kcontact,
                        $v->ins_address,
                        $v->gps_latitude,
                        $v->gps_longitude,
                        $v->loc_id
                    ];
                }
            }
            else if (in_array($order, ['order_b2c', 'order_b2b', 'order_proactive']))
            {
                $result['data'][] = [
                    ++$k,
                    '<a type="button" data-bs-toggle="modal" data-bs-target="#dispatch_data" class="btn btn-sm btn-icon btn-primary btn-rounded btn-hover-scale dispatch_modal" data-order_type_id="'.$order_type->id.'" data-order_data="'.$order.'" data-id_data="'.$v->incident.'">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    </a>',
                    $v->incident,
                    $v->ttr_customer,
                    $v->summary,
                    $v->reported_date,
                    $v->owner_group,
                    $v->customer_segment,
                    $v->service_type,
                    $v->witel,
                    $v->workzone,
                    $v->status,
                    $v->status_date,
                    $v->ticket_id_gamas,
                    $v->contact_phone,
                    $v->contact_name,
                    $v->source_ticket,
                    $v->customer_type,
                    $v->customer_name,
                    $v->service_id,
                    $v->service_no,
                    $v->device_name,
                    $v->guarante_status,
                    $v->resolve_date
                ];
            }
            else if (in_array($order, ['order_non_warranty', 'order_warranty']))
            {
                $result['data'][] = [
                    ++$k,
                    '<a type="button" data-bs-toggle="modal" data-bs-target="#dispatch_data" class="btn btn-sm btn-icon btn-primary btn-rounded btn-hover-scale dispatch_modal" data-order_type_id="'.$order_type->id.'" data-order_data="'.$order.'" data-id_data="'.$v->nd.'">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    </a>',
                    $v->reg,
                    $v->witel,
                    $v->sektor,
                    $v->node_id,
                    $v->shelf_slot_port_onuid,
                    $v->fiber_length,
                    $v->cmdf,
                    $v->rk,
                    $v->dp,
                    $v->nd,
                    $v->tanggal_ps,
                    $v->status_inet,
                    $v->onu_rx_power,
                    $v->tanggal_ukur,
                    $v->onu_rx_power_ukur_ulang,
                    $v->tanggal_ukur_ulang,
                    $v->nomor_tiket,
                    $v->status_tiket,
                    $v->flag_hvc,
                    $v->type_pelanggan,
                    $v->prioritas,
                    $v->jenis,
                    $v->tanggal_order
                ];
            }
            
        }

        return response()->json($result);
    }

    public function undispatch_search($order, $id)
    {
        $result['data'] = [];

        $data = OrderModel::undispatch_search($order, $id);

        $result['data'] = $data;

        return response()->json($result);
    }

    public function trr_hvc()
    {
        $jml_ttr_hours = $ttl_ttr_0hours = $ttl_ttr_1hours = $ttl_ttr_2hours = $ttl_ttr_3hours = $ttl_ttr_hours = $jml_comply_notcomply = $ttl_comply = $ttl_notcomply = $ttl_comply_notcomply = $percent_comply = $ttl_percent_comply = $ttl_percent_notcomply = $percent_notcomply = 0;

        $data = DashboardModel::ttr_hvc();
        $result = ['data' => [], 'footer' => []];

        foreach ($data as $k => $v)
        {
            $jml_ttr_hours        = $v->ttr_0hours + $v->ttr_1hours + $v->ttr_2hours + $v->ttr_3hours;
            $ttl_ttr_0hours       += $v->ttr_0hours;
            $ttl_ttr_1hours       += $v->ttr_1hours;
            $ttl_ttr_2hours       += $v->ttr_2hours;
            $ttl_ttr_3hours       += $v->ttr_3hours;
            $ttl_ttr_hours        += $jml_ttr_hours;

            $jml_comply_notcomply = $v->ttr_comply + $v->ttr_notcomply;
            $ttl_comply           += $v->ttr_comply;
            $ttl_notcomply        += $v->ttr_notcomply;
            $ttl_comply_notcomply += $jml_comply_notcomply;

            if ($jml_comply_notcomply > 0)
            {
                $percent_comply = round(($v->ttr_comply / $jml_comply_notcomply) * 100, 2);
                $percent_notcomply = round(($v->ttr_notcomply / $jml_comply_notcomply) * 100, 2);
            }

            $percent_comply = is_nan($percent_comply) ? 0 : $percent_comply;
            $percent_notcomply = is_nan($percent_notcomply) ? 0 : $percent_notcomply;
 
            $result['data'][$k][] = $v->witel;
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_0hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_1hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_2hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_3hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($jml_ttr_hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_comply));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_notcomply));
            $result['data'][$k][] = $percent_comply." %";
            $result['data'][$k][] = $percent_notcomply. " %";
            $k++;
        }

        if ($ttl_comply_notcomply > 0)
        {
            $ttl_percent_comply = round(($ttl_comply / $ttl_comply_notcomply) * 100, 2);
            $ttl_percent_notcomply = round(($ttl_notcomply / $ttl_comply_notcomply) * 100, 2);
        }

        $ttl_percent_comply = is_nan($ttl_percent_comply) ? 0 : $ttl_percent_comply;
        $ttl_percent_notcomply = is_nan($ttl_percent_notcomply) ? 0 : $ttl_percent_notcomply;


        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_0hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_1hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_2hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_3hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_comply)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_notcomply)).'</b>';
        $result['footer'][] = '<b>'.$ttl_percent_comply.' %</b>';
        $result['footer'][] = '<b>'.$ttl_percent_notcomply.' %</b>';

        return response()->json($result);
    }
}
?>