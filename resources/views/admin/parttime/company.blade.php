@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item active" aria-current="page">Company</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/part-time/company/add')}}" class="btn btn-primary"><i data-feather="plus"></i>Add New</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Company</strong>
                    </div>
                    <div class="card-body overflow-hidden">
                        <table id="dt_company" class="datatable table table-striped table-bordered responsive nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>UID</th>
                                <th>Company Name</th>
                                <th>Company Logo</th>
                                <th>Category</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Website</th>
                                <th>Created At</th>
                                <th>Created By</th>
                                <th>Updated At</th>
                                <th>Update By</th>
                                <th data-priority="2">Count</th>
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
    <script src="{{url('/js/admin/parttime/company.js')}}" type="application/javascript" ></script>
    <script>
        init_data_table();
    </script>
@endsection