@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item active" aria-current="page">Vacancy</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-12 mt-3">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <strong>List Of Vacancy</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Filter Data</strong></p>
                                <form action="" method="get">
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <select class="custom-select select2" name="company_filter" id="company_filter">
                                                <option value="">Select All Company</option>
                                                @foreach($company as $item)
                                                    <option value="{{$item->id}}" {{$item->id == $company_filter ? "selected" : "" }}>{{$item->company_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="custom-select select2" name="category_filter" id="category_filter">
                                                <option value="">Select All Category</option>
                                                @foreach($category as $item)
                                                    <option value="{{$item->id}}" {{$item->id == $category_filter ? "selected" : "" }}>{{$item->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="custom-select select2" name="province_filter" id="province_filter">
                                                <option value="">Select All Province</option>
                                                @foreach($province as $item)
                                                    <option value="{{$item->id}}" {{$item->id == $province_filter ? "selected" : "" }}>{{$item->province_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="custom-select select2" name="city_filter" id="city_filter">
                                                <option value="">Select All City</option>
                                                @foreach($city as $item)
                                                    <option value="{{$item->id}}" {{$item->id == $city_filter ? "selected" : "" }}>{{$item->city_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-primary mr-1"><i data-feather="search"></i> Search</button>
                                            <a href="{{url('/admin/part-time/vacancy')}}" class="btn btn-outline-primary"><i data-feather="maximize-2" class="mr-1"></i>Show All</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr/>
                        <table id="dt_vacancy" class="datatable table table-striped table-bordered nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Position Name</th>
                                <th>Company Name</th>
                                <th>Category</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Experienced</th>
                                <th>Salary</th>
                                <th>Allowance</th>
                                <th>Send To Email</th>
                                <th>Send To Wa</th>
                                <th>Active Until</th>
                                <th>Created At</th>
                                <th>Created By</th>
                                <th>Updated At</th>
                                <th>Update By</th>
                                <th data-priority="2">Vacancy Status</th>
                                <th data-priority="1">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/js/admin/parttime/applicant.js')}}" type="application/javascript" ></script>
    <script>
        init_data_table();
    </script>
@endsection