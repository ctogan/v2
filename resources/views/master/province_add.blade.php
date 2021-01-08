@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item">Province</li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/master/province')}}" class="btn btn-primary"><i data-feather="list"></i> List Province</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        New Province
                    </div>
                    <div class="card-body">
                        <form id="form_new_province" enctype="multipart/form-data" method="POST">
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Province Name*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Input Province Name" name="province_name" id="province_name">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed-bottom">
            <div class="card bg-light border-radius-none">
                <div class="card-header">
                    <div class="container">
                        <button id="btn_submit_province" class="btn btn-primary"><i data-feather="save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/js/master/province.js')}}" type="application/javascript" ></script>
@endsection