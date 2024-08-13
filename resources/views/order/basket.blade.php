@extends('layout')

@section('css')
<style>
    .leaflet-layer,
.leaflet-control-zoom-in,
.leaflet-control-zoom-out,
.leaflet-control-attribution {
  filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
}
.productivity_table th {
        padding : 10px !important;
        background-color: #070707;
        color : #ffffff;
        border: 1px solid rgb(148, 148, 148);
        text-align: center;
        vertical-align: middle;
        border-collapse: collapse;
    }
    #schedule .example2{
            background-color: #3eb698;
        }
        #schedule .ogp{
            color: #2c0000;
            font-weight: bold;
            background-color: #c7ab50;
        }
        #schedule .needProgress{
            color: #2c0000;
            font-weight: bold;
            background-color: #3b73c7;
        }
        #schedule .kendala{
            color: #2c0000;
            font-weight: bold;
            background-color: #d71b1b;
        }
        #schedule .finished{
            color: #2c0000;
            font-weight: bold;
            background-color: #1fbf67;
        }
        
        
        
    </style>
   
    
@endsection

@section('title', 'Workforce Management')

@section('filter')
<div class="d-flex align-items-center py-3 py-md-1">
    <!--begin::Wrapper-->
    <div class="me-4">
        <!--begin::Menu-->
        <a href="#" class="btn btn-custom btn-active-white btn-flex btn-color-white btn-active-color-white" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
        <i class="ki-duotone ki-filter fs-5 me-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>Filter</a>
        <!--begin::Menu 1-->
        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_6678174455ec6">
            <!--begin::Header-->
            <div class="px-7 py-5">
                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
            </div>
            <!--end::Header-->
            <!--begin::Menu separator-->
            <div class="separator border-gray-200"></div>
            <!--end::Menu separator-->
            <!--begin::Form-->
            <div class="px-7 py-5">
                <!--begin::Input group-->
                <div class="mb-10">
                    <!--begin::Label-->
                    <label class="form-label fw-semibold">WITEL:</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <div>
                        <select id="witel" name="witel" class="form-select form-select-solid">
                            <option value="">--</option>
                            @foreach ($getWitel as $r)
                            <option value="{{ $r->id }}" <?php if ($r->id==$witel) { echo 'selected'; } else { }  ?>>{{ $r->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" id="periode" value="{{ $periode }}" />
                    </div>
                    <!--end::Input-->
                </div>
                <div class="mb-10">
                    <label class="form-label fw-semibold">SEKTOR:</label>
                    <div>
                        <select id="sector" class="form-select form-select-solid">
                            <option value="ALL">--</option>
                            @foreach ($sectorList as $result)
                            <option value="{{ $result->id }}" <?php if ($result->id==$sector) { echo 'selected'; } else { }  ?> >{{ $result->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Actions-->
                <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary" id="filter" data-kt-menu-dismiss="true">Apply</button>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Form-->
        </div>
        <!--end::Menu 1-->
        <!--end::Menu-->
    </div>
    <!--end::Wrapper-->
</div>
@endsection
@section('content')

<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/assets/js/jquery.schedule-master/dist/css/style.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin=""></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
<style>
    .white_text {
        color : #FFF;
    }
    .green_text {
        color : #2ecc71;
    }
    .red_text {
        color : #df1313;
    }
    /* .pink_background {
        background-color : #efdada !important;
    }
    .gray_background {
        background-color : #efdada !important;
    } */
    /*
     */
     .productivity_table td {
        padding : 10px !important;
        border: 1px solid rgb(242, 242, 242);
        text-align: center;
        vertical-align: middle;
        border-collapse: collapse;
    }
    .btn-custom {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }
    .btn-custom a:hover {
        color : #000 !important;
    }
    #map {
        height: 400px;
    }
</style>
<div class="row">
    {{-- <div class="col-xl-12" style="margin-top:-45px">
        <button style="float:right" type="button" class="btn btn-custom btn-active-white btn-flex btn-color-white btn-active-color-white" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
            <i class="ki-duotone ki-plus fs-2"></i>Filter
        </button>&nbsp;&nbsp;
        <button style="float:right; margin-right: 10px !important;" type="button" class="btn btn-custom btn-active-white btn-flex btn-color-white btn-active-color-white" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
            <i class="ki-duotone ki-plus fs-2"></i>REKOMENDASI : BANTEK DARI ASSURANCE KE PROVISIONING
        </button>
        <button style="float:right;  margin-right: 10px !important;" type="button" class="btn btn-custom btn-active-white btn-flex btn-color-white btn-active-color-white" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
            <i class="ki-duotone ki-plus fs-2"></i>SEKTOR PELAIHARI | PROVISIONING : KUADRAN III & ASSURANCE : KUADRAN II
        </button>
        
    </div> --}}

</div>

<div class="row">
    <div class="col-xl-2 boxCustom">
        <a href="/schedule/list/{{ $sector }}/1/{{ $periode }}/ALL/ALL">
            <div class="card card-custom gutter-b card-stretch">
                <div class="card-header border-0">
                    <div class="card-title">
                        <div class="card-label">
                            <div class="font-weight-bolder">Scheduled</div>
                            <div class="font-size-sm  green_text"> {{ count($getScheduleTech) }} org</div>
                        </div>
                    </div> 
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 boxCustom">
        <a href="/schedule/list/{{ $sector }}/1/{{ $periode }}/ALL/1">
            <div class="card card-custom gutter-b card-stretch">
                <div class="card-header border-0">
                    <div class="card-title">
                        <div class="card-label">
                            <div class="font-weight-bolder">Approved</div>
                            <div class="font-size-sm  green_text">{{ count($getApproval) }} org</div>
                        </div> 
                    </div> 
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 boxCustom">
        <a href="/schedule/list/{{ $sector }}/1/{{ $periode }}/ALL/2">
            <div class="card card-custom gutter-b card-stretch">
                <div class="card-header border-0">
                    <div class="card-title">
                        <div class="card-label">
                            <div class="font-weight-bolder">Suspended</div>
                            <div class="font-size-sm  red_text">{{ count($getSuspend) }}  org</div>
                        </div>
                    </div> 
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 boxCustom">
        <a href="/schedule/list/{{ $sector }}/1/{{ $periode }}/1/ALL">
            <div class="card card-custom gutter-b card-stretch">
                <div class="card-header border-0">
                    <div class="card-title">
                        <div class="card-label">
                            <div class="font-weight-bolder">Hold</div>
                            <div class="font-size-sm  red_text">{{ count($getHold) }} org</div>
                        </div>
                    </div> 
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 boxCustom">
        <a href="/schedule/list/{{ $sector }}/1/{{ $periode }}/1/0">
            <div class="card card-custom gutter-b card-stretch">
                <div class="card-header border-0">
                    <div class="card-title">
                        <div class="card-label">
                            <div class="font-weight-bolder">Ready to GO</div>
                            <div class="font-size-sm  green_text">{{ count($getReadytoGo) }} org</div>
                        </div>
                    </div> 
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 boxCustom">
        <a href="/schedule/list/{{ $sector }}/1/{{ $tommorow }}/ALL/ALL">
        <div class="card card-custom gutter-b card-stretch">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="card-label">
                        <div class="font-weight-bolder">Schedule H+1</div>
                        <div class="font-size-sm  green_text">{{ count($getScheduleTommorow) }} org</div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalLong" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary font-weight-bold">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <br />
        <br />

        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4">BASKET ORDER</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_5">MAP VIEW</a>
            </li>
        </ul>
        
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show" id="kt_tab_pane_4" role="tabpanel">
                <div class="card" id="basket" role="tabpanel">
                    <div class="card-header border-0">
                        <div class="card-title">
                            <div class="card-label">
                                <div class="font-weight-bolder">Main Order</div>
                            </div>
                        </div> 
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($orderTypeGetMain as $r)
                            <div class="col-xl-2 boxCustom">
                                <div class="card card-custom gutter-b card-stretch">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <div class="card-label">
                                                <div class="font-weight-bolder">{{ $r->name }}</div>
                                                <div class="font-size-sm  green_text"><a href="/order/basket/list/{{ $r->order_type }}/1/{{ $sector }}" >{{ $r->jumlah }}</a>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>                        
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-header border-0">
                        <div class="card-title">
                            <div class="card-label">
                                <div class="font-weight-bolder">Responsibility Order</div>
                            </div>
                        </div> 
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($orderTypeGetResp as $r)
                            <div class="col-xl-2">
                                <div class="card card-custom gutter-b card-stretch">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <div class="card-label">
                                                <div class="font-weight-bolder">{{ $r->name }}</div>
                                                <div class="font-size-sm  green_text">{{ $r->jumlah }}</div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show active" id="kt_tab_pane_5" role="tabpanel">
                <div id="mapView" role="tabpanel">
                    <div class="card card-custom gutter-b card-stretch">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="card-label">
                                    <div class="font-weight-bolder">MAP ORDER</div>
                                </div>
                            </div> 
                        </div>
                        <div class="card-body" style="padding:30px !important">
                            <div id="map">
            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

    </div>
    <div class="col-xl-12" style="margin-top:10px">
        <div class="card card-custom gutter-b card-stretch gray_background">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="card-label">
                        <div class="font-weight-bolder">TIMESLOT MANAGEMENT</div>
                    </div>
                </div> 
            </div>
            <div class="card-body" style="padding:30px !important">
                <div id="schedule"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-12" style="margin-top:10px">
        <div class="card card-custom gutter-b card-stretch gray_background">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="card-label">
                        <div class="font-weight-bolder">MAPPING ORDER</div>
                    </div>
                </div> 
            </div>
            <div class="card-body table-responsive ">
                <table class="table productivity_table" border=1>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Team</th>
                        <th colspan=3>Order</th>
                        <th colspan=3>Progress</th>
                        <th colspan=3>UP</th>
                        <th rowspan="2">Target</th>
                        <th rowspan="2">Poin</th>
                        <th colspan=3>Kendala</th>
                    </tr>
                    <tr>
                        <th>ASR</th>
                        <th>PROV</th>
                        <th>TTL</th>
                        <th>ASR</th>
                        <th>PROV</th>
                        <th>TTL</th>
                        <th>ASR</th>
                        <th>PROV</th>
                        <th>TTL</th>
                        <th>ASR</th>
                        <th>PROV</th>
                        <th>TTL</th>
                    </tr>
                    @foreach ($getSchedule as $n => $r)
                    <tr>
                        <td>{{ ++$n }} </td>
                        <td style="text-align:left !important">{{ $r->name }}</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>8</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    @endforeach
                    <?php
                    $target_sector_today = count($getSchedule)*8;
                    ?>
                    <tr>
                        <td colspan="2">TOTAL</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>{{ $target_sector_today }}</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-12" style="margin-top:10px">
        <div class="card card-custom gutter-b card-stretch gray_background">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="card-label">
                        <div class="font-weight-bolder">PRODUCTIVITY POIN PERIOD</div>
                    </div>
                </div> 
            </div>
            <div class="card-body table-responsive" style="padding:30px !important">
                <table class="table productivity_table" border=1>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">NIK</th>
                        <th rowspan="2">Nama</th>
                        <th colspan=6>PROV</th>
                        <th colspan=5>ASR</th>
                        <th colspan=3>KONS</th>
                        <th rowspan="2">Target</th>
                        <th rowspan="2">Poin</th>
                    </tr>
                    <tr>
                        <th>AO</th>
                        <th>MO</th>
                        <th>PDA</th>
                        <th>ORBIT</th>
                        <th>DISMANTLE</th>
                        <th>TOTAL</th>
                        <th>B2C</th>
                        <th>B2B</th>
                        <th>Unspec</th>
                        <th>Maint</th>
                        <th>TOTAL</th>
                        <th>PT2</th>
                        <th>ODP_Expand</th>
                        <th>TOTAL</th>
                    </tr>
                    @foreach($getEmployee as $n => $r)
                    <tr>
                        <td>{{ ++$n }}</td>
                        <td>{{ $r->nik }}</td>
                        <td style="text-align:left !important">{{ $r->name }}</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="3">TOTAL</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>
                        <th>0</th>

                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>
<button type="button" class="btn btn-primary d-none" id="second" data-bs-toggle="modal" data-bs-target="#kt_modal_1">
    Launch demo modal
</button>

<div class="modal fade" tabindex="-1" id="kt_modal_1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Dispatch Order</h3>
                
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <form action="/order/dispatchSave" method="post">
                <input type="hidden" value="{{ $periode }}" name="assigned_date">
                <input type="hidden" value="{{ $witel }}" name="witel">
                <input type="hidden" value="{{ $sector }}" name="sector">
                <input type="hidden" value="{{ $currentUrl }}" name="backUrl">
                <div class="row">
                    <div class="col-sm-2">
                         
                        <label class="fw-semibold fs-6 mb-2">Start Time :</label>
                        <input type="text" class="form-control" name="startTime" id="startTime" />
                        
                    </div>
                    <div class="col-sm-2">
                         
                        <label class="fw-semibold fs-6 mb-2">End Time :</label>
                        <input type="text" class="form-control" name="endTime" id="endTime" />
                        
                    </div>
                    <div class="col-sm-4">
                        <label class="fw-semibold fs-6 mb-2">Team :</label>
                        <input type="text" id="teamName" name="teamName" class="form-control" /><br />
                        <input type="hidden" id="teamID" name="team" class="form-control" /><br />
                    </div>
                    <div class="col-sm-4">
                        <label class="fw-semibold fs-6 mb-2">Order :</label>
                        <select id="SelectBasketOrder" name="order_type_id" class="form-control">
                            <option value="">--</option>
                            @foreach ($orderTypeGetMain as $r)
                            <option value="{{ $r->order_type }}">{{ $r->name }} ({{ $r->jumlah }})</option>
                            @endforeach
                            <option value="RECOMMEND">RECOMENDATION</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="required fw-semibold fs-6 mb-2">ORDER ID</label>
                        <input type="text" class="form-control" name="order_id" id="source_id" readonly />
                    </div>
                    <div class="col-sm-4">
                        <label class="fw-semibold fs-6 mb-2">ODP</label>
                        <input type="text" class="form-control" name="odp" id="odp" readonly />
                    </div>
                    <div class="col-sm-4">
                        <label class="fw-semibold fs-6 mb-2">ALAMAT</label>
                        <input type="text" class="form-control" name="alamat" id="alamat" readonly />
                    </div>
                    <div class="col-sm-12 my-4 ">
                        <input type="submit" class="btn btn-primary" value="Dispatch">
                    </div>
                    <div class="col-sm-12 table-responsive my-4 ">
                        
                        <table class="table table-hover table-rounded table-striped border gy-7 gs-7" id="tableDispatchOrder" >
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>TYPE</th>
                                    <th>SOURCE ID</th>
                                    <th>MH</th>
                                    <th>ORDER</th>
                                    <th>STO</th>
                                    <th>ODP</th>
                                    <th>ALAMAT</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                
                
            </form>
            </div>

            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js" type="text/javascript" language="javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="/assets/js/jquery.schedule-master/src/js/jq.schedule.js"></script>
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
    function addLog(type, message){
        var $log = $('<tr />');
        $log.append($('<th />').text(type));
        $log.append($('<td />').text(message ? JSON.stringify(message) : ''));
        $("#logs table").prepend($log);
    }
    jQuery(document).ready(function($) {
        $("#filter").click(function() {
            var witel = $("#witel").val();
            var sector = $("#sector").val();
            var periode = $("#periode").val();
            var url = "/order/basket/"+witel+"/"+sector+"/"+periode;
            document.location.replace(url); // Redirect to the link's URL
        });
        $("#witel").on('change', function() {
            $idWitel = $(this).val();
            $('#sector').empty();
            $.ajax({
                url: '/ajax/getSector/'+$idWitel,
                dataType: 'json', // Expect JSON response
                success: function(data) {
                    // Populate the second select box with fetched data
                    var options = '';
                    $.each(data, function(index, item) {
                        options += '<option value="' + item.id + '">' + item.name + '</option>';
                    });
                    $('#sector').html(options).show();
                },
                error: function() {
                    console.error('Error fetching data from the server.');
                }
            });
        });
        $("#logs").append('<table class="table">');
        var isDraggable = true;
        var isResizable = true;
        <?php
            $teamID = array();
            foreach ($getSchedule as $n => $r) {
                $teamID[$n] = $r->teamID;
            }
        ?>
        <?php
            $teamName = array();
            foreach ($getSchedule as $n => $r) {
                $teamName[$n] = $r->name;
            }
        ?>
        var timelineAlias = <?php echo json_encode($teamName) ?>;
        var teamAlias = <?php echo json_encode($teamID) ?>;
        
        var $sc = $("#schedule").timeSchedule({
            startTime: "08:00", // schedule start time(HH:ii)
            endTime: "23:00",   // schedule end time(HH:ii)
            widthTime: 120 * 10,  // cell timestamp example 10 minutes
            timeLineY: 60,       // height(px)
            verticalScrollbar: 20,   // scrollbar (px)
            timeLineBorder: 2,   // border(top and bottom)
            bundleMoveWidth: 6,  // width to move all schedules to the right of the clicked time line cell
            draggable: isDraggable,
            resizable: isResizable,
            resizableLeft: true,
            rows : {
            @foreach ($getSchedule as $n => $r)
                '<?php echo $n++; ?>' : {
                    title : '<?php echo $r->name ?>',
                    schedule:[
                        @foreach($orderTimeslot[$r->teamID] as $x)
                        {   
                            
                            start: '{{ $x->timeStart }}',
                            end: '{{ $x->timeEnd }}',
                            text: '{{ $x->order_id }}',
                            data: {
                                <?php
                                switch($x->status){
                                    case 1 : 
                                        $class = 'ogp';
                                    break;
                                    case 2 :
                                        $class = 'kendala'; 
                                    break;
                                    case 3 :
                                        $class = 'kendala'; 
                                    break;
                                    case 4 :
                                        $class = 'kendala'; 
                                    break;
                                    case 5 :
                                        $class = 'finished'; 
                                    break;
                                    default :
                                        $class = 'needProgress'; 
                                    break;
                                }
                                ?> 
                                class : '{{ $class }}', 
                                order_id : '{{ $x->order_id }}'
                            }
                        },
                        @endforeach
                    ]
                },
            @endforeach
      
            },
            onChange: function(node, data){
                console.log(data);
                console.log(timelineAlias[data.timeline]);
                console.log(data.text);
                Swal.fire({
                    text: "Re-dispatch "+data.text+" to "+timelineAlias[data.timeline]+" at "+data.start+" - "+data.end+" Successfully",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Close",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
                $.ajax({url: '/order/dispatchAjax/'+teamAlias[data.timeline]+'/{{ $periode }}/'+data.start+'/'+data.end+'/'+data.text})
                .done( (data) => {

                });
                addLog('onChange', data);
            },
            onInitRow: function(node, data){
                addLog('onInitRow', data);
            },
            onClick: function(node, data){
                addLog('onClick', data);
            },
            onAppendRow: function(node, data){
                addLog('onAppendRow', data);
            },
            onAppendSchedule: function(node, data){
                addLog('onAppendSchedule', data);
                if(data.data.class){
                    node.addClass(data.data.class);
                }
                if(data.data.image){
                    var $img = $('<div class="photo"><img></div>');
                    $img.find('img').attr('src', data.data.image);
                    node.prepend($img);
                    node.addClass('sc_bar_photo');
                }
            },
            onScheduleClick: function(node, time, timeline){
                document.getElementById("second").click(); // Triggers the click event on the second button
                document.getElementById("startTime").value = time;
                document.getElementById("teamName").value = timelineAlias[timeline];
                document.getElementById("teamID").value = teamAlias[timeline];
           
                const sector = document.getElementById('sector').value;
                const selectElement = document.getElementById('SelectBasketOrder');
                selectElement.addEventListener('change', function() {
                    const orderType = this.value;
                    const table = document.getElementById('tableDispatchOrder');
                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = '';
                    fetch('/order/ajaxBasket/'+orderType+'/'+sector)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the table with data
                        const table = document.getElementById('tableDispatchOrder');
                        const tbody = table.querySelector('tbody');
                        let numb = 1;
                        data.forEach(item => {
                            const row = tbody.insertRow();
                            row.insertCell().textContent = numb++;
                            row.insertCell().textContent = item.orderType;
                            row.insertCell().textContent = item.source_id;
                            row.insertCell().textContent = item.MH;
                            row.insertCell().textContent = item.JENISORDER;
                            row.insertCell().textContent = item.STO;
                            row.insertCell().textContent = item.ODP;
                            row.insertCell().textContent = item.ALAMAT;
                            // Add other cells as needed
                        });
                    })
                    .catch(error => console.error('Error fetching data:', error));
                });

                
            },
        });
        $('#event_timelineData').on('click', function(){
            addLog('timelineData', $sc.timeSchedule('timelineData'));
        });
        $('#event_scheduleData').on('click', function(){
            addLog('scheduleData', $sc.timeSchedule('scheduleData'));
        });
        $('#event_resetData').on('click', function(){
            $sc.timeSchedule('resetData');
            addLog('resetData');
        });
        $('#event_resetRowData').on('click', function(){
            $sc.timeSchedule('resetRowData');
            addLog('resetRowData');
        });
        $('#event_setDraggable').on('click', function(){
            isDraggable = !isDraggable;
            $sc.timeSchedule('setDraggable', isDraggable);
            addLog('setDraggable', isDraggable ? 'enable' : 'disable');
        });
        $('#event_setResizable').on('click', function(){
            isResizable = !isResizable;
            $sc.timeSchedule('setResizable', isResizable);
            addLog('setResizable', isResizable ? 'enable' : 'disable');
        });
        $('.ajax-data').on('click', function(){
            $.ajax({url: './data/'+$(this).attr('data-target')})
                .done( (data) => {
                    addLog('Ajax GetData', data);
                    $sc.timeSchedule('setRows', data);
                });
        });
        $('#clear-logs').on('click', function(){
            $('#logs .table').empty();
        });
    });
</script>
<script>
    const table = document.getElementById('tableDispatchOrder');
    const tbody = table.querySelector('tbody');

    tbody.addEventListener('click', function (event) {
        const targetRow = event.target.closest('tr'); // Find the closest parent <tr> element
        if (targetRow) {
            const cells = targetRow.getElementsByTagName('td');
            document.getElementById('source_id').value = cells[2].textContent; 
            document.getElementById('odp').value = cells[6].textContent; 
            document.getElementById('alamat').value = cells[7].textContent; 
            let MH = cells[3].textContent;
            const StartTime = document.getElementById('startTime').value;
            console.log('start time : ' + StartTime);

            const [hours, minutes] = StartTime.split(':').map(Number);
            const totalMinutes = hours * 60 + minutes + MH * 60; // Convert everything to minutes
            const newHours = Math.floor(totalMinutes / 60) % 24; // Calculate new hours and wrap around if needed
            const newMinutes = Math.round(totalMinutes % 60); // Get minutes and handle rounding

            // Ensure two-digit formatting
            const formattedHours = newHours.toString().padStart(2, '0');
            const formattedMinutes = newMinutes.toString().padStart(2, '0');

            const newTime = `${formattedHours}:${formattedMinutes}`;
            document.getElementById('endTime').value = newTime;

        }
    });
    var center = { lng: {{ $sektorLongitude }}, lat: {{ $sektorLatitude }} };
    var map = L.map('map').setView(center, 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var teknisiLayer = L.layerGroup().addTo(map); // Create the teknisiLayer
    var teknisiIcon = L.icon({
        iconUrl: "/images/technician.png",
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32],
    });

    // Fetch teknisi data and add markers to teknisiLayer
    fetch('/tech/location/{{ $sector }}')
        .then(response => response.json()) // Parse the JSON from the response
        .then(teknisiData => {
            teknisiData.forEach(teknisi => {
                if (teknisi && teknisi.latitude && teknisi.longitude) {
                    var marker = L.marker([teknisi.latitude, teknisi.longitude], {
                        icon: teknisiIcon,
                    }).addTo(teknisiLayer);
                    
                    marker.bindPopup(`
                        <div class="custom-popup table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td style="font-size: 10px">Teknisi</td>
                                        <td style="font-size: 10px">:</td>
                                        <td style="font-size: 10px">${teknisi.name} ${teknisi.nik}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px">Latitude</td>
                                        <td style="font-size: 10px">:</td>
                                        <td style="font-size: 10px">${teknisi.latitude}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px">Longitude</td>
                                        <td style="font-size: 10px">:</td>
                                        <td style="font-size: 10px">${teknisi.longitude}</td>
                                    </tr> 
                                    <tr>
                                        <td style="font-size: 10px">Last Update</td>
                                        <td style="font-size: 10px">:</td>
                                        <td style="font-size: 10px">${teknisi.last_location_update}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>`);
                    
                    // marker.bindTooltip(`${teknisi.name} (${teknisi.nik})`, { permanent: true, interactive: true })
                    //     .openTooltip();
                } else {
                    console.warn('Invalid teknisi data:', teknisi);
                }
            });
            map.addLayer(teknisiLayer); // Add the teknisiLayer to the map after it is populated
        })
        .catch(error => {
            console.error('Failed to load teknisi data:', error);
        });

    // Additional markers
    var LeafIcon = L.Icon.extend({
        options: {
            shadowUrl: 'leaf-shadow.png',
            iconSize:     [38, 95],
            shadowSize:   [50, 64],
            iconAnchor:   [22, 94],
            shadowAnchor: [4, 62],
            popupAnchor:  [-3, -76]
        }
    });

    var greenIcon = new LeafIcon({iconUrl: 'leaf-green.png'});
    var redIcon = new LeafIcon({iconUrl: 'leaf-red.png'});
    var orangeIcon = new LeafIcon({iconUrl: 'leaf-orange.png'});

    var mGreen = L.marker([51.5, -0.09], {icon: greenIcon}).bindPopup('I am a green leaf.').addTo(map);
    var mRed = L.marker([51.495, -0.083], {icon: redIcon}).bindPopup('I am a red leaf.').addTo(map);
    var mOrange = L.marker([51.49, -0.1], {icon: orangeIcon}).bindPopup('I am an orange leaf.').addTo(map);

    
    </script>
@endsection
