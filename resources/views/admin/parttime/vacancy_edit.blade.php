@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item"><a href="{{url('/admin/part-time/vacancy')}}">Vacancy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5 mt-4">
            <div class="col-md-6 mt-2">
                <div class="vacancy_title pl-2 align-items-center">
                    <h3 class="mb-0 text-primary">{{$vacancy->position_name .' - ' . $vacancy->company_name}} </h3>
                    <span class="text-muted">Created by : {{$vacancy->created_by}}</span>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{url('/admin/part-time/vacancy/add')}}" class="btn btn-outline-primary"><i data-feather="plus"></i> Add Vacancy</a>
                <a href="{{url('/admin/part-time/company/add')}}" class="btn btn-outline-primary"><i data-feather="plus"></i> Add Company</a>
                <a href="{{url('/admin/part-time/vacancy')}}" class="btn btn-primary"><i data-feather="list"></i> List Vacancy</a>
            </div>
            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="w-75">
                                <strong>Edit Job Vacancy</strong>
                            </div>
                            <div class="text-right w-25">
                                Status : <span class="badge {{UtilsHelp::get_vacancy_badge($vacancy->vacancy_status)}} p-1">{{ucfirst($vacancy->vacancy_status)}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="form_update_vacancy">
                            <input type="hidden" value="{{$vacancy->id}}" name="id" id="id">
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Company Name*</label>
                                <div class="col-sm-6">
                                    <div class="d-flex">
                                        <select class="custom-select select2" name="company_id" id="company_id">
                                            <option>Choose Company</option>
                                            @foreach($company as $item)
                                                <option value="{{$item->id}}" {{$item->id == $vacancy->company_id ? "selected" : "" }}>{{$item->company_name}}</option>
                                            @endforeach
                                        </select>
                                        <a class="btn btn-light ml-2" href="{{url('/admin/part-time/company/edit/'.$vacancy->company_id)}}"><i data-feather="eye"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Province*</label>
                                <div class="col-sm-6">
                                    <select class="custom-select select2" name="province_id" id="province">
                                        <option>Choose Province</option>
                                        @foreach($province as $item)
                                            <option value="{{$item->id}}" {{$item->id == $vacancy->province_id ? "selected" : "" }}>{{$item->province_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">City*</label>
                                <div class="col-sm-6">
                                    <input type="hidden" id="hdn_city" value="">
                                    <select class="custom-select select2" name="city_id" id="city">
                                        @foreach($city as $item)
                                            <option value="{{$item->id}}" {{$item->id == $vacancy->city_id ? "selected" : "" }}>{{$item->city_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Position Name*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" value="{{$vacancy->position_name}}" placeholder="Position Name" name="position_name" id="position_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Job Description*</label>
                                <div class="col-sm-10">
                                    <textarea name="description" class="form-control" style="white-space: pre-line;" cols="100" rows="8">{{$vacancy->description}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Qualifications*</label>
                                <div class="col-sm-10">
                                    <textarea name="qualifications" class="form-control" style="white-space: pre-line;" cols="100" rows="8">{{$vacancy->qualifications}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="education" class="col-sm-2 col-form-label">Education*</label>
                                <div class="col-sm-3">
                                    <select class="custom-select" name="education" id="education">
                                        <option>Choose Education</option>
                                        @foreach($education as $item)
                                            <option value="{{$item->id}}" {{$vacancy->education == $item->id ? "selected" : "" }}>{{$item->education}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Experienced*</label>
                                <div class="col-sm-3">
                                    <select class="custom-select" name="experienced" id="experienced">
                                        <option value="0">Fresh Graduated</option>
                                        <option value="1" {{$vacancy->experienced == "1" ? "selected" : "" }}> 1 Year</option>
                                        <option value="2" {{$vacancy->experienced == "2" ? "selected" : "" }}> 2 Years</option>
                                        <option value="3" {{$vacancy->experienced == "3" ? "selected" : "" }}> 3 Years</option>
                                        <option value="4" {{$vacancy->experienced == "4" ? "selected" : "" }}> 4 Years</option>
                                        <option value="5" {{$vacancy->experienced == "5" ? "selected" : "" }}> 5 Years</option>
                                        <option value="6" {{$vacancy->experienced == "6" ? "selected" : "" }}> 6 Years</option>
                                        <option value="7" {{$vacancy->experienced == "7" ? "selected" : "" }}> 7 Years</option>
                                        <option value="8" {{$vacancy->experienced == "8" ? "selected" : "" }}> 8 Years</option>
                                        <option value="9" {{$vacancy->experienced == "9" ? "selected" : "" }}> 9 Years</option>
                                        <option value="10" {{$vacancy->experienced == "10" ? "selected" : "" }}> 10 Years</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Salary*</label>
                                <div class="col-sm-3">
                                    <input type="number" value="{{$vacancy->salary}}" class="form-control" placeholder="Enter Salary" name="salary" id="salary">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="salary_type" class="col-sm-2 col-form-label">Salary Type*</label>
                                <div class="col-sm-3">
                                    <select class="form-control custom-select" name="salary_type">
                                        <option value=""></option>
                                        <option value="hourly" {{$vacancy->salary_type == "hourly" ? "selected" : "" }}>Hourly</option>
                                        <option value="daily" {{$vacancy->salary_type == "daily" ? "selected" : "" }}>Daily</option>
                                        <option value="weekly" {{$vacancy->salary_type == "weekly" ? "selected" : "" }}>Weekly</option>
                                        <option value="monthly" {{$vacancy->salary_type == "monthly" ? "selected" : "" }}>Monthly</option>
                                        <option value="project" {{$vacancy->salary_type == "project" ? "selected" : "" }}>Project</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Allowance</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{$vacancy->allowance}}" class="form-control" placeholder="Enter Allowance" name="allowance" id="allowance">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Send to Email*</label>
                                <div class="col-sm-6">
                                    <input type="text" value="{{$vacancy->send_to_email}}" class="form-control" placeholder="Enter Email" name="send_to_email" id="sent_to_email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Send to Whatsapp</label>
                                <div class="col-sm-3">
                                    <input type="text" value="{{$vacancy->send_to_wa}}" class="form-control" placeholder="Enter Whatsapp Number" name="send_to_wa" id="send_to_wa">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Active Until*</label>
                                <div class="col-sm-3">
                                    <input type="date" value="{{date_format(date_create($vacancy->active_until),"Y-m-d")}}" class="form-control" name="active_until" id="active_until">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Status*</label>
                                <div class="col-sm-3 d-flex align-items-center">
                                    @if ($vacancy->vacancy_status == "published" || $vacancy->vacancy_status == "unpublished")
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="vacancy_status" value="published" {{$vacancy->vacancy_status == "published" ? "checked" : "" }}>
                                            <label class="form-check-label">
                                                Published
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="vacancy_status" value="unpublished" {{$vacancy->vacancy_status == "unpublished" ? "checked" : "" }}>
                                            <label class="form-check-label">
                                                Unpublished
                                            </label>
                                        </div>
                                    @else
                                        <div class="form-check form-check-inline">
                                            <input type="hidden" value="{{$vacancy->vacancy_status}}" name="vacancy_status">
                                            <span class="badge {{UtilsHelp::get_vacancy_badge($vacancy->vacancy_status)}} p-2">{{ucfirst($vacancy->vacancy_status)}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if($vacancy->vacancy_status == 'rejected')
                                <div class="form-group row">
                                    <label for="position_name" class="col-sm-2 col-form-label">Rejection Notes*</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="white-space: pre-line;" cols="100">{!! $vacancy->rejection_reason !!}</textarea>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        History
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="width: 100px">Datetime</th>
                                <th style="width: 150px">Changes By</th>
                                <th>Changes</th>
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

        <div class="fixed-bottom">
            <div class="card bg-light border-radius-none">
                <div class="card-header">
                    <div class="container">
                        @if ($vacancy->vacancy_status == "pending" || $vacancy->vacancy_status == "waiting_confirm")
                            <button id="btn_update_vacancy" class="btn btn-primary mr-1"><i data-feather="save"></i> Update</button>
                            <button id="btn_approve_vacancy" class="btn btn-primary mr-1"><i data-feather="check"></i> Approve</button>
                            <button id="btn_open_reject_form" class="btn btn-outline-danger"><i data-feather="x"></i> Reject</button>
                            <a class="ml-2" href="{{url('/admin/part-time/vacancy/add')}}"><u>or Add New Vacancy</u></a>
                        @elseif ($vacancy->vacancy_status != "rejected")
                            <button id="btn_update_vacancy" class="btn btn-primary"><i data-feather="save"></i> Update</button>
                            <a class="ml-2" href="{{url('/admin/part-time/vacancy/add')}}"><u>or Add New Vacancy</u></a>
                        @else
                            <strong class="text-danger">You cannot change data that has been rejected.</strong>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("admin.parttime.vacancy_rejection_form")
@endsection

@section('js')
    <script src="{{url('/js/admin/parttime/vacancy.js')}}" type="application/javascript" ></script>
@endsection
