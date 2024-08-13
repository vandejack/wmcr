@extends('layout')

@section('css')
<style>
    th, td {
        white-space: nowrap;
    }
    .productivity_table th {
        padding : 5px !important;
        background-color: #636363;
        color : #ffffff;
        border: 1px solid rgb(242, 242, 242);
        text-align: center;
        vertical-align: middle;
        border-collapse: collapse;
    }
    .productivity_table td {
        padding : 5px !important;
        border: 1px solid rgb(242, 242, 242);
        text-align: center;
        vertical-align: middle;
        border-collapse: collapse;
    }
</style>
@endsection

@section('title', 'Schedule Management')

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
                            <option value="{{ $result->id }}" <?php if ($result->id==$witel) { echo 'selected'; } else { }  ?> >{{ $result->name }}</option>
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
<div class="row">
    <div class="col-xl-12" >
        @if ($employee<>NULL) 
        <div class="card shadow-sm">
            <div class="card-body pb-4">
                <div class="table-responsive">
                    <table class="productivity_table ">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800 px-7 text-center">
                                <th rowspan="2">#</th>
                                <th rowspan="2">NIK</th>
                                <th width="100%" rowspan="2" >Nama</th>
                                <th rowspan="2">Flagging</th>
                                <th colspan="31">TGL</th>
                            </tr>
                            <tr>
                                @for ($x=01;$x<=$days;$x++)
                                <th>{{ $x }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee as $num => $result)
                            <tr>
                                <td>{{ ++$num }}</td>
                                <td>{{ $result->nik }}</td>
                                <td style="text-align: left !important">{{ $result->name }}</td>
                                <td>{{ $result->flagging }}</td>
                                @for ($x=1;$x<=$days;$x++)
                                    <?php
                                        $input = $periode.'-'.$x;
                                        $date = strtotime($input);
                                        $status = @$scheduleData[$result->nik][date('Y-m-d',$date)]['status'];
                                        if ($status==1){
                                            $btnLabel = 'success';
                                        } else {
                                           
                                            $btnLabel = 'danger';
                                        }
                                    ?>
                                <td>
                                    <a href="/schedule/update/{{ @$scheduleData[$result->nik][date('Y-m-d',$date)]['id'] }}" class="btn btn-{{ $btnLabel }}">
                                       
                                    </a>
                                </td>
                                @endfor
                            </tr>
                            @endforeach
                        
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">Hadir</th>
                                @for ($x=1;$x<=$days;$x++)
                                <?php  
                                    $input = $periode.'-'.$x;
                                    $date = strtotime($input);
                                ?>
                                <th>{{ @$scheduleSectorDaily[date('Y-m-d',$date)]['hadir'] }}</th>
                                @endfor
                            </tr>
                            <tr>
                                <th colspan="4">Tidak Hadir</th>
                                @for ($x=1;$x<=$days;$x++)
                                <?php  
                                    $input = $periode.'-'.$x;
                                    $date = strtotime($input);
                                ?>
                                <th>{{ @$scheduleSectorDaily[date('Y-m-d',$date)]['tidak_hadir'] }}</th>
                                @endfor
                            </tr>
                            <tr>
                                <th colspan="4">Hadir (%)</th>
                              
                                    @for ($x=1;$x<=$days;$x++)
                                    <?php  
                                        $input = $periode.'-'.$x;
                                        $date = strtotime($input);
                                        $jumlah = @$scheduleSectorDaily[date('Y-m-d',$date)]['hadir']+@$scheduleSectorDaily[date('Y-m-d',$date)]['tidak_hadir'];
                                        $percent_hadir = @($scheduleSectorDaily[date('Y-m-d',$date)]['hadir']/$jumlah)*100;
                                    ?>
                                    <th>{{ round($percent_hadir) }}%</th>
                                    @endfor
                               
                            </tr>
                            <tr>
                                <th colspan="4">Tidak Hadir(%)</th>
                                @for ($x=1;$x<=$days;$x++)
                                <?php  
                                    $input = $periode.'-'.$x;
                                    $date = strtotime($input);
                                    $jumlah = @$scheduleSectorDaily[date('Y-m-d',$date)]['hadir']+@$scheduleSectorDaily[date('Y-m-d',$date)]['tidak_hadir'];
                                    $percent_hadir = @($scheduleSectorDaily[date('Y-m-d',$date)]['tidak_hadir']/$jumlah)*100;
                                ?>
                                <th>{{ round($percent_hadir) }}%</th>
                                @endfor
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @else 
        @endif
    </div>
</div>
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js" type="text/javascript" language="javascript"></script>
<script>
    $(function() {
        $("#filter").click(function() {
            var sector = $("#periode").val();
            var periode = $("#sector").val();
            var url = "/schedule/manage/"+periode+"/"+sector;
            document.location.replace(url); // Redirect to the link's URL
        });
    });
</script>
@endsection