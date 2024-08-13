@extends('layout')

@section('css')
<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<style>
    .productivity_table th {
    padding : 10px !important;
    background-color: #636363;
    color : #ffffff;
    border: 1px solid rgb(242, 242, 242);
    text-align: center;
    vertical-align: middle;
    border-collapse: collapse;
}
.productivity_table td {
    padding : 10px !important;
    background-color: #FFF;
    color : #000;
    border: 1px solid rgb(242, 242, 242);
    text-align: center;
    vertical-align: middle;
}
</style>
@endsection
@include('partial.alerts')
@section('title', 'Basket Order List')

@section('content')


<div class="row">
    <div class="col-xl-12">
        <div class="card card-custom gutter-b tab-content card-stretch pink_background" id="home-tab">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="card-label">
                        <div class="font-weight-bolder">Basket List</div>
                    </div>
                </div> 
            </div>
            <div class="card-body table-responsive">
                <table class="table  table-striped gy-7 gs-7" id="productivity_table">
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th>No</th>
                        <th>Order ID</th>
                        <th>ORDER</th>
                        <th>CUSTOMER NAME</th>
                        <th>STO</th>
                        <th>ALPRO</th>
                        <th>ALAMAT</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($query as $num => $result)
                        <tr>
                            <td>{{ ++$num }}</td>
                            <td>{{ $result->source_id }}</td>
                            <td>{{ $result->JENISORDER }}</td>
                            <td>{{ $result->NAME }}</td>
                            <td>{{ $result->STO }}</td>
                            <td>{{ $result->ODP }}</td>
                            <td>{{ $result->ALAMAT }}</td>
                            <td>
                                <a class="btn btn-info" href="/order/dispatchManual/{{ $result->id }}/{{ date('Y-m-d') }}">Dispatch</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection