@extends('layout')

@section('css')

@endsection

@section('title', 'Search Order')

@section('content')
<form method="POST">
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-2">
                    <select class="form-select form-select-solid" data-control="select2" data-allow-clear="true" data-placeholder="Select an option" name="type" required="required">
                        <option></option>
                        <option value="Provisioning" {{ $type == 'Provisioning' ? 'selected' : '' }}>Provisioning</option>
                        <option value="Migration" {{ $type == 'Migration' ? 'selected' : '' }}>Migration</option>
                        <option value="Assurance" {{ $type == 'Assurance' ? 'selected' : '' }}>Assurance</option>
                        <option value="Maintenance" {{ $type == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-8">
                    <input class="form-control" placeholder="Masukan Order ID atau Nomor Internet" type="search" name="search" maxlength="20" required="required" value="{{ $id }}"/>
                </div>
                <div class="col-sm-12 col-md-2 d-grid gap-2">
                    <button type="submit" class="btn btn-sm btn-primary btn-hover-scale me-5 btn-rounded">
                        <i class="fa fa-search" aria-hidden="true"></i>&nbsp; Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@if (count($data) > 0)
<div class="card shadow-sm mb-5">
    <div class="card-body">
        <div class="row">
            <pre>{{ print_r($data) }}</pre>
        </div>
    </div>
</div>
@else
<div class="card shadow-sm mb-5">
    <div class="card-body">
        <div class="row">
            <pre>Data not found!</pre>
        </div>
    </div>
</div>
@endif

@endsection

@section('footer')
@endsection