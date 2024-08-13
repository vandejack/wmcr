@extends('layout')

@section('css')
@endsection

@section('title', 'Home')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">{{ session('auth')->name }}</h3>
        <div class="card-toolbar">
            <button type="button" class="btn btn-sm btn-light">
                ...
            </button>
        </div>
    </div>
    <div class="card-body">
        Welcome to WMCR
    </div>
    <div class="card-footer">
       
    </div>
</div>
@endsection