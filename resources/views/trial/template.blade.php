@extends('layout')

@section('css')
<style>
    .leaflet-layer,
.leaflet-control-zoom-in,
.leaflet-control-zoom-out,
.leaflet-control-attribution {
  filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
}
.productivity_table th {
        padding : 10px !important;
        background-color: #070707;
        color : #ffffff;
        border: 1px solid rgb(148, 148, 148);
        text-align: center;
        vertical-align: middle;
        border-collapse: collapse;
    }
    </style>
@endsection

@section('title', 'Workforce Management')

@section('filter')
@endsection

@section('content')
<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_4">Link 1</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">Link 2</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_6">Link 3</a>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="kt_tab_pane_4" role="tabpanel">
        A
    </div>
    <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
        B
    </div>
    <div class="tab-pane fade" id="kt_tab_pane_6" role="tabpanel">
        C
    </div>
</div>
@endsection