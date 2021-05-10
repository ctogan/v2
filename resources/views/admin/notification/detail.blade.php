@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{url('/admin/notification')}}">Notification</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$notification->title}}</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h4 class="m-0 text-secondary page-title">NOTIFICATION</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{url('/admin/notification')}}" class="btn btn-primary"><i data-feather="list"></i>Notification List</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Notification</strong>
                    </div>
                    <div class="card-body overflow-hidden">
                        <input type="hidden" name="id" id="id" value="{{$notification->id}}">
                        <div>
                            <table class="table">
                                <tr>
                                    <td width="100">Title</td>
                                    <td width="10">:</td>
                                    <td>{{$notification->title}}</td>
                                </tr>
                                <tr>
                                    <td>Message</td>
                                    <td>:</td>
                                    <td>{{$notification->body}}</td>
                                </tr>
                                <tr>
                                    <td>Send To</td>
                                    <td>:</td>
                                    <td class="text-uppercase">{{$notification->send_to}}</td>
                                </tr>
                                <tr>
                                    <td>Image</td>
                                    <td>:</td>
                                    <td class="text-uppercase">
                                        @if($notification->img)
                                            <img src="{{$notification->img}}" alt="" width="100px">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Send By</td>
                                    <td>:</td>
                                    <td>{{$notification->created_by}}, {{$notification->created_at}}</td>
                                </tr>
                            </table>
                        </div>
                        <hr>
                        <div class="p-2">
                            <table id="dt_notification_detail" class="datatable table table-striped table-bordered responsive nowrap table-hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="200">UID</th>
                                    <th>Read?</th>
                                    <th>Received At</th>
                                    <th>Read At</th>
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
    </div>

    @include('admin.notification._add')
@endsection

@section('js')
    <script src="{{url('/js/admin/notification.js')}}"></script>
    <script>
        init_detail_table({{$notification->id}});
    </script>
@endsection