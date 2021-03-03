@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item active" aria-current="page">Applicant</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Applicant</strong>
                    </div>
                    <div class="card-body overflow-hidden">
                        <table id="dt_applicant" class="datatable table table-striped table-bordered responsive nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>UID</th>
                                <th>Name</th>
                                <th>Company Name</th>
                                <th>Vacancy Name</th>
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

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection