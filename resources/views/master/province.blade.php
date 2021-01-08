@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item active" aria-current="page">Province</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/master/city')}}" class="btn btn-outline-primary"><i data-feather="list"></i>City</a>
                <a href="{{url('/admin/master/province/add')}}" class="btn btn-primary"><i data-feather="plus"></i>Add New</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Province</strong>
                    </div>
                    <div class="card-body">
                        <table id="dt_province" class="datatable table table-striped table-bordered nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Province Name</th>
                                <th style="width: 50px;">Action</th>
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
    <script src="{{url('/js/master/province.js')}}" type="application/javascript" ></script>
    <script>
        init_data_table();
    </script>
@endsection