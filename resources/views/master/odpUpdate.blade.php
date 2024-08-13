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

@section('title', 'Update Alpro ODP')
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
                <input type="submit" class="btn btn-default" value="submit" id="submitButton" />
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    
// Submit button handler
const submitButton = document.getElementById('submitButton');
const form = document.getElementById('uploadForm');


form.addEventListener('submit', function (e) {
    // Prevent default button action
    e.preventDefault();
    var data = this;
    // Validate form before submit

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
                data.append('file', document.getElementById('file').files[0]);

                xhr.send(data);
            });

</script>
@endsection