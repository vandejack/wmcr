@extends('layout')

@section('css')

@endsection

@section('title', 'Profile')

@section('content')
<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Profile Details</h3>
        </div>
    </div>
    <div id="kt_account_settings_profile_details" class="collapse show">
        <form id="kt_account_profile_details_form" class="form" method="post" enctype="multipart/form-data" autocomplete="off">
            
            {{ csrf_field() }}
            <input type="hidden" name="nik" value="{{ $data->nik ?? old('nik') }}">
            
            <div class="card-body border-top p-9">
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
                    <div class="col-lg-8">
                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(/assets/media/avatars/blank.png);">
                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url(/assets/media/avatars/blank.png);"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                {{-- <input type="hidden" name="avatar_remove" /> --}}
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>
                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">NIK</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->nik ?? old('nik') }}" disabled/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Name</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->name ?? old('name') }}" disabled/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                        <span class="required">Chat ID</span>
                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Chat ID must be obtained from the @WMCR_Bot"></i>
                    </label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="chat_id" class="form-control form-control-lg form-control-solid" placeholder="Masukan Chat ID Telegram" value="{{ $data->chat_id ?? old('chat_id') }}" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Password</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="password" class="form-control form-control-lg form-control-solid" placeholder="Ganti Password Baru" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Time Zone</label>
                    <div class="col-lg-8 fv-row">
                        <select name="timezone_id" aria-label="Select a Timezone" data-control="select2" data-placeholder="Select a timezone.." class="form-select form-select-solid form-select-lg" id="timezone_id"></select>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Regional</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->regional_name ?? old('regional_name') }}" disabled/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Witel</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->witel_name ?? old('witel_name') }}" disabled/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Mitra</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->mitra_name ?? old('mitra_name') }}" disabled/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Unit</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->unit_name ?? old('unit_name') }}" disabled/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Sub Unit</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->sub_unit_name ?? old('sub_unit_name') }}" disabled/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Sub Group</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->sub_group_name ?? old('sub_group_name') }}" disabled/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Position</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->position_name ?? old('position_name') }}" disabled/>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Level Access</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $data->level_name ?? old('level_name') }}" disabled/>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
    $(document).ready(function () {

        $('#timezone_id').select2({
            allowClear: true,
            placeholder: 'Pilih Timezone',
            ajax: {
                url: "/ajax/select2/timezone/0",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true,
            }
        });

        var data = {!! json_encode($data) !!};

        if (data != null)
        {
            if (data.timezone_name != null)
            {
                $("#timezone_id").empty().append(new Option(data.timezone_name, data.timezone_id, true, true)).trigger('change');
            }
        }
    })
</script>
@endsection