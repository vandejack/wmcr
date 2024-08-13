@extends('layout')

@section('css')
<style>
    th, td {
        white-space: nowrap;
    }
    .productivity_table th {
        padding : 5px !important;
        background-color: #636363;
        color : #ffffff;
        border: 1px solid rgb(242, 242, 242);
        text-align: center;
        vertical-align: middle;
        border-collapse: collapse;
    }
    .productivity_table td {
        padding : 5px !important;
        background-color: #FFF;
        color : #000;
        border: 1px solid rgb(242, 242, 242);
        text-align: center;
        vertical-align: middle;
        border-collapse: collapse;
    }
    .btn {
        padding : 5px !important;
    }
</style>
@endsection

@section('title', 'Update Schedule '.$nik)

@section('content')
<div class="row">
    <div class="col-xl-12" >
        <div class="card shadow-sm">
            <div class="card-body pb-4">
                <form method="post">
                    <div class="my-4 fv-row">
                        <label class="required fw-semibold fs-6 mb-2">NIK</label>
                        <input type="hidden" id="defaultSector" class="form-control" readonly value="{{ $defaultSector }}" />
                        <input type="text" name="technician" class="form-control" readonly value="{{ $nik }}" />
                        <input type="hidden" name="back_url" class="form-control" readonly value="{{ $back_url }}" />
                    </div>
                    <div class="my-4 fv-row">
                        <label class="required fw-semibold fs-6 mb-2">TANGGAL</label>
                        <input type="text" name="date" class="form-control" readonly value="{{ $periode }}">
                    </div>
                    <div class="my-4 fv-row">
                        <label class="required fw-semibold fs-6 mb-2">TANGGAL</label>
                        <select name="status" id="statusx" class="form-control">
                            <option value="">--</option>
                            @foreach ($scheduleStatus as $r)
                            <option value="{{ $r->status_id }}" <?= ($r->status_id == $status) ? 'selected' : '' ?>>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <?php
                        $activeClass = ($status == 3) ? '' : 'd-none';
                    ?>
                    <div class="my-4 fv-row {{ $activeClass }}">
                        <label class="fw-semibold fs-6 mb-2">Witel</label>
                        <select name="witel" id="witel" class="form-control">
                            <option value="">--</option>
                            @foreach ($getWitel as $witel)
                            <option value="{{ $witel->id }}" <?= ($witel->id == $bantekWitel) ? 'selected' : '' ?>>{{ $witel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="my-4 fv-row {{ $activeClass }}">
                        <label class="fw-semibold fs-6 mb-2">Sektor</label>
                        <select name="sektor" id="sektor" class="form-control">
                            @foreach ($getSectorbyWitel as $r)
                            <option value="{{ $r->id }}"  <?= ($r->id == $bantekSector) ? 'selected' : '' ?>>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="my-4 fv-row">
                        <input type="submit" class="btn btn-success" value="Submit" />
                    </div>

                </table>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>

const status = document.getElementById('statusx');
const witel = document.getElementById('witel');
const sektor = document.getElementById('sektor');
const showBantekLocation = () => {
    witel.parentNode.classList.remove('d-none');
    sektor.parentNode.classList.remove('d-none');
}
const hideBantekLocation = () => {
    witel.parentNode.classList.add('d-none');
    sektor.parentNode.classList.add('d-none');
}
status.addEventListener('change', async e => {
    const value = e.target.value;
    console.log(value);
    if (value==3){
        showBantekLocation();
    } else {
        hideBantekLocation();
    }
});
witel.addEventListener('change', async e => {
    const idWitel = e.target.value;
    const defaultSector = document.getElementById('defaultSector').value;
    var sectorSelect = sektor;
    sectorSelect.innerHTML = '';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/ajax/getSectorExcept/' + idWitel+'/'+defaultSector, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = xhr.response;
            var options = '';
            data.forEach(function(item) {
                options += '<option value="' + item.id + '">' + item.name + '</option>';
            });
            sectorSelect.innerHTML = options;
            sectorSelect.style.display = 'block';
        } else {
            console.error('Error fetching data from the server.');
        }
    };

    xhr.onerror = function() {
        console.error('Error fetching data from the server.');
    };

    xhr.send();    
});

</script>
@endsection