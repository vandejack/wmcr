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
                <table>
                    <tr>
                        <td>INPUT</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="technician" readonly value="{{ $nik }}" />
                            <input type="hidden" name="back_url" readonly value="{{ $back_url }}" />
                        </td>
                    </tr>
                    <tr>
                        <td>TGL</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="date" readonly value="{{ $periode }}">
                        </td>
                    </tr>
                    <tr>
                        <td>STATUS</td>
                        <td>:</td>
                        <td>
                            <select name="status">
                                <option value="">--</option>
                                @foreach ($scheduleStatus as $r)
                                <option value="{{ $r->status_id }}" <?php if ($r->status_id==$status) { echo 'selected'; } else { } ?>>{{ $r->name }}</option>
                                @endforeach
                            </select>
                            
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="submit" value="submit" />
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection