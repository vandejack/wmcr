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

@section('title', 'Sector List')

@section('content')
<div class="card shadow-sm">
	<div class="card-body pb-4">
        <div class="table-responsive">
            <table class="table table-hover table-row-bordered gy-5 border rounded w-100">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800 px-7 text-center">
                        <th>#</th>
                        <th>Name</th>
                        <th>Group Chat ID</th>
                        <th>Owner 1</th>
                        <th>Owner 2</th>
                        <th>Rayon Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
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
			"pagingType": "full_numbers",
            "ordering": false,
            "ajax": {
				"url": `/ajax/sector/list`
			}
		});
    });
</script>
@endsection