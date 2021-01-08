@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Settings</a></li>
                <li class="breadcrumb-item">User</li>
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/settings/user-admin')}}" class="btn btn-primary"><i data-feather="list" class="mr-1"></i>List Users</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>New User</strong>
                    </div>
                    <div class="card-body">
                        <form id="form_new_user">
                            <div class="form-group row">
                                <label for="city_name" class="col-sm-3 col-form-label">User Name*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Input User Name" name="name" id="name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email*</label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control" placeholder="Input Email" name="email" id="email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-3 col-form-label">Password*</label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" placeholder="Input Password" name="password" id="password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password_confirmation" class="col-sm-3 col-form-label">Password Confirmation*</label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" placeholder="Input Password Confirmation" name="password_confirmation" id="password_confirmation">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password_confirmation" class="col-sm-3 col-form-label">Role*</label>
                                <div class="col-sm-6">
                                    <select class="form-control custom-select">
                                        <option value="ceo">Super (CEO, Director)</option>
                                        <option value="IT">IT</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Operational">Operational</option>
                                        <option value="Finance">Operational</option>
                                        <option value="HR">Operational</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password_confirmation" class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-primary" id="btn_submit_user"><i data-feather="save" class="mr-1"></i>Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/js/admin/settings/setting.js')}}" type="application/javascript" ></script>
@endsection