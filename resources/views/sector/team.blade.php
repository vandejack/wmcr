@extends('layout')

@section('css')
<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<style>
    tr, th, td {
		vertical-align: middle;
        text-align: center;
    }
    .dataTables_wrapper table.dataTable tbody td,
    .dataTables_wrapper table.dataTable tbody th {
        padding: 2px 1px;
        border: 0.1px solid #f3f3f3;
    }

    .dataTables_wrapper table.dataTable thead td,
    .dataTables_wrapper table.dataTable thead th {
        padding: 2px 1px;
        border: 0.1px solid #f3f3f3;
    }

    .dataTables_wrapper table.dataTable tfoot td,
    .dataTables_wrapper table.dataTable tfoot th {
        padding: 2px 1px;
        border: 0.1px solid #f3f3f3;
    }
</style>
@endsection

@section('title', 'Team Data')

@section('content')
<div class="card shadow-sm">
	<div class="card-body pb-0">
        <div class="table-responsive">
            <table class="table table-hover table-row-bordered gy-5 gs-7 border rounded w-100">
                <thead class="table-light">
                    <tr class="fw-bold fs-6 text-muted text-center">
                        <th style="width: 5%">#</th>
                        <th>Sector</th>
                        <th>Team</th>
                        <th>Technician 1</th>
                        <th>Technician 2</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $k => $v)
                    <tr class="text-center">
                        <td>{{ ++$k }}</td>
                        <td>{{ $v->sector_name }}</td>
                        <td>{{ $v->name }}</td>
                        <td>{{ $v->technician1_name }} ({{ $v->technician1 }})</td>
                        <td>{{ $v->technician2_name }} ({{ $v->technician2 }})</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
        $(".table").DataTable({
			"language": {
				"lengthMenu": "Show _MENU_",
			},
			"dom":
					"<'row'" +
					"<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
					"<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
					">" +
					"<'table-responsive'tr>" +
					"<'row'" +
					"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
					"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
					">",
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"pageLength": 10,
			"pagingType": "full_numbers"
		});
    });
</script>
@endsection