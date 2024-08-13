@extends('layout')

@section('css')
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
.btn {
    padding : 4px !important;
    font-size: 11px;
}
</style>
@endsection

@section('title', 'Employee List')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card card-custom gutter-b tab-content card-stretch pink_background" id="home-tab">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="card-label">
                        <div class="font-weight-bolder">List</div>
                    </div>
                </div> 
            </div>
            <div class="card-body">
                <table class="productivity_table" width="100%">
                    <tr>
                        <th>No</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>NIK</th>
                        <th>Name</th>
                        <th>Sektor</th>
                        <th>Approval</th>
                        <th>isHold</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($query as $num => $result)
                        <tr>
                            <td>{{ ++$num }}</td>
                            <td>{{ $result->periode }}</td>
                            <td>{{ $result->statusName }}</td>
                            <td>{{ $result->nik }}</td>
                            <td style="text-align : left !important">{{ $result->employeeName }}</td>
                            <td>{{ $result->sector }}</td>
                            <td>{{ $result->approvalName }}</td>
                            <td>{{ $result->isHold }}</td>
                            <td>
                                @if ($result->approval<>0)
                                <a class="btn btn-success" href="/schedule/approval/{{ $result->id }}/1">Approve</a>
                                <a class="btn btn-danger" href="/schedule/approval/{{ $result->id }}/2">Suspend</a>
                                @else 
                                No Further Action yet
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection