@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Notification</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h4 class="m-0 text-secondary page-title">NOTIFICATION</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#send_new_notification" class="btn btn-primary"><i data-feather="send"></i>Send New</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Notification</strong>
                    </div>
                    <div class="card-body overflow-hidden">
                        <table id="dt_notification" class="datatable table table-striped table-bordered responsive nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th width="200">Title</th>
                                <th>Body</th>
                                <th>Image</th>
                                <th>Send To</th>
                                <th>Send At</th>
                                <th>Send By</th>
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

    @include('admin.notification._add')
@endsection

@section('js')
    <script src="{{url('/js/admin/notification.js')}}"></script>
    <script>
        init_data_table();
    </script>
@endsection