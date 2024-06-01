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

@section('title', 'Home')

@section('content')
<div class="card shadow-sm">
	<div class="card-body pb-4">
        <div class="table-responsive">
            <table class="table table-hover table-row-bordered gy-5 border rounded w-100" id="ttr_hvc">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800 px-7 text-center">
                        <th rowspan="2">WITEL</th>
                        <th colspan="4" class="center">TICKETS RUNNING (hours)</th>
                        <th rowspan="2">TOTAL</th>
                        <th colspan="5" class="center">TODAY CLOSING</th>                        
                    </tr>
                    <tr>
                        <th>0</th>
                        <th>1</th>
                        <th>2</th>
                        <th>>3</th>
                        <th>COMPLY</th>
                        <th>NOT COMPLY</th>
                        <th>COMPLY (%)</th>
                        <th>NOT COMPLY (%)</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td>
                            <b>TOTAL</b>
                        </td>
                        <td>
                            <b>-</b>
                        </td>
                        <td>
                            <b>-</b>
                        </td>
                        <td>
                            <b>-</b>
                        </td>
                        <td>
                            <b>-</b>
                        </td>
                        <td>
                            <b>-</b>
                        </td>
                        <td>
                            <b>-</b>
                        </td>
                        <td>
                            <b>-</b>
                        </td>
                        <td>
                            <b>-</b>
                        </td>
                        <td>
                            <b>-</b>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
	$(document).ready(function () {

        $('#ttr_hvc').DataTable({
			lengthChange: false,
			searching: false,
			paging: false,
			info: false,
			autoWidth: true,
			ordering: false,
			ajax: `/ajax/dashboard/ttr-hvc`,
			'footerCallback': function( tfoot, data, start, end, display )
			{
				var response = this.api().ajax.json();
				if (response)
				{
					var $td = $(tfoot).find('td');
					$.each(response.footer, function(k, v)
					{
						var kk = k + 1;
						$td.eq(kk).html(v);
					})
				}
			}
      	});
    });
</script>
@endsection