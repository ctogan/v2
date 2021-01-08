@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Change Password</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        Change Password
                    </div>
                    <div class="card-body">
                        <form id="form_new_province" enctype="multipart/form-data" method="POST">
                            <div class="form-group row">
                                <label for="company" class="col-sm-3 col-form-label">Old Password*</label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" placeholder="Input Old Password" name="old_password" id="old_password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-3 col-form-label">New Password*</label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" placeholder="Input New Password" name="password" id="password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-3 col-form-label">Password Confirmation*</label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" placeholder="Input Password Confirmation" name="password_confirmation" id="password_confirmation">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6">
                                    <button type="button" id="btn_change_password" class="btn btn-primary" >Change Now</button>
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