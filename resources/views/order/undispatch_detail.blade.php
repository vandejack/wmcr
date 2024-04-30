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

@section('title', 'Undispatch Detail')

@section('content')

<div class="modal fade" id="dispatch_data" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="title_modal">Order Dispatch - </h4>

				<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
					<span class="svg-icon svg-icon-1">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
							<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
						</svg>
					</span>
				</div>
			</div>
			<form id="form_level" method="post" action="/order/dispatch-save">
			{{ csrf_field() }}
			<input type="hidden" name="order_code">
			<input type="hidden" name="order_type_id">
			
			<div class="modal-body">
				<div class="form-group mb-8">
					<div class="row">
						<label class="col-sm-12 col-md-2 col-form-label">Order Code</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" id="order_code" type="text" disabled>
						</div>
					</div>
				</div>
				<div class="form-group mb-8">
					<div class="row">
						<label class="col-sm-12 col-md-2 col-form-label">Sector</label>
						<div class="col-sm-12 col-md-10">
							<select class="form-control form-select form-select-solid id_sector" data-dropdown-parent="#dispatch_data" data-control="select2" name="id_sector" style="width: 100%;" required></select>
						</div>
					</div>
				</div>
				<div class="form-group mb-8">
					<div class="row">
						<label class="col-sm-12 col-md-2 col-form-label">Team</label>
						<div class="col-sm-12 col-md-10">
							<select class="form-control form-select form-select-solid id_team" data-dropdown-parent="#dispatch_data" data-control="select2" name="id_team" style="width: 100%;" required></select>
						</div>
					</div>
				</div>
				<div class="form-group mb-8">
					<div class="row">
						<label class="col-sm-12 col-md-2 col-form-label">Dispatch Date</label>
						<div class="col-sm-12 col-md-10">
							<div class="position-relative d-flex align-items-center">
								<span class="svg-icon svg-icon-2 position-absolute mx-4">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
										<path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="black" />
										<path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="black" />
										<path d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z" fill="black" />
									</svg>
								</span>
								<input class="form-control form-control-solid ps-12 dispatch-date" name="dispatch_date" placeholder="Select Dispatch Date" value="" readonly required />
							</div>
						</div>
					</div>
				</div>
				<div class="form-group mb-8">
					<div class="row">
						<label class="col-sm-12 col-md-2 col-form-label">Tag</label>
						<div class="col-sm-12 col-md-10">
							<select class="form-control form-select form-select-sm form-select-solid order_tag" data-control="select2" name="order_tag[]" data-size="7" multiple="multiple">
								@foreach ($order_tag as $ot)
								<option value="{{ $ot->id }}">{{ $ot->text }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Dispatch</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="card shadow-sm">
	<div class="card-body pb-4">
        <div class="table-responsive">
            <table class="table table-hover table-row-bordered gy-5 border rounded w-100">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800 px-7 text-center">
                        <th>#</th>
                        <th>Action</th>
                        @if ($order == 'order_survey')
                        <th>Order Code</th> 
                        <th>Customer Desc</th>
                        <th>Order Created Date</th>
                        <th>Customer Name</th>
                        <th>Customer Phone</th>
                        <th>STO</th>
                        <th>Witel</th>
                        <th>MYIR</th>
                        <th>Order Type Name</th>
                        <th>Order Status Name</th>
                        @elseif (in_array($order, ['order_ao', 'order_mo', 'order_pda']))
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Order Date PS</th>
                        <th>NCLI</th>
                        <th>Customer Name</th>
                        <th>Witel</th>
                        <th>Jenis PSB</th>
                        <th>STO</th>
                        <th>No Internet</th>
                        <th>No Voice</th>
                        <th>Package Name</th>
                        <th>Status Resume</th>
                        <th>Customer Address</th>
                        <th>K-Contact</th>
                        <th>Install Address</th>
                        <th>GPS Latitude</th>
                        <th>GPS Longitude</th>
                        <th>Location ID</th>
                        @elseif (in_array($order, ['order_b2c', 'order_b2b', 'order_proactive', 'order_assurance']))
                        <th>Incident</th>
                        <th>TTR Customer</th>
                        <th>Summary</th>
                        <th>Reported Date</th>
                        <th>Owner Group</th>
                        <th>Customer Segment</th>
                        <th>Service Type</th>
                        <th>Witel</th>
                        <th>Workzone</th>
                        <th>Status</th>
                        <th>Status Date</th>
                        <th>Ticket ID Gamas</th>
                        <th>Contact Phone</th>
                        <th>Contact Name</th>
                        <th>Source Ticket</th>
                        <th>Customer Type</th>
                        <th>Customer Name</th>
                        <th>Service ID</th>
                        <th>Service No</th>
                        <th>Device Name</th>
                        <th>Guarante Status</th>
                        <th>Resolve Date</th>
                        @elseif (in_array($order, ['order_non_warranty', 'order_warranty', 'order_maintenance']))
                        <th>Reg</th>
                        <th>Witel</th>
                        <th>Sektor</th>
                        <th>Node ID</th>
                        <th>Shelf|Slot|Port|OnuID</th>
                        <th>Fiber Length</th>
                        <th>CMDF</th>
                        <th>RK</th>
                        <th>DP</th>
                        <th>ND</th>
                        <th>Tanggal PS</th>
                        <th>Status Inet</th>
                        <th>Onu Rx Power</th>
                        <th>Tanggal Ukur</th>
                        <th>Onu Rx Power Ukur Ulang</th>
                        <th>Tanggal Ukur Ulang</th>
                        <th>Nomor Tiket</th>
                        <th>Status Tiket</th>
                        <th>Flag HVC</th>
                        <th>Type Pelanggan</th>
                        <th>Prioritas</th>
                        <th>Jenis</th>
                        <th>Tanggal Order</th>
                        @endif
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
        var area = {!! json_encode($area) !!},
        order = {!! json_encode($order) !!},
        start_date = {!! json_encode($start_date) !!},
        end_date = {!! json_encode($end_date) !!};

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
            "ordering": true,
            "ajax": {
				"url": `/ajax/order/undispatch-detail?area=${area}&order=${order}&start_date=${start_date}&end_date=${end_date}`
			}
		});

        $('.dispatch-date').flatpickr({ enableTime: false, dateFormat: "Y-m-d" });

        $('.sector_id').select2({
            allowClear: true,
            placeholder: 'Pilih Sektor',
            ajax: {
                url: "/ajax/select2/sector/0",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true,
            }
        });

        $(document).on('click', '.dispatch_modal', function() {
            const id_data = $(this).data('id_data'),
            order_data = $(this).data('order_data'),
			order_type_id = $(this).data('order_type_id');

            $.ajax({
				type: 'GET',
				url: `/ajax/order/undispatch-search/${order_data}/${id_data}`,
				success: function (data) {
                    var result = data.data;                    
					
					$(".title_modal").html(`Order Dispatch - ${id_data}`);
                    $("input[name='order_code']").val(id_data);
                    $("input[name='order_type_id']").val(order_type_id);
                    $("#order_code").val(id_data);
				}
			});
        })
    });
</script>
@endsection