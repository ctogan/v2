@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item"><a href="{{url('/admin/part-time/vacancy')}}">Vacancy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reported</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12 mt-3">
                <div class="card-header">
                    <strong>List Of Reported Vacancy</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="dt_vacancy_reported" class="datatable table table-striped table-bordered nowrap table-hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Position Name</th>
                                    <th>Company Name</th>
                                    <th>Category</th>
                                    <th>Province</th>
                                    <th>City</th>
                                    <th data-priority="2">Reported Count</th>
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
    </div>
@endsection

@section('js')
    <script src="{{url('/js/admin/parttime/vacancy.js')}}" type="application/javascript" ></script>
    <script>
        init_data_table_reported();
    </script>
@endsection