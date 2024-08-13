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

@section('title', 'Update Data IHLD')
@section('content')
<div id="overlay"></div>
                <div id="progress-container">
                    Uploading : <div id="progress-bar">0%</div>
                </div>
<div class="card shadow-sm">
	<div class="card-body pb-4">
        <div class="table-responsive">
            <form id="uploadForm" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <label for="excelFile">Upload Excel File:</label>
                <input type="file" id="file" name="file" class="form-control" accept=".xlsx, .xls">
                <input type="submit" class="btn btn-success ym-7" value="submit" id="submitButton" />
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
 

</script>
@endsection