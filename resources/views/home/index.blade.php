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
        <h3>TTR HVC (Gold , Platinum & Diamond)</h3>
        <div class="table-responsive">
            <table class="table table-hover table-row-bordered gy-5 border rounded w-100" id="ttr_hvc">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800 px-7 text-center">
                        <th rowspan="2" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">WITEL</th>
                        <th colspan="4" class="center" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TICKETS RUNNING (hours)</th>
                        <th rowspan="2" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TOTAL</th>
                        <th colspan="5" class="center" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TODAY CLOSING</th>                        
                    </tr>
                    <tr>
                        <th style="font-weight: bold; background-color: rgb(131, 149, 167); color: white">0</th>
                        <th style="font-weight: bold; background-color: rgb(29, 209, 161); color: white">1</th>
                        <th style="font-weight: bold; background-color: rgb(254, 202, 87); color: white">2</th>
                        <th style="font-weight: bold; background-color: rgb(241, 65, 108); color: white">>3</th>
                        <th style="font-weight: bold; background-color: rgb(29, 209, 161); color: white">COMPLY</th>
                        <th style="font-weight: bold; background-color: rgb(241, 65, 108); color: white">NOT COMPLY</th>
                        <th style="font-weight: bold; background-color: rgb(29, 209, 161); color: white">COMPLY (%)</th>
                        <th style="font-weight: bold; background-color: rgb(241, 65, 108); color: white">NOT COMPLY (%)</th>
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

<br />

<div class="card shadow-sm">
	<div class="card-body pb-4">
        <h3>PRODUKTIFITAS ORDER</h3>
        <div class="table-responsive">
            <table class="table table-hover table-row-bordered gy-5 border rounded w-100" id="productivity_order">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800 px-7 text-center">
                        <th rowspan="3" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">WITEL</th>
                        <th colspan="10" class="center" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">PROVISIONING</th>
                        <th colspan="16" class="center" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">ASSURANCE</th>                        
                    </tr>
                    <tr>
                        <th colspan="5" class="center" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TSEL</th>
                        <th colspan="5" class="center" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TLKM</th>
                        <th colspan="7" class="center" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">B2C</th>
                        <th colspan="9" class="center" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">B2B</th>
                    </tr>
                    <tr>
                        @for ($i = 0; $i < 2; $i ++)
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">AO</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">ORBIT</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">MO</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">PDA</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TTL</th>
                        @endfor

                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">VVIP</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">DMND</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">PLAT</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">GOLD</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">REG</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">PRO</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TTL</th>

                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">DES</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">DBS</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">DGS</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">DPS</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">DSS</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">REG</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">DWS</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TAW</th>
                        <th style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TTL</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td>
                            <b>TOTAL</b>
                        </td>
                        @for ($i = 0; $i < 2; $i ++)
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
                        @endfor
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

<br />

<div class="card shadow-sm">
	<div class="card-body pb-4">
        <h3>DASHBOARD PRODUKTIFITAS PROVISIONING PERIODE {{ $start_date }} S/D {{ $end_date }}</h3>
        <div class="table-responsive">
            <table class="table table-hover table-row-bordered gy-5 border rounded w-100" id="productivity_provisioning">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800 px-7 text-center">
                        <th rowspan="2" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">SEKTOR</th>                  
                        <th rowspan="2" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">TEKNISI</th>                  
                        <th colspan="5" style="font-weight: bold; background-color: rgb(254, 202, 87); color: white">ORDER</th>
                        <th colspan="5" style="font-weight: bold; background-color: rgb(29, 209, 161); color: white">PS</th>
                        <th colspan="3" style="font-weight: bold; background-color: rgb(241, 65, 108); color: white">KENDALA</th>
                        <th rowspan="2" style="font-weight: bold; background-color: rgb(10, 189, 227); color: white">POINT+</th>
                    </tr>
                    <tr>
                        <th style="font-weight: bold; background-color: rgb(254, 202, 87); color: white">AO</th>
                        <th style="font-weight: bold; background-color: rgb(254, 202, 87); color: white">ORBIT</th>
                        <th style="font-weight: bold; background-color: rgb(254, 202, 87); color: white">MO</th>
                        <th style="font-weight: bold; background-color: rgb(254, 202, 87); color: white">PDA</th>
                        <th style="font-weight: bold; background-color: rgb(254, 202, 87); color: white">TTL</th>

                        <th style="font-weight: bold; background-color: rgb(29, 209, 161); color: white">AO</th>
                        <th style="font-weight: bold; background-color: rgb(29, 209, 161); color: white">ORBIT</th>
                        <th style="font-weight: bold; background-color: rgb(29, 209, 161); color: white">MO</th>
                        <th style="font-weight: bold; background-color: rgb(29, 209, 161); color: white">PDA</th>
                        <th style="font-weight: bold; background-color: rgb(29, 209, 161); color: white">TTL</th>

                        <th style="font-weight: bold; background-color: rgb(241, 65, 108); color: white">PELANGGAN</th>
                        <th style="font-weight: bold; background-color: rgb(241, 65, 108); color: white">TEKHNIK</th>
                        <th style="font-weight: bold; background-color: rgb(241, 65, 108); color: white">TTL</th>
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
                        @for ($i = 0; $i < 2; $i ++)
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
                        @endfor
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

        $('#productivity_order').DataTable({
			lengthChange: false,
			searching: false,
			paging: false,
			info: false,
			autoWidth: true,
			ordering: false,
			ajax: `/ajax/dashboard/productivity-order`,
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

        $('#productivity_provisioning').DataTable({
            lengthChange: false,
			searching: false,
			paging: false,
			info: false,
			autoWidth: true,
			ordering: false,
			ajax: `/ajax/dashboard/productivity-provisioning`,
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