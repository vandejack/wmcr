@extends('layout')

@section('css')
<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('title', 'Preview Data IHLD')
@section('content')
<div class="card shadow-sm">
	<div class="card-body pb-4">
        <div class="table-responsive">
            <table id="kt_datatable_column_rendering" class="table table-striped table-row-bordered gy-5 gs-7">
                <thead>
                    <tr class="fw-semibold fs-6 text-gray-800">
                        <th>NO WO</th>
                        <th>NO INET</th>
                        <th>LATITUDE CUSTOMER</th>
                        <th>LONGITUDE CUSTOMER</th>
                        <th>ODP NAME</th>
                        <th>LATITUDE ODP</th>
                        <th>LONGITUDE ODP</th>
                        <th>DROPCORE</th>
                        <th>NEW LATITUDE</th>
                        <th>NEW LONGITUDE</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($data as $r)
                    <tr>
                        @for ($x=0;$x<count($r);$x++)
                        <td>{{ $r[$x] }}</td>
                        @endfor
                    </tr>
                    @endforeach --}}
                    @foreach ($newData as $r)
                    <tr>
                        <td>{{ $r['WO'] }}</td>
                        <td>{{ $r['NO_INET'] }}</td>
                        <td>{{ $r['LATITUDE_CUSTOMER'] }}</td>
                        <td>{{ $r['LONGITUDE_CUSTOMER'] }}</td>
                        <td>{{ $r['ODP_NAME'] }}</td>
                        <td>{{ $r['LATITUDE_ODP'] }}</td>
                        <td>{{ $r['LONGITUDE_ODP'] }}</td>
                        <td>{{ $r['DROPCORE'] }}</td>
                        <td>{{ $r['NEW_LATITUDE'] }}</td>
                        <td>{{ $r['NEW_LONGITUDE'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
        $(".table").DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'excel']
		});
    });
</script>
@endsection