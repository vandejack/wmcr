@extends('layout')


@section('title', 'Preview Import ODP')
@section('content')
<div class="card shadow-sm">
	<div class="card-body pb-4">
        <div class="table-responsive">
            <table id="kt_datatable_column_rendering" class="table table-striped table-row-bordered gy-5 gs-7">
                <thead>
                    <tr class="fw-semibold fs-6 text-gray-800">
                        @for ($x=1;$x<count($columns);$x++)
                        <th>{{ $columns[$x] }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $r)
                    <tr>
                        @for ($x=1;$x<count($r);$x++)
                        <td>{{ $r[$x] }}</td>
                        @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
var status = {
    1: {"title": "Pending", "state": "primary"},
    2: {"title": "Delivered", "state": "danger"},
    3: {"title": "Canceled", "state": "primary"},
    4: {"title": "Success", "state": "success"},
    5: {"title": "Info", "state": "info"},
    6: {"title": "Danger", "state": "danger"},
    7: {"title": "Warning", "state": "warning"},
};

$("#kt_datatable_fixed_header").DataTable({
    "fixedHeader": {
        "header":true
    },
    "columnDefs": [
        {
            // The `data` parameter refers to the data for the cell (defined by the
            // `data` option, which defaults to the column being worked with, in
            // this case `data: 0`.
            "render": function ( data, type, row ) {
                var index = KTUtil.getRandomInt(1, 7);

                return data + '<span class="ms-2 badge badge-light-' + status[index]['state'] + ' fw-semibold">' + status[index]['title'] + '</span>';
            },
            "targets": 1
        }
    ]
});
</script>
@endsection