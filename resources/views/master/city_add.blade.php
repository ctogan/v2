@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item">City</li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/master/province/add')}}" class="btn btn-outline-primary"><i data-feather="plus"></i> Add Province</a>
                <a href="{{url('/admin/master/city')}}" class="btn btn-primary"><i data-feather="list"></i> List City</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        New City
                    </div>
                    <div class="card-body">
                        <form id="form_new_city" enctype="multipart/form-data" method="POST">
                            <div class="form-group row">
                                <label for="city_name" class="col-sm-2 col-form-label">Province Name*</label>
                                <div class="col-sm-6">
                                    <select class="custom-select select2" name="province_id" id="province">
                                        <option value="">Choose Province</option>
                                        @foreach($province as $item)
                                            <option value="{{$item->id}}" {{$item->id == $province_id ? "selected" : "" }}>{{$item->province_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="city_name" class="col-sm-2 col-form-label">City Name*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Input City Name" name="city_name" id="city_name">
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
                        <button id="btn_submit_city" class="btn btn-primary"><i data-feather="save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/js/master/city.js')}}" type="application/javascript" ></script>
@endsection