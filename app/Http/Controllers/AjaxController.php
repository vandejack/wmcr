<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\EmployeeModel;
use App\Models\MasterModel;
use App\Models\SectorModel;
use App\Models\OrderModel;
use App\Models\DashboardModel;

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

        $data = SectorModel::show($id);

        foreach ($data as $k => $v)
        {
            switch ($id) {
                case 'list':
                        $result['data'][] = [
                            ++$k,
                            $v->name,
                            $v->group_chat_id,
                            $v->owner1_name . ' (' . $v->owner1 . ')',
                            $v->owner2_name . ' (' . $v->owner2 . ')',
                            $v->rayon_name,
                            '<a href="/sector/edit/'.$v->id.'" type="button" class="btn btn-icon btn-sm btn-primary btn-rounded text-center">' .
                                    '<i class="fa fa-edit" aria-hidden="true"></i>' .
                            '</a>'
                        ];
                    break;
                case 'rayon':
                        $result['data'][] = [
                            ++$k,
                            $v->name,
                            $v->owner_name . ' (' . $v->owner . ')',
                            $v->manager_name . ' (' . $v->manager . ')'
                        ];
                    break;
                case 'team':
                        $result['data'][] = [
                            ++$k,
                            $v->sector_name,
                            $v->name,
                            $v->technician1_name . ' (' . $v->technician1 . ')',
                            $v->technician2_name . ' (' . $v->technician2 . ')',
                        ];
                    break;
                case 'alpro':
                        $result['data'][] = [
                            ++$k,
                            $v->sector_name,
                            $v->name
                        ];
                    break;
                case 'schedule':
                        $result['data'][] = [
                            ++$k,
                            $v->technician_name . ' (' . $v->technician . ')',
                            $v->date
                        ];
                    break;
                case 'brifieng':
                        $result['data'][] = [
                            ++$k,
                            $v->name,
                            $v->date
                        ];
                    break;
                case 'alker':
                        $result['data'][] = [
                            ++$k
                        ];
                    break;
            }
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

    public function productivity_order()
    {
        $jml_tsel = $jml_ao_tsel = $jml_orbit_tsel = $jml_mo_tsel = $jml_pda_tsel = $ttl_tsel = 
        $jml_tlkm = $jml_ao_tlkm = $jml_orbit_tlkm = $jml_mo_tlkm = $jml_pda_tlkm = $ttl_tlkm =
        $jml_insera_b2c = $jml_insera_b2c_vvip = $jml_insera_b2c_diamond = $jml_insera_b2c_platinum = $jml_insera_b2c_gold = $jml_insera_b2c_reguler = $jml_insera_b2c_unspec = $jml_insera_b2c_sqm = $ttl_insera_b2c =
        $jml_insera_b2b = $jml_insera_b2b_des = $jml_insera_b2b_dbs = $jml_insera_b2b_dgs = $jml_insera_b2b_dps = $jml_insera_b2b_dss = $jml_insera_b2b_reg = $jml_insera_b2b_dws = $jml_insera_b2b_taw = $ttl_insera_b2b = 0;

        $data = DashboardModel::productivity_order();

        $result = ['data' => [], 'footer' => []];

        foreach ($data as $witel => $value)
        {
            $jml_tsel                =  (@$value['ao_tsel'] + @$value['orbit_tsel'] + @$value['mo_tsel'] + @$value['pda_tsel']);
            $jml_ao_tsel             += @$value['ao_tsel'];
            $jml_orbit_tsel          += @$value['orbit_tsel'];
            $jml_mo_tsel             += @$value['mo_tsel'];
            $jml_pda_tsel            += @$value['pda_tsel'];
            $ttl_tsel                += $jml_tsel;

            $jml_tlkm                =  (@$value['ao_tlkm'] + @$value['orbit_tlkm'] + @$value['mo_tlkm'] + @$value['pda_tlkm']);
            $jml_ao_tlkm             += @$value['ao_tlkm'];
            $jml_orbit_tlkm          += @$value['orbit_tlkm'];
            $jml_mo_tlkm             += @$value['mo_tlkm'];
            $jml_pda_tlkm            += @$value['pda_tlkm'];
            $ttl_tlkm                += $jml_tlkm;

            $jml_insera_b2c          =  (@$value['insera_b2c_vvip'] + @$value['insera_b2c_diamond'] + @$value['insera_b2c_platinum'] + @$value['insera_b2c_gold'] + @$value['insera_b2c_reguler'] + @$value['insera_b2c_unspec'] + @$value['insera_b2c_sqm']);
            $jml_insera_b2c_vvip     += @$value['insera_b2c_vvip'];
            $jml_insera_b2c_diamond  += @$value['insera_b2c_diamond'];
            $jml_insera_b2c_platinum += @$value['insera_b2c_platinum'];
            $jml_insera_b2c_gold     += @$value['insera_b2c_gold'];
            $jml_insera_b2c_reguler  += @$value['insera_b2c_reguler'];
            $jml_insera_b2c_unspec   += @$value['insera_b2c_unspec'];
            $jml_insera_b2c_sqm      += @$value['insera_b2c_sqm'];
            $ttl_insera_b2c          += $jml_insera_b2c;

            $jml_insera_b2b          =  (@$value['insera_b2b_des'] + @$value['insera_b2b_dbs'] + @$value['insera_b2b_dgs'] + @$value['insera_b2b_dps'] + @$value['insera_b2b_dss'] + @$value['insera_b2b_reg'] + @$value['insera_b2b_dws'] + @$value['insera_b2b_taw']);
            $jml_insera_b2b_des      += @$value['insera_b2b_des'];
            $jml_insera_b2b_dbs      += @$value['insera_b2b_dbs'];
            $jml_insera_b2b_dgs      += @$value['insera_b2b_dgs'];
            $jml_insera_b2b_dps      += @$value['insera_b2b_dps'];
            $jml_insera_b2b_dss      += @$value['insera_b2b_dss'];
            $jml_insera_b2b_reg      += @$value['insera_b2b_reg'];
            $jml_insera_b2b_dws      += @$value['insera_b2b_dws'];
            $jml_insera_b2b_taw      += @$value['insera_b2b_taw'];
            $ttl_insera_b2b          += $jml_insera_b2b;

            $result['data'][] = [
                $witel,
                str_replace(',', '.', number_format(@$value['ao_tsel'])),
                str_replace(',', '.', number_format(@$value['orbit_tsel'])),
                str_replace(',', '.', number_format(@$value['mo_tsel'])),
                str_replace(',', '.', number_format(@$value['pda_tsel'])),
                str_replace(',', '.', number_format($jml_tsel)),

                str_replace(',', '.', number_format(@$value['ao_tlkm'])),
                str_replace(',', '.', number_format(@$value['orbit_tlkm'])),
                str_replace(',', '.', number_format(@$value['mo_tlkm'])),
                str_replace(',', '.', number_format(@$value['pda_tlkm'])),
                str_replace(',', '.', number_format($jml_tlkm)),

                str_replace(',', '.', number_format(@$value['insera_b2c_vvip'])),
                str_replace(',', '.', number_format(@$value['insera_b2c_diamond'])),
                str_replace(',', '.', number_format(@$value['insera_b2c_platinum'])),
                str_replace(',', '.', number_format(@$value['insera_b2c_gold'])),
                str_replace(',', '.', number_format(@$value['insera_b2c_reguler'])),
                str_replace(',', '.', number_format(@$value['insera_b2c_unspec'])),
                str_replace(',', '.', number_format(@$value['insera_b2c_sqm'])),
                str_replace(',', '.', number_format($jml_insera_b2c)),

                str_replace(',', '.', number_format(@$value['insera_b2b_des'])),
                str_replace(',', '.', number_format(@$value['insera_b2b_dbs'])),
                str_replace(',', '.', number_format(@$value['insera_b2b_dgs'])),
                str_replace(',', '.', number_format(@$value['insera_b2b_dps'])),
                str_replace(',', '.', number_format(@$value['insera_b2b_dss'])),
                str_replace(',', '.', number_format(@$value['insera_b2b_reg'])),
                str_replace(',', '.', number_format(@$value['insera_b2b_dws'])),
                str_replace(',', '.', number_format(@$value['insera_b2b_taw'])),
                str_replace(',', '.', number_format($jml_insera_b2b))
            ];
        }

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_ao_tsel)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_orbit_tsel)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_mo_tsel)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_pda_tsel)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_tsel)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_ao_tlkm)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_orbit_tlkm)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_mo_tlkm)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_pda_tlkm)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_tlkm)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2c_vvip)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2c_diamond)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2c_platinum)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2c_gold)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2c_reguler)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2c_unspec)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2c_sqm)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_insera_b2c)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2b_des)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2b_dbs)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2b_dgs)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2b_dps)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2b_dss)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2b_reg)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2b_dws)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($jml_insera_b2b_taw)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_insera_b2b)).'</b>';

        return response()->json($result);
    }

    public function ttr_comply_notcomply_open()
    {
        $jml_ttr_vvip = $ttl_ttr_vvip_1hours = $ttl_ttr_vvip_2hours = $ttl_ttr_vvip_3hours = $ttl_ttr_vvip_3plus_hours =
        $jml_ttr_diamond = $ttl_ttr_diamond_1hours = $ttl_ttr_diamond_2hours = $ttl_ttr_diamond_3hours = $ttl_ttr_diamond_3plus_hours =
        $jml_ttr_platinum = $ttl_ttr_platinum_1hours = $ttl_ttr_platinum_3hours = $ttl_ttr_platinum_6hours = $ttl_ttr_platinum_6plus_hours =
        $jml_ttr_gsr = $ttl_ttr_gsr_6hours = $ttl_ttr_gsr_12hours = $ttl_ttr_gsr_24hours = $ttl_ttr_gsr_36hours =
        $jml_ttr_manja = $ttl_ttr_manja_1hours = $ttl_ttr_manja_2hours = $ttl_ttr_manja_3hours = $ttl_ttr_manja_3plus_hours = 0;

        $data = DashboardModel::ttr_comply_notcomply_open();

        $result = ['data' => [], 'footer' => []];

        foreach ($data as $k => $v)
        {
            $jml_ttr_vvip                 =  $v->ttr_vvip_1hours + $v->ttr_vvip_2hours + $v->ttr_vvip_3hours + $v->ttr_vvip_3plus_hours;
            $ttl_ttr_vvip_1hours          += $v->ttr_vvip_1hours;
            $ttl_ttr_vvip_2hours          += $v->ttr_vvip_2hours;
            $ttl_ttr_vvip_3hours          += $v->ttr_vvip_3hours;
            $ttl_ttr_vvip_3plus_hours     += $v->ttr_vvip_3plus_hours;

            $jml_ttr_diamond              =  $v->ttr_diamond_1hours + $v->ttr_diamond_2hours + $v->ttr_diamond_3hours + $v->ttr_diamond_3plus_hours;
            $ttl_ttr_diamond_1hours       += $v->ttr_diamond_1hours;
            $ttl_ttr_diamond_2hours       += $v->ttr_diamond_2hours;
            $ttl_ttr_diamond_3hours       += $v->ttr_diamond_3hours;
            $ttl_ttr_diamond_3plus_hours  += $v->ttr_diamond_3plus_hours;

            $jml_ttr_platinum             =  $v->ttr_platinum_1hours + $v->ttr_platinum_3hours + $v->ttr_platinum_6hours + $v->ttr_platinum_6plus_hours;
            $ttl_ttr_platinum_1hours      += $v->ttr_platinum_1hours;
            $ttl_ttr_platinum_3hours      += $v->ttr_platinum_3hours;
            $ttl_ttr_platinum_6hours      += $v->ttr_platinum_6hours;
            $ttl_ttr_platinum_6plus_hours += $v->ttr_platinum_6plus_hours;

            $jml_ttr_gsr                  =  $v->ttr_gsr_6hours + $v->ttr_gsr_12hours + $v->ttr_gsr_24hours + $v->ttr_gsr_36hours;
            $ttl_ttr_gsr_6hours           += $v->ttr_gsr_6hours;
            $ttl_ttr_gsr_12hours          += $v->ttr_gsr_12hours;
            $ttl_ttr_gsr_24hours          += $v->ttr_gsr_24hours;
            $ttl_ttr_gsr_36hours          += $v->ttr_gsr_36hours;

            $jml_ttr_manja                =  $v->ttr_manja_1hours + $v->ttr_manja_2hours + $v->ttr_manja_3hours + $v->ttr_manja_3plus_hours;
            $ttl_ttr_manja_1hours         += $v->ttr_manja_1hours;
            $ttl_ttr_manja_2hours         += $v->ttr_manja_2hours;
            $ttl_ttr_manja_3hours         += $v->ttr_manja_3hours;
            $ttl_ttr_manja_3plus_hours    += $v->ttr_manja_3plus_hours;

            $result['data'][$k][] = $v->witel;
            
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_vvip_1hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_vvip_2hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_vvip_3hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_vvip_3plus_hours));

            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_diamond_1hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_diamond_2hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_diamond_3hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_diamond_3plus_hours));

            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_platinum_1hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_platinum_3hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_platinum_6hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_platinum_6plus_hours));

            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_gsr_6hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_gsr_12hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_gsr_24hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_gsr_36hours));

            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_manja_1hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_manja_2hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_manja_3hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_manja_3plus_hours));
        }

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_vvip_1hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_vvip_2hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_vvip_3hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_vvip_3plus_hours)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_diamond_1hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_diamond_2hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_diamond_3hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_diamond_3plus_hours)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_platinum_1hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_platinum_3hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_platinum_6hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_platinum_6plus_hours)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_gsr_6hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_gsr_12hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_gsr_24hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_gsr_36hours)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_manja_1hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_manja_2hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_manja_3hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_manja_3plus_hours)).'</b>';

        return response()->json($result);
    }

    public function ttr_comply_notcomply_closed()
    {
        $jml_ttr_vvip = $ttl_ttr_vvip_1hours = $ttl_ttr_vvip_2hours = $ttl_ttr_vvip_3hours = $ttl_ttr_vvip_3plus_hours =
        $jml_ttr_diamond = $ttl_ttr_diamond_1hours = $ttl_ttr_diamond_2hours = $ttl_ttr_diamond_3hours = $ttl_ttr_diamond_3plus_hours =
        $jml_ttr_platinum = $ttl_ttr_platinum_1hours = $ttl_ttr_platinum_3hours = $ttl_ttr_platinum_6hours = $ttl_ttr_platinum_6plus_hours =
        $jml_ttr_gsr = $ttl_ttr_gsr_6hours = $ttl_ttr_gsr_12hours = $ttl_ttr_gsr_24hours = $ttl_ttr_gsr_36hours =
        $jml_ttr_manja = $ttl_ttr_manja_1hours = $ttl_ttr_manja_2hours = $ttl_ttr_manja_3hours = $ttl_ttr_manja_3plus_hours = 0;

        $data = DashboardModel::ttr_comply_notcomply_closed();

        $result = ['data' => [], 'footer' => []];

        foreach ($data as $k => $v)
        {
            $jml_ttr_vvip                 =  $v->ttr_vvip_1hours + $v->ttr_vvip_2hours + $v->ttr_vvip_3hours + $v->ttr_vvip_3plus_hours;
            $ttl_ttr_vvip_1hours          += $v->ttr_vvip_1hours;
            $ttl_ttr_vvip_2hours          += $v->ttr_vvip_2hours;
            $ttl_ttr_vvip_3hours          += $v->ttr_vvip_3hours;
            $ttl_ttr_vvip_3plus_hours     += $v->ttr_vvip_3plus_hours;

            $jml_ttr_diamond              =  $v->ttr_diamond_1hours + $v->ttr_diamond_2hours + $v->ttr_diamond_3hours + $v->ttr_diamond_3plus_hours;
            $ttl_ttr_diamond_1hours       += $v->ttr_diamond_1hours;
            $ttl_ttr_diamond_2hours       += $v->ttr_diamond_2hours;
            $ttl_ttr_diamond_3hours       += $v->ttr_diamond_3hours;
            $ttl_ttr_diamond_3plus_hours  += $v->ttr_diamond_3plus_hours;

            $jml_ttr_platinum             =  $v->ttr_platinum_1hours + $v->ttr_platinum_3hours + $v->ttr_platinum_6hours + $v->ttr_platinum_6plus_hours;
            $ttl_ttr_platinum_1hours      += $v->ttr_platinum_1hours;
            $ttl_ttr_platinum_3hours      += $v->ttr_platinum_3hours;
            $ttl_ttr_platinum_6hours      += $v->ttr_platinum_6hours;
            $ttl_ttr_platinum_6plus_hours += $v->ttr_platinum_6plus_hours;

            $jml_ttr_gsr                  =  $v->ttr_gsr_6hours + $v->ttr_gsr_12hours + $v->ttr_gsr_24hours + $v->ttr_gsr_36hours;
            $ttl_ttr_gsr_6hours           += $v->ttr_gsr_6hours;
            $ttl_ttr_gsr_12hours          += $v->ttr_gsr_12hours;
            $ttl_ttr_gsr_24hours          += $v->ttr_gsr_24hours;
            $ttl_ttr_gsr_36hours          += $v->ttr_gsr_36hours;

            $jml_ttr_manja                =  $v->ttr_manja_1hours + $v->ttr_manja_2hours + $v->ttr_manja_3hours + $v->ttr_manja_3plus_hours;
            $ttl_ttr_manja_1hours         += $v->ttr_manja_1hours;
            $ttl_ttr_manja_2hours         += $v->ttr_manja_2hours;
            $ttl_ttr_manja_3hours         += $v->ttr_manja_3hours;
            $ttl_ttr_manja_3plus_hours    += $v->ttr_manja_3plus_hours;

            $result['data'][$k][] = $v->witel;
            
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_vvip_1hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_vvip_2hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_vvip_3hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_vvip_3plus_hours));

            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_diamond_1hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_diamond_2hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_diamond_3hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_diamond_3plus_hours));

            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_platinum_1hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_platinum_3hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_platinum_6hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_platinum_6plus_hours));

            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_gsr_6hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_gsr_12hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_gsr_24hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_gsr_36hours));

            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_manja_1hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_manja_2hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_manja_3hours));
            $result['data'][$k][] = str_replace(',', '.', number_format($v->ttr_manja_3plus_hours));
        }

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_vvip_1hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_vvip_2hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_vvip_3hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_vvip_3plus_hours)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_diamond_1hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_diamond_2hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_diamond_3hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_diamond_3plus_hours)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_platinum_1hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_platinum_3hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_platinum_6hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_platinum_6plus_hours)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_gsr_6hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_gsr_12hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_gsr_24hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_gsr_36hours)).'</b>';

        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_manja_1hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_manja_2hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_manja_3hours)).'</b>';
        $result['footer'][] = '<b>'.str_replace(',', '.', number_format($ttl_ttr_manja_3plus_hours)).'</b>';

        return response()->json($result);
    }

    public function dashboard_produktif()
    {
        $ttl_teknisi = $jml_sisa_order = $ttl_order_ao = $ttl_order_orbit = $ttl_order_addon = $ttl_order_pda = $ttl_sisa_order = $jml_ps = $ttl_ps_ao = $ttl_ps_orbit = $ttl_ps_addon = $ttl_ps_pda = $ttl_ps = $jml_order_kendala =$ttl_order_kp = $ttl_order_kt = $ttl_order_kendala = $jml_point = $ttl_point = 0;

        $start_date = Input::get('start_date') ?? date('Y-m-01');
        $end_date   = Input::get('end_date') ?? date('Y-m-d');

        $data  = DashboardModel::dashboard_produktif($start_date, $end_date);

        $result = ['data' => [], 'footer' => []];

        foreach ($data as $k => $v)
        {
            $ttl_teknisi       += $v->jml_teknisi;

            $jml_sisa_order    = ($v->order_ao + $v->order_orbit + $v->order_addon + $v->order_pda);
            $ttl_order_ao      += $v->order_ao;
            $ttl_order_orbit   += $v->order_orbit;
            $ttl_order_addon   += $v->order_addon;
            $ttl_order_pda     += $v->order_pda;
            $ttl_sisa_order    += $jml_sisa_order;

            $jml_ps            = ($v->ps_ao + $v->ps_orbit + $v->ps_addon + $v->ps_pda);
            $ttl_ps_ao         += $v->ps_ao;
            $ttl_ps_orbit      += $v->ps_orbit;
            $ttl_ps_addon      += $v->ps_addon;
            $ttl_ps_pda        += $v->ps_pda;
            $ttl_ps            += $jml_ps;

            $jml_order_kendala = ($v->order_kp + $v->order_kt);
            $ttl_order_kp      += $v->order_kp;
            $ttl_order_kt      += $v->order_kt;
            $ttl_order_kendala += $jml_order_kendala;

            $jml_point         = ($v->ps_ao * 4) + ($v->ps_orbit * 2) + ($v->ps_addon * 2) + ($v->ps_pda * 2);
            $ttl_point         += $jml_point;

            $result['data'][$k][] = $v->sektor;
            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=teknisi&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->jml_teknisi.'</a>';

            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=order_ao&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->order_ao.'</a>';
            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=order_orbit&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->order_orbit.'</a>';
            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=order_addon&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->order_addon.'</a>';
            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=order_pda&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->order_pda.'</a>';
            $result['data'][$k][] = $jml_sisa_order;

            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=ps_ao&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->ps_ao.'</a>';
            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=ps_orbit&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->ps_orbit.'</a>';
            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=ps_addon&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->ps_addon.'</a>';
            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=ps_pda&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->ps_pda.'</a>';
            $result['data'][$k][] = $jml_ps;

            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=order_kp&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->order_kp.'</a>';
            $result['data'][$k][] = '<a href="/dashboard/productivity-sector-detail?sektor='.$v->sektor.'&status=order_kt&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$v->order_kt.'</a>';
            $result['data'][$k][] = $jml_order_kendala;

            $result['data'][$k][] = $jml_point;
            
            $k++;
        }

        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=teknisi&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_teknisi.'</a></b>';

        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=order_ao&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_order_ao.'</a></b>';
        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=order_orbit&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_order_orbit.'</a></b>';
        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=order_addon&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_order_addon.'</a></b>';
        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=order_pda&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_order_pda.'</a></b>';
        $result['footer'][] = '<b>'.$ttl_sisa_order.'</b>';

        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=ps_ao&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_ps_ao.'</a></b>';
        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=ps_orbit&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_ps_orbit.'</a></b>';
        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=ps_addon&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_ps_addon.'</a></b>';
        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=ps_pda&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_ps_pda.'</a></b>';
        $result['footer'][] = '<b>'.$ttl_ps.'</b>';

        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=order_kp&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_order_kp.'</a></b>';
        $result['footer'][] = '<b><a href="/dashboard/productivity-sector-detail?sektor=all&status=order_kt&start_date='.$start_date.'&end_date='.$end_date.'" style="color: black">'.$ttl_order_kt.'</a></b>';
        $result['footer'][] = '<b>'.$ttl_order_kendala.'</b>';

        $result['footer'][] = '<b>'.$ttl_point.'</b>';

        return response()->json($result);
    }
}
?>