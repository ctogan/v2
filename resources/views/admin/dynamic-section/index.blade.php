@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Dynamic Section</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h4 class="m-0 text-secondary page-title">Dynamic Section</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#add_new_dynamic_section" class="btn btn-primary"><i data-feather="plus"></i>Add New</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Dynamic Section</strong>
                    </div>
                    <div class="card-body overflow-hidden">
                        <table id="dt_dynamic_section" class="datatable table table-striped table-bordered responsive nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th width="200">Title</th>
                                <th>Sub Title</th>
                                <th>Target</th>
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

    @include('admin.dynamic-section._add')
    @include('admin.dynamic-section._edit')
@endsection

@section('js')
    <script src="{{url('/js/admin/dynamicsection.js')}}"></script>
@endsection