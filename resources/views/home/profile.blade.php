@extends('layout')

@section('css')

@endsection

@section('title', 'Profile')

@section('content')
<div class="card shadow-sm">
	<div class="card-body pb-0">
        <p>{{ json_encode(session('auth')) }}</p>
    </div>
</div>
@endsection

@section('footer')
@endsection