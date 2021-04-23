@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Cerdas Cermat</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h4 class="m-0 text-secondary page-title">Cerdas Cermat</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{url('/admin/cerdas-cermat/question')}}" class="btn btn-outline-primary mr-2"><i data-feather="list"></i>Question</a>
                        <a href="{{url('/admin/cerdas-cermat/add')}}" class="btn btn-primary"><i data-feather="plus"></i>Add Sesion</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Cerdas Cermat</strong>
                    </div>
                    <div class="card-body overflow-hidden">
                        <table id="dt_dynamic_section" class="datatable table table-striped table-bordered responsive nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Question</th>
                                <th>Open Date</th>
                                <th>Point</th>
                                <th>Prize 1st</th>
                                <th>Prize 2nd</th>
                                <th>Prize 3nd</th>
                                <th>Contribution Prize</th>
                                <th>Created At</th>
                                <th>Created By</th>
                                <th>Updated At</th>
                                <th>Updated By</th>
                                <th data-priority="2">Status</th>
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

@section('js')
    <script src="{{url('/js/admin/cerdascermat.js')}}"></script>
@endsection