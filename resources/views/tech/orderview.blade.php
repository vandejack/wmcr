@extends('layout')

@section('css')
<style>
    .image-input-placeholder {
        background-image: url('/images/empty.png');
    }

    [data-bs-theme="dark"] .image-input-placeholder {
        background-image: url('/images/empty.png');
    }
    #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            display: block;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        /* Style for the progress bar container */
        #progress-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            opacity: 0;
            background-color: #353535;
            border-radius: 5px;
            padding: 3px;
            z-index: 1000;
            visibility: hidden;
            display: block;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        /* Style for the progress bar */
        #progress-bar {
            width: 0;
            height: 30px;
            background-color: #4caf50;
            text-align: center;
            line-height: 30px;
            color: white;
            border-radius: 5px;
        }
        .activex {
            opacity: 1 !important;
            visibility: visible !important;
        }
</style>
@endsection

@include('partial.alerts')
@section('title', 'Home')
 
@section('content')
<div class="row" style="padding:10px;margin-top:-30px">
    <form id="kt_form_reporting" class="form" action="#" method="post" autocomplete="off" enctype="multipart/form-data">
    <input type="hidden" name="order_id" value="{{ $order->order_id }}" />
    <input type="hidden" name="dispatch_id" value="{{ $dispatchID }}">
    <div class="content flex-row-fluid" id="kt_content" >
        <!--begin::Order details page-->
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
                <!--begin:::Tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto" role="tablist">
                    <!--begin:::Tab item-->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_sales_order_summary" aria-selected="true" role="tab">Order Summary</a>
                    </li>
                    <!--end:::Tab item-->
                    <!--begin:::Tab item-->
                    @if ($order->status<>0)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_sales_order_history" aria-selected="false" tabindex="-1" role="tab">Reporting</a>
                    </li>
                    @endif
                    <!--end:::Tab item-->
                </ul>
                <!--end:::Tabs-->
                <!--begin::Button-->
                <a href="{{ $backUrl }}" class="btn btn-icon btn-light btn-active-secondary btn-sm ms-auto me-lg-n7">
                    <i class="ki-duotone ki-left fs-2"></i>
                </a>
                <!--end::Button-->
                <!--begin::Button-->
                @if ($order->status==0)
                <a href="/tech/startProgress/{{ $dispatchID }}" class="btn btn-success btn-sm">START PROGRESS</a>
                @else 
                <div id="overlay"></div>
                <div id="progress-container">
                    Uploading : <div id="progress-bar">0%</div>
                </div>
                <button class="btn btn-success btn-sm" id="kt_docs_formvalidation_text_submit">
                    <span class="indicator-label">
                        Save
                    </span>
                    <span class="indicator-progress">
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>                
                @endif
                <!--end::Button-->
            </div>
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                                <!--begin::Orders-->
            <!--begin::Order summary-->
            <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                <!--begin::Order details-->
                <div class="card card-flush py-4 flex-row-fluid">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Order Details (#{{ $order->order_id }})</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                <tbody class="fw-semibold text-gray-600">
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-calendar fs-2 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>Assigned Date</div>
                                        </td>
                                        <td class="fw-bold text-end">{{ $order->assigned_date }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-wallet fs-2 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                            </i>Time Schedule</div>
                                        </td>
                                        <td class="fw-bold text-end">{{ $order->timeStart }} - {{ $order->timeEnd }} </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-truck fs-2 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>Order Type</div>
                                        </td>
                                        <td class="fw-bold text-end">{{ $order->orderType }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Order details-->
                <!--begin::Customer details-->
                <div class="card card-flush py-4 flex-row-fluid">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Customer Details</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                <tbody class="fw-semibold text-gray-600">
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-profile-circle fs-2 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>Customer</div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <!--begin::Name-->
                                                <a href="#" class="text-gray-600 text-hover-primary">{{ $orderDetail->customer_name }}</a>
                                                <!--end::Name-->
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-sms fs-2 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>Internet ID</div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            <a href="apps/user-management/users/view.html" class="text-gray-600 text-hover-primary">{{ $orderDetail->speedy }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-phone fs-2 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>Type Detail</div>
                                        </td>
                                        <td class="fw-bold text-end">{{ $orderDetail->jenis_psb }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Customer details-->
                <!--begin::Documents-->
                <div class="card card-flush py-4 flex-row-fluid">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Datek</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                <tbody class="fw-semibold text-gray-600">
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-devices fs-2 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>STO 
                                            <span class="ms-1" data-bs-toggle="tooltip" aria-label="View the invoice generated by this order." data-bs-original-title="View the invoice generated by this order." data-kt-initialized="1">
                                                <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </span></div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            <a href="apps/invoices/view/invoice-3.html" class="text-gray-600 text-hover-primary">{{ $orderDetail->sto }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-truck fs-2 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>ODP Reservasi 
                                            <span class="ms-1" data-bs-toggle="tooltip" aria-label="View the shipping manifest generated by this order." data-bs-original-title="View the shipping manifest generated by this order." data-kt-initialized="1">
                                                <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </span></div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            <a href="#" class="text-gray-600 text-hover-primary">{{ $orderDetail->loc_id }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-discount fs-2 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>Status Resume 
                                            <span class="ms-1" data-bs-toggle="tooltip" aria-label="Reward value earned by customer when purchasing this order" data-bs-original-title="Reward value earned by customer when purchasing this order" data-kt-initialized="1">
                                                <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </span></div>
                                        </td>
                                        <td class="fw-bold text-end">{{ $orderDetail->status_resume }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Documents-->
            </div>
            <!--end::Order summary-->

                    <div class="d-flex flex-column gap-7 gap-lg-10" style="margin-top:20px">
                        <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                            <!--begin::Payment address-->
                            <div class="card card-flush py-4 flex-row-fluid position-relative">
                                <!--begin::Background-->
                                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                    <i class="ki-solid ki-two-credit-cart" style="font-size: 14em"></i>
                                </div>
                                <!--end::Background-->
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Billing Address</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    {{ $orderDetail->ins_address }}<br />
                                    {{ $orderDetail->customer_addr }}<br />
                                    <a href="https://maps.google.com/?q={{ $orderDetail->gps_latitude }},{{ $orderDetail->gps_longitude }}">{{ $orderDetail->gps_latitude }},{{ $orderDetail->gps_longitude }}</a>
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Payment address-->
                            <!--begin::Shipping address-->
                            <div class="card card-flush py-4 flex-row-fluid position-relative">
                                <!--begin::Background-->
                                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                    <i class="ki-solid ki-delivery" style="font-size: 13em"></i>
                                </div>
                                <!--end::Background-->
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>KContact</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    {{ $orderDetail->kcontact }} 
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Shipping address-->
                        </div>
                        
                    </div>
                    <!--end::Orders-->
                </div>
                <!--end::Tab pane-->
                <!--begin::Tab pane-->
                <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">
                    
                    <!--begin::Orders-->
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <!--begin::Order history-->
                        <div class="card card-flush py-4" data-select2-id="select2-data-140-1d86">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Order Condition</h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <?php
                                        $statusColor = ($order->status === 1) ? 'bg-warning' : (($order->status === 5) ? 'bg-success' : 'bg-danger');
                                    ?>
                                    <div class="rounded-circle w-15px h-15px bg-primary {{ $statusColor }}" id="kt_ecommerce_add_product_status"></div>
                                </div>
                                <!--begin::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <div class="card-body pt-0" data-select2-id="select2-data-139-c6ib">
                                <div class="form-check form-switch form-check-custom form-check-solid fv-row">
                                    <input class="form-check-input" name="isHR" type="checkbox" value="1" <?php if ($order->isHR==1) { echo "checked"; }  ?>  id="flexSwitchDefault"/>
                                    <label class="form-check-label" for="flexSwitchDefault">
                                        Home Reach (HR)
                                    </label>
                                </div>
                            </div>
                            <!--begin::Card body-->
                            <div class="card-body pt-0 fv-row" data-select2-id="select2-data-139-c6ib">
                                <label class="required fw-semibold fs-6 mb-2">Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="">--</option>
                                    @foreach($getStatus as $r)
                                    <option value="{{ $r->id }}" <?= ($order->status == $r->id) ? 'selected' : '' ?>>{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 fv-row <?= ($order->status <> 2) ? 'd-none' : '' ?>" data-select2-id="select2-data-139-c6ib">
                                <label class="required fw-semibold fs-6 mb-2">Kendala Teknik</label>
                                <select class="form-select" name="kendala_teknik" id="kendala_teknik">
                                    <option value="">--</option>
                                    @foreach($getKendalaTeknik as $r)
                                    <option value="{{ $r->id }}" <?= ($order->statusSub == $r->id) ? 'selected' : '' ?>>{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 fv-row <?= ($order->status <> 3) ? 'd-none' : '' ?>" data-select2-id="select2-data-139-c6ib">
                                <label class="required fw-semibold fs-6 mb-2">Kendala Pelanggan</label>
                                <select class="form-select" name="kendala_pelanggan" id="kendala_pelanggan">
                                    <option value="">--</option>
                                    @foreach($getKendalaPelanggan as $r)
                                    <option value="{{ $r->id }}" <?= ($order->statusSub == $r->id) ? 'selected' : '' ?>>{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 fv-row <?= ($order->status <> 4) ? 'd-none' : '' ?>" data-select2-id="select2-data-139-c6ib">
                                <label class="required fw-semibold fs-6 mb-2">Kendala Pelanggan</label>
                                <select class="form-select" name="kendala_lainnya" id="kendala_lainnya">
                                    <option value="">--</option>
                                    @foreach($getKendalaLainnya as $r)
                                    <option value="{{ $r->id }}" <?= ($order->statusSub == $r->id) ? 'selected' : '' ?>>{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 fv-row" data-select2-id="select2-data-139-c6ib">
                                <label class="fw-semibold fs-6 mb-2">Memo</label>
                                <textarea class="form-control" name="engineMemo">{{ $order->engineMemo }}</textarea>
                            </div>
                            
                        </div>
                        
                        </div>

                        <div class="card card-flush py-4 my-4" data-select2-id="select2-data-140-1d86">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Datek</h2>
                                </div>
                            </div>
                                <div class="card-body pt-0 " data-select2-id="select2-data-139-c6ib">
                                    <div class="my-4 fv-row">
                                        <label class="required fw-semibold fs-6 mb-2">ODP Installation</label>
                                        <input type="text" class="form-control form-control-solid mb-3 mb-lg-0" name="odp_installation" id="odp_installation" placeholder="" value="{{ $order->odp_installation }}" />
                                    </div>
                                    <div class="my-4 fv-row">
                                        <label for="xx">Valins ID</label>
                                        <input type="text" name="valins_id" class="form-control" value="{{ $order->valins_id }}" />
                                    </div>
                                </div>
                            
                        </div>
                        <div class="card card-flush py-4 my-4" data-select2-id="select2-data-140-1d86">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Evidence & Customer Coordinates</h2>
                                </div>
                            </div>
                                <div class="card-body pt-0" data-select2-id="select2-data-139-c6ib">
                                    <div class="my-4">
                                        <h4>Photo Evidence</h4>
                                            
                                        <div class="table-responsive">
                                            <table>
                                                <tr>
                                                    <td>Rumah Pelanggan</td>
                                                    <td>ODP</td>
                                                    <td>Capture Evidence</td>
                                                </tr>
                                                <tr>
                                                    <td>
        
                                                        <?php
                                                        $relativePath = '/images/evidence/' . $dispatchID . '/Comp_Rumah_Pelanggan.jpg';
                                                        $absolutePath = public_path($relativePath);

                                                        // Check if the file exists
                                                        if (file_exists($absolutePath)) {
                                                            $image = $relativePath; // Use relative path for the view
                                                        } else {
                                                            $image = '/images/empty.png'; // Default image path
                                                        }
                                                        ?>
                                                        <!--begin::Image input-->
                                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ $image }})">
                                                            <!--begin::Image preview wrapper-->
                                                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $image }})"></div>
                                                            <!--end::Image preview wrapper-->

                                                            <!--begin::Edit button-->
                                                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="change"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-dismiss="click"
                                                            title="Change avatar">
                                                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                                                <!--begin::Inputs-->
                                                                <input type="file" name="Rumah_Pelanggan" id="Rumah_Pelanggan" />
                                                                <input type="hidden" name="avatar_remove" />
                                                                <!--end::Inputs-->
                                                            </label>
                                                            <!--end::Edit button-->

                                                            <!--begin::Cancel button-->
                                                            <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="cancel"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-dismiss="click"
                                                            title="Cancel avatar">
                                                                <i class="ki-outline ki-cross fs-3"></i>
                                                            </span>
                                                            <!--end::Cancel button-->

                                                            <!--begin::Remove button-->
                                                            <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="remove"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-dismiss="click"
                                                            title="Remove avatar">
                                                                <i class="ki-outline ki-cross fs-3"></i>
                                                            </span>
                                                            <!--end::Remove button-->
                                                        </div>
<!--end::Image input-->
                                                       </td>
                                                    <td>
                                                     <!--begin::Image input-->
                                                     <?php
                                                        $relativePath = '/images/evidence/' . $dispatchID . '/Comp_ODP.jpg';
                                                        $absolutePath = public_path($relativePath);

                                                        // Check if the file exists
                                                        if (file_exists($absolutePath)) {
                                                            $image = $relativePath; // Use relative path for the view
                                                        } else {
                                                            $image = '/images/empty.png'; // Default image path
                                                        }
                                                        ?>
                                                     <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ $image }})">
                                                        <!--begin::Image preview wrapper-->
                                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $image }})"></div>
                                                        <!--end::Image preview wrapper-->

                                                        <!--begin::Edit button-->
                                                        <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="change"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-dismiss="click"
                                                        title="Change avatar">
                                                            <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                                            <!--begin::Inputs-->
                                                            <input type="file" name="ODP" id="ODP" />
                                                            <input type="hidden" name="avatar_remove" />
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <!--end::Edit button-->

                                                        <!--begin::Cancel button-->
                                                        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="cancel"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-dismiss="click"
                                                        title="Cancel avatar">
                                                            <i class="ki-outline ki-cross fs-3"></i>
                                                        </span>
                                                        <!--end::Cancel button-->

                                                        <!--begin::Remove button-->
                                                        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="remove"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-dismiss="click"
                                                        title="Remove avatar">
                                                            <i class="ki-outline ki-cross fs-3"></i>
                                                        </span>
                                                        <!--end::Remove button-->
                                                    </div>                                                    
                                                    </td>
                                                    <td>
                                                         <!--begin::Image input-->
                                                         <?php
                                                        $relativePath = '/images/evidence/' . $dispatchID . '/Comp_Rumah_Pelanggan.jpg';
                                                        $absolutePath = public_path($relativePath);

                                                        // Check if the file exists
                                                        if (file_exists($absolutePath)) {
                                                            $image = $relativePath; // Use relative path for the view
                                                        } else {
                                                            $image = '/images/empty.png'; // Default image path
                                                        }
                                                        ?>
                                                     <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ $image }}))">
                                                        <!--begin::Image preview wrapper-->
                                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $image }})"></div>
                                                        <!--end::Image preview wrapper-->

                                                        <!--begin::Edit button-->
                                                        <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="change"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-dismiss="click"
                                                        title="Change avatar">
                                                            <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                                            <!--begin::Inputs-->
                                                            <input type="file" name="Capture" id="Capture"  />
                                                            <input type="hidden" name="avatar_remove" />
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <!--end::Edit button-->

                                                        <!--begin::Cancel button-->
                                                        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="cancel"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-dismiss="click"
                                                        title="Cancel avatar">
                                                            <i class="ki-outline ki-cross fs-3"></i>
                                                        </span>
                                                        <!--end::Cancel button-->

                                                        <!--begin::Remove button-->
                                                        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="remove"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-dismiss="click"
                                                        title="Remove avatar">
                                                            <i class="ki-outline ki-cross fs-3"></i>
                                                        </span>
                                                        <!--end::Remove button-->
                                                    </div> 
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="my-4">
                                            <h4>Video</h4> (Max. 1 MB)
                                            <input type="file" />
                                        </div>
                                        <div class="my-4">
                                            <h4>BA UT Online</h4>
                                            <input type="file" />
                                        </div>
                                        <div class="my-4">

                                        </div>
                                    </div>
                                    <div class="card-body pt-0 fv-row" data-select2-id="select2-data-139-c6ib">
                                        <label class="required fw-semibold fs-6 mb-2">Kordinat Pelanggan</label>
                                        <input type="text" class="form-control form-control-solid mb-3 mb-lg-0" id="kordinat_pelanggan" placeholder="" name="kordinat_pelanggan" value="{{ $order->kordinat_pelanggan }}" readonly />
                                    </div>
                                </div>
                            
                        </div>
                    
                </div>
                <!--end::Tab pane-->
            </div>
            <!--end::Tab content-->
        </div>
        <!--end::Order details page-->
    </div>
    </form>
</div>

@endsection
@section('js')
<script type="module">
import exifr from '/js/exif/lite.esm.js';
document.querySelector('#Rumah_Pelanggan').addEventListener('change', async e => {
    let file = e.target.files[0];
    let exifData = await exifr.parse(file);
    console.log('exifData', exifData);
    document.querySelector('#kordinat_pelanggan').value = exifData.latitude+','+exifData.longitude;
    // document.querySelector('#kordinat_pelanggan').value = JSON.stringify(exifData);
});
const target            = document.getElementById('kt_ecommerce_add_product_status');
const select            = document.getElementById('status');
const statusClasses     = ['bg-success','bg-warning','bg-danger'];
const kendalaTeknik     = document.getElementById('kendala_teknik');
const kendalaPelanggan  = document.getElementById('kendala_pelanggan');
const kendalaLainnya    = document.getElementById('kendala_lainnya');

const showKendalaTeknik = () => {
    kendalaTeknik.parentNode.classList.remove('d-none');
}
const hideKendalaTeknik = () => {
    kendalaTeknik.parentNode.classList.add('d-none');
}
const showKendalaPelanggan = () => {
    kendalaPelanggan.parentNode.classList.remove('d-none');
}
const hideKendalaPelanggan = () => {
    kendalaPelanggan.parentNode.classList.add('d-none');
} 
const showKendalaLainnya = () => {
    kendalaLainnya.parentNode.classList.remove('d-none');
}
const hideKendalaLainnya = () => {
    kendalaLainnya.parentNode.classList.add('d-none');
} 


document.querySelector('#status').addEventListener('change', async e => {
    const value = e.target.value;
    console.log(value);
    switch (value){
        case "2" :
            target.classList.remove(...statusClasses);
            target.classList.add('bg-danger'); 
            showKendalaTeknik();
            hideKendalaPelanggan();
            hideKendalaLainnya();
        break;
        case "3" :
            target.classList.remove(...statusClasses);
            target.classList.add('bg-danger');
            showKendalaPelanggan();
            hideKendalaTeknik(); 
            hideKendalaLainnya();
        break;
        case "4" :
            target.classList.remove(...statusClasses);
            target.classList.add('bg-danger');
            showKendalaLainnya();
            hideKendalaPelanggan();
            hideKendalaTeknik(); 
        break;
        case "5" :
            target.classList.remove(...statusClasses);
            target.classList.add('bg-success'); 
            hideKendalaPelanggan();
            hideKendalaTeknik();
            hideKendalaLainnya();
        break;
        default :
            target.classList.remove(...statusClasses);
            target.classList.add('bg-warning'); 
            hideKendalaPelanggan();
            hideKendalaTeknik();
            hideKendalaLainnya();
        break;


    }
});
</script>
<!-- form validation !-->
<script>
// Define form element
const form = document.getElementById('kt_form_reporting');

// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'kordinat_pelanggan': {
                validators: {
                    notEmpty: {
                        message: 'Kordinat Pelanggan is required'
                    }
                }
            },
            'odp_installation': {
                validators: {
                    notEmpty: {
                        message: 'ODP Installation is required'
                    }
                }
            },
            'status': {
                validators: {
                    notEmpty: {
                        message: 'Status is required'
                    }
                }
            },
            
        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

// Submit button handler
const submitButton = document.getElementById('kt_docs_formvalidation_text_submit');
form.addEventListener('submit', function (e) {
    // Prevent default button action
    e.preventDefault();
    var data = this;
    // Validate form before submit
    if (validator) {
        validator.validate().then(function (status) {
            console.log('validated!');

            if (status == 'Valid') {
                var progressContainer = document.getElementById('progress-container');
                overlay.classList.add('activex');
                progressContainer.classList.add('activex');

                // Your XMLHttpRequest logic here (from the previous code example)
                var xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percentComplete = (e.loaded / e.total) * 100;
                        document.getElementById('progress-bar').style.width = percentComplete + '%';
                        document.getElementById('progress-bar').innerText = Math.round(percentComplete) + '%';
                        if (percentComplete === 100) {
                            setTimeout(function() {
                                overlay.classList.remove('activex');
                                progressContainer.classList.remove('activex');
                            }, 500); // Optional delay for visual effect
                        }
                    }
                }, false);

                xhr.open('POST', e.target.action, true);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.addEventListener('load', function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        Swal.fire({
                            text: "Form has been successfully submitted!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    } else {
                        var response = JSON.parse(xhr.responseText);
                        if (response.errors) {
                            // Handle errors
                        }
                    }

                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;
                });

                xhr.addEventListener('error', function() {
                    // Handle the error
                });

                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;

                var data = new FormData(e.target);
                // var file = document.getElementById('Rumah_Pelanggan').files[0];
                // data.append('file', file);
                data.append('Rumah_Pelanggan', document.getElementById('Rumah_Pelanggan').files[0]);
                data.append('ODP', document.getElementById('ODP').files[0]);
                data.append('Capture', document.getElementById('Capture').files[0]);

                xhr.send(data);
            }
        });
    }
});
</script>
@endsection