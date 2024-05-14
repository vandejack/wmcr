@extends('layout')

@section('css')

@endsection

@section('title', 'List')

@section('content')
<style>
    .label-danger {
        background : #FF0000;
        padding : 3px;
        color : #FFF;
    }
</style>
<div class="card shadow-sm">
	<div class="card-body pb-4">
        <div class="table-responsive">
            <table class="table table-hover table-row-bordered gy-5 border rounded w-100">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800 px-7 text-center">
                        <th>#</th>
                        <th>Witel</th>
                        <th>Workzone</th>
                        {{-- <th>Tech</th> --}}
                        <th>Ticket</th>
                        <th>Status</th>
                        <th>Reported Date</th>
                        <th>Tgt Close</th>
                        <th>Timeleft</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $n => $r)
                    <tr class="fs-6 text-gray-800 px-7 text-center">
                        <td>{{ ++$n }}</td>
                        <td>{{ $r->witel}}</td>
                        <td>{{ $r->workzone}}</td>
                        {{-- <td>{{ $r->technician}}</td> --}}
                        <td>{{ $r->incident}}</td>
                        <td>{{ $r->status}}</td>
                        <td>{{ $r->reported_date}}</td>
                        <td>
                            <?php 
                            $new_time = date("Y-m-d H:i:s", strtotime('+3 hours', strtotime($r->reported_date)));
                            $datenow = date('Y-m-d H:i:s'); 
                            echo $new_time;
                            ?>
                        </td>
                        <td>
                            <?php
                            $selisih = strtotime($new_time) - strtotime($datenow);
                            $hari = floor(($selisih / (60 * 60 * 24))*24); // Menghitung selisih dalam hari
                            $jam = floor(($selisih % (60 * 60 * 24)) / (60 * 60)); // Menghitung selisih dalam jam
                            $menit = floor(($selisih % (60 * 60)) / 60); // Menghitung selisih dalam menit
                            $jam_new = $hari+$jam;
                            if ($jam_new<0) {
                                echo '<span class="label label-danger">LEWAT_TTR_3</span>';
                            } else {
                                echo $jam_new." jam ".$menit." menit"; 
                            }
                            
                            ?>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('footer')
@endsection