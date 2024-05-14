@extends('layout')

@section('css')

@endsection

@section('title', 'Tickets Monitoring')

@section('content')
<style>
    .tableMy td,th {
        padding : 4px;
        border :1px;
        text-align: center;
    }
    .red {
        background-color: red;
        color : #FFF;
    } 
    .center {
        text-align: center;
    }
</style>
<div class="card shadow-sm">
	<div class="card-body pb-4">
        <h2>TTR HVC (Gold, Platinum & Diamond)</h2>
        <table class="tableMy" border=1 width="100%">
            <tr>
                <th rowspan="2">WITEL</th>
                <th colspan="4" class="center">TICKETS RUNNING (hours)</th>
                <th rowspan="2">TOTAL</th>
                <th colspan="5" class="center">TODAY CLOSING</th>
                <th rowspan="2">TOTAL</th>
                
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
            <?php 
                $totalnol1jam = 0;
                $totalsatu2jam = 0;
                $totaldua3jam = 0;
                $totallebih3jam = 0;
                $total = 0;
            ?>
            @foreach ($witel as $r)
            <tr>
                <td>{{ $r->name }}</td>
                <td>{{ $dataWitel[$r->name]['nol1jam'] ?: 0 }}</td>
                <td>{{ $dataWitel[$r->name]['satu2jam'] ?: 0 }}</td>
                <td>{{ $dataWitel[$r->name]['dua3jam'] ?: 0 }}</td>
                <td class="red">{{ $dataWitel[$r->name]['lebih3jam'] ?: 0 }}</td>
                <td>{{ $dataWitel[$r->name]['total'] ?: 0 }}</td>
            </tr>
            <?php 
                $totalnol1jam = $totalnol1jam + $dataWitel[$r->name]['nol1jam'];
                $totalsatu2jam = $totalsatu2jam + $dataWitel[$r->name]['satu2jam'];
                $totaldua3jam = $totaldua3jam + $dataWitel[$r->name]['dua3jam'];
                $totallebih3jam = $totallebih3jam + $dataWitel[$r->name]['lebih3jam'];
                $total = $total + $dataWitel[$r->name]['total'];
            ?>
            @endforeach
            <tr>
                <th>REGIONAL</th>
                <th>{{ $totalnol1jam }}</th>
                <th>{{ $totalsatu2jam }}</th>
                <th>{{ $totaldua3jam }}</th>
                <th>{{ $totallebih3jam }}</th>
                <th>{{ $total }}</th>
            </tr>
        </table>
    </div>
</div>
@endsection

@section('footer')
@endsection