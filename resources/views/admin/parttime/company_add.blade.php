@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item"><a href="#">Company</a></li>
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/part-time/company')}}" class="btn btn-outline-primary"><i data-feather="plus"></i> Add Province</a>
                <a href="{{url('/admin/part-time/company')}}" class="btn btn-outline-primary"><i data-feather="plus"></i> Add City</a>
                <a href="{{url('/admin/part-time/company')}}" class="btn btn-outline-primary"><i data-feather="plus"></i> Add Category</a>
                <a href="{{url('/admin/part-time/company')}}" class="btn btn-primary"><i data-feather="list"></i> Company List</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        New Job Vacancy
                    </div>
                    <div class="card-body">
                        <form id="form_new_company" enctype="multipart/form-data" method="POST">
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Company Name*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Input Company Name" name="company_name" id="company_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Company Logo*</label>
                                <div class="col-sm-3">
                                    <div class="preview mb-1" style="width: 100%;border:1px dotted">
                                        <img id="image-preview" src="{{url('/assets/images/default.png')}}" width="100%">
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="company_logo" class="custom-file-input" id="customFile" required>
                                        <label class="custom-file-label overflow-hidden" for="customFile">Choose file</label>
                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Company Category*</label>
                                <div class="col-sm-6">
                                    <select class="custom-select select2" name="category" id="category">
                                        <option value="">Choose Category</option>
                                        @foreach($category as $item)
                                            <option value="{{$item->id}}">{{$item->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Address*</label>
                                <div class="col-sm-10">
                                    <textarea name="address" class="form-control" style="white-space: pre-line;" cols="100" rows="8"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Province*</label>
                                <div class="col-sm-6">
                                    <select class="custom-select select2" name="province_id" id="province">
                                        <option value="">Choose Province</option>
                                        @foreach($province as $item)
                                            <option value="{{$item->id}}">{{$item->province_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">City*</label>
                                <div class="col-sm-6">
                                    <select class="custom-select select2" name="city_id" id="city">
                                        <option>Choose City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Description*</label>
                                <div class="col-sm-10">
                                    <textarea placeholder="Describe about the company" name="description" class="form-control" style="white-space: pre-line;" cols="100" rows="8"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Email*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Input Email" name="email" id="sent_to_email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Phone Number</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="Input Phone Number" name="phone_number" id="phone_number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Website</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Input Website" name="website" id="website">
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
                        <button id="btn_submit_company" class="btn btn-primary"><i data-feather="save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/js/admin/parttime/company.js')}}" type="application/javascript" ></script>
    <script src="{{url('/js/province.js')}}" type="application/javascript" ></script>
@endsection
