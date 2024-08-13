@extends('layout')

@section('css')
@endsection

@section('title', 'Home')
@include('partial.alerts')
@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">{{ session('auth')->name }}</h3>
        <div class="card-toolbar">
          
        </div>
    </div>
    <div class="card-body">
        @if ($getSchedule)
            @if ($getSchedule->approval==0)
            <a href="/tech/requestApproval" class="btn btn-sm btn-success">
                Laporkan Kehadiran
            </a>
            @endif
            @if ($getSchedule->approval==3)
            Menunggu Proses Approval
            @endif
            @if ($getSchedule->approval==1)
            Selamat Bekerja !
            @endif
            @if ($getSchedule->approval==2)
            User Anda di Suspend oleh Team Leader. 
            @endif
            
        @else
        Anda tidak dijadwalkan masuk hari ini.
        @endif
    </div>
    <div class="card-footer">
       
    </div>
</div>
@endsection