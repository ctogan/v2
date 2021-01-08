@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Management</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/settings/user-admin/add')}}" class="btn btn-primary"><i data-feather="plus"></i>Add New</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Users</strong>
                    </div>
                    <div class="card-body">
                        <table id="dt_users" class="datatable table table-striped table-bordered table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Updated By</th>
                                <th>Updated At</th>
                                <th data-priority="1">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                ?>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->created_by}}</td>
                                        <td>{{$user->created_at}}</td>
                                        <td>{{$user->updated_by}}</td>
                                        <td>{{$user->updated_at}}</td>
                                        <td align="center">
                                            <a class="mr-1" href="{{url("/admin/settings/user-admin/edit/". $user->id)}}"><i data-feather="edit"></i></a>
                                            <a onclick="delete_user({{$user->id}})" href="javascript:void(0)"><i data-feather="trash"></i></a>
                                        </td>
                                        <?php
                                        $no ++;
                                        ?>
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

@section('js')
    <script src="{{url('/js/admin/settings/setting.js')}}" type="application/javascript" ></script>
@endsection