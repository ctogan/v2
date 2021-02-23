@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Operational</a></li>
                <li class="breadcrumb-item active" aria-current="page">Clbk Event</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/operational/clbk/event/upload')}}" class="btn btn-primary"><i data-feather="plus"></i>Add New</a>
                <a href="{{url('/sms-job')}}" class="btn btn-primary"><i data-feather="plus"></i>Start</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Contact</strong>
                    </div>
                    <div class="card-body">
                        <table id="dt_city" class="datatable table table-striped table-bordered nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Uid</th>
                                <th>Phone Number</th>
                                <th>Message</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sms_list as $key => $val)
                            <tr>
                                <td>{{$val->uid}}</td>
                                <td>{{$val->uid}}</td>
                                <td>+62{{$val->phone_number}}</td>
                                <td>{{$val->message}}</td>
                                <td>{{$val->status}}</td>
                            </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>



                </div>
            </div>
        </div>
    </div>

@endsection