@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Operational</a></li>
                <li class="breadcrumb-item">CLBK</li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        Upload Data
                    </div>
                    <div class="card-body">
                        <form id="form_import_excel" method="post" action="/admin/operational/clbk/event/submit" enctype="multipart/form-data">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="form-group row">
                                <label for="upload_dataa" class="col-sm-2 col-form-label">Upload Data</label>
                                <div class="col-sm-6">
                                  <input type="file" class="form-control" name="file" id="file">
                                </div>
                                <button id="btn_upload_file" class="btn btn-primary"><i data-feather="save"></i> Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/js/operational/clbk.js')}}" type="application/javascript" ></script>
@endsection