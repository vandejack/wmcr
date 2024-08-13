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
        background-color: #FFF;
        color : #000;
        border: 1px solid rgb(242, 242, 242);
        text-align: center;
        vertical-align: middle;
        border-collapse: collapse;
    }

</style>
@endsection

@section('title', 'Dispatch Order')

@section('content')
<div class="row">
    <div class="col-xl-12" >
        <div class="card shadow-sm">
            <div class="card-body pb-4">
                <form method="post">
                    <div class="row">
                        <input type="hidden" name="id" readonly value="{{ $getBasket->id }}" />
                        <input type="hidden" name="backUrl" value="{{ $backUrl }}">
                        <div class="col-sm-4">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span>ID SOURCE ORDER</span>
                            </label>
                            <input type="text" name="order_id" readonly class="form-control mb-5" value="{{ $getBasket->source_id }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span>ORDER TYPE</span>
                            </label>
                            <input type="text" readonly class="form-control mb-5" value="{{ $getBasket->name }}">
                            <input type="hidden" name="order_type_id" value="{{ $getBasket->typeID }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span>MANHOURS NEEDED</span>
                            </label>
                            <input type="text" readonly class="form-control mb-5" value="{{ $getBasket->MH }}">
                        </div>
                        
                        <div class="col-sm-4">
                            <label class="form-label">ASSIGNED DATE</label>
                            <input class="form-control form-control-solid" name="assigned_date" placeholder="Pick Date to Dispatch" id="kt_daterangepicker_3"/>
                        </div>
                        <div class="col-sm-4">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span>TIME START</span>
                            </label>
                            <input class="form-control form-control-solid ps-12 flatpickr-input active mb-5" id="kt_datepicker_8" placeholder="Set time Start" name="timeStart" type="text" readonly="readonly">
                        </div>
                        <div class="col-sm-4">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span>TIME END</span>
                            </label>
                            <input class="form-control form-control-solid ps-12 flatpickr-input active mb-5" id="kt_datepicker_9" placeholder="Estimated Finished at" name="timeEnd" type="text" readonly="readonly">
                        </div>
                        
                        <div class="col-sm-6">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span>SEKTOR</span>
                            </label>
                            <input type="text" readonly class="form-control mb-5" value="{{ $getBasket->sectorName }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span>TEAM AVAILABLE</span>
                            </label>
                            <select id="team" name="team" class="select2-selection select2-selection--single form-select form-select-solid">
                                <option value="">--</option>
                                @foreach ($scheduleTeam as $r)
                                <option value="{{ $r->teamID }}">{{ $r->name }} (MH Avai. {{ $r->MH_available }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <input type="submit" name="submit" value="Dispatch" class="form-control btn btn-info" />
                        </div>
                    </div>
            
                </form>
            </div>
        </div>
    </div>
</div>
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js" type="text/javascript" language="javascript"></script>
<script>
$(function() {
    $("#kt_daterangepicker_3").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
        format: 'YYYY-MM-DD'
        },
        minYear: 1901,
        maxYear: parseInt(moment().format("YYYY"),12)
    });
    $("#kt_datepicker_8").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i", 
    });
    $("#kt_datepicker_9").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
});
</script>
@endsection