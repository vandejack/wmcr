@extends('layout')

@section('css')

@endsection

@section('title', 'Home')
@include('partial.alerts')
@section('content')

<div class="row">
    <div class="col-lg-6">
        <!--begin::Card-->
        <div class="card card-flush h-lg-100" data-select2-id="select2-data-149-6q8a">
            <!--begin::Card header-->
            <div class="card-header mt-6">
                <!--begin::Card title-->
                <div class="card-title flex-column">
                    <h3 class="fw-bold mb-1">What's your next Task ?</h3>
                    <div class="fs-6 text-gray-500">Today Orders : {{ count($getTechOrder[date('d')]) }} </div>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                {{-- <div class="card-toolbar">
                    <!--begin::Select-->
                    <select name="status" data-control="select2" data-hide-search="true" class="form-select form-select-solid form-select-sm fw-bold w-100px select2-hidden-accessible" data-select2-id="select2-data-12-4f08" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                        <option value="1" selected="selected" data-select2-id="select2-data-14-zhqy">Options</option>
                        <option value="2" data-select2-id="select2-data-154-v3ph">Option 1</option>
                        <option value="3" data-select2-id="select2-data-155-xh6r">Option 2</option>
                        <option value="4" data-select2-id="select2-data-156-1m4g">Option 3</option>
                    </select><span class="select2 select2-container select2-container--bootstrap5 select2-container--below" dir="ltr" data-select2-id="select2-data-13-jn9y" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single form-select form-select-solid form-select-sm fw-bold w-100px" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-status-lg-container" aria-controls="select2-status-lg-container"><span class="select2-selection__rendered" id="select2-status-lg-container" role="textbox" aria-readonly="true" title="Option 1">Option 1</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                    <!--end::Select-->
                </div> --}}
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body p-9 pt-4">
                <!--begin::Dates-->
                <ul class="nav nav-pills d-flex flex-nowrap hover-scroll-x py-2" role="tablist">
                    @for ($x=-1;$x<=$viewDay;$x++)
                    <?php
                    $date = date('d', strtotime($defaultPeriode . "$x days"));
                    ?>
                    <!--begin::Date-->
                    <li class="nav-item me-1" role="presentation">
                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-active-primary <?php if(date('d', strtotime($defaultPeriode . "$x days"))==date('d')) { echo 'active'; } ?>" data-bs-toggle="tab" href="#kt_schedule_day_{{ $date }}" aria-selected="false" tabindex="-1" role="tab">
                            <span class="opacity-50 fs-7 fw-semibold">{{ substr(date('D', strtotime($defaultPeriode . "$x days")),0,2) }}</span>
                            <span class="fs-6 fw-bold">{{ date('d', strtotime($defaultPeriode . "$x days")) }}</span>
                        </a>
                    </li>
                    <!--end::Date-->
                    @endfor
                    
                </ul>
                <!--end::Dates-->
                <!--begin::Tab Content-->
                <div class="tab-content">
                    @for ($x=-1;$x<=$viewDay;$x++)
                    <?php
                    $date = date('d', strtotime($defaultPeriode . "$x days"));
                    ?>
                    <!--begin::Day-->
                    <div id="kt_schedule_day_{{ $date }}" class="tab-pane fade show <?php if($date==date('d')){ echo "active"; }?>" role="tabpanel">
                        @foreach ($getTechOrder[$date] as $r)
                        <!--begin::Time-->
                        <div class="d-flex flex-stack position-relative mt-8">
                            <!--begin::Bar-->
                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                            <!--end::Bar-->
                            <!--begin::Info-->
                            <div class="fw-semibold ms-5 text-gray-600">
                                <!--begin::Time-->
                                <div class="fs-5">{{ $r->timeStart }} - {{ $r->timeEnd }} 
                                <span class="fs-7 text-gray-500 text-uppercase">{{ $r->statusName }}</span></div>
                                <!--end::Time-->
                                <!--begin::Title-->
                                <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">{{ $r->order_id }} </a>
                                <!--end::Title-->
                                <!--begin::User-->
                                <div class="text-gray-500">Dispatch by 
                                <a href="#">{{ $r->created_by }}</a></div>
                                <!--end::User-->
                            </div>
                            <!--end::Info-->
                            <!--begin::Action-->
                            <a href="/tech/orderview/{{ $r->dispatchID }}" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                            <!--end::Action-->
                        </div>
                        <!--end::Time-->
                        @endforeach
                        
                    </div>
                    <!--end::Day-->
                    @endfor
                </div>
                <!--end::Tab Content-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
   
</div>

@endsection