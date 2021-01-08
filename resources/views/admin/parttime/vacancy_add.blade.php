@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item"><a href="{{url('/admin/part-time/vacancy')}}">Vacancy</a></li>
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/part-time/company/add')}}" class="btn btn-outline-primary"><i data-feather="plus"></i> Add Company</a>
                <a href="{{url('/admin/part-time/vacancy')}}" class="btn btn-primary"><i data-feather="list"></i> List Vacancy</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        New Job Vacancy
                    </div>
                    <div class="card-body">
                        <form id="form_new_vacancy">
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Company Name*</label>
                                <div class="col-sm-6">
                                    <select class="custom-select select2" name="company_id" id="company_id">
                                        <option>Choose Company</option>
                                        @foreach($company as $item)
                                            <option value="{{$item->id}}">{{$item->company_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Province*</label>
                                <div class="col-sm-6">
                                    <select class="custom-select select2" name="province_id" id="province">
                                        <option>Choose Province</option>
                                        @foreach($province as $item)
                                            <option value="{{$item->id}}">{{$item->province_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">City*</label>
                                <div class="col-sm-6">
                                    <input type="hidden" id="hdn_city" value="">
                                    <select class="custom-select select2" name="city_id" id="city">
                                        <option>Choose City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Position Name*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Position Name" name="position_name" id="position_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Job Description*</label>
                                <div class="col-sm-10">
                                    <textarea name="description" class="form-control" style="white-space: pre-line;" cols="100" rows="8"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Qualifications*</label>
                                <div class="col-sm-10">
                                    <textarea name="qualifications" class="form-control" style="white-space: pre-line;" cols="100" rows="8"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="education" class="col-sm-2 col-form-label">Education*</label>
                                <div class="col-sm-3">
                                    <select class="custom-select" name="education" id="education">
                                        <option>Choose Education</option>
                                        <option value="sd"> SD</option>
                                        <option value="smp"> SMP</option>
                                        <option value="sma"> SMA</option>
                                        <option value="d1"> Diploma 1</option>
                                        <option value="d2"> Diploma 2</option>
                                        <option value="d3"> Diploma 3</option>
                                        <option value="d4"> Diploma 4</option>
                                        <option value="sarjana"> Sarjana</option>
                                        <option value="magister"> Magister</option>
                                        <option value="doktor"> Doktor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Experienced*</label>
                                <div class="col-sm-3">
                                    <select class="custom-select" name="experienced" id="experienced">
                                        <option>Choose Experienced</option>
                                        <option value="0">Fresh Graduated</option>
                                        <option value="1"> 1 Year</option>
                                        <option value="2"> 2 Years</option>
                                        <option value="3"> 3 Years</option>
                                        <option value="4"> 4 Years</option>
                                        <option value="5"> 5 Years</option>
                                        <option value="6"> 6 Years</option>
                                        <option value="7"> 7 Years</option>
                                        <option value="8"> 8 Years</option>
                                        <option value="9"> 9 Years</option>
                                        <option value="10"> 10 Years</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Salary*</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" placeholder="Enter Salary" name="salary" id="salary">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Salary Type*</label>
                                <div class="col-sm-3">
                                    <select class="form-control custom-select" name="salary_type">
                                        <option value=""></option>
                                        <option value="hour">Hourly</option>
                                        <option value="day">Daily</option>
                                        <option value="week">Weekly</option>
                                        <option value="month">Monthly</option>
                                        <option value="project">Project</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Allowance</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Enter Allowance, Example : BPJS, Bonus" name="allowance" id="allowance">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Send to Email*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Enter Email" name="send_to_email" id="sent_to_email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Send to Whatsapp</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="Enter Whatsapp Number" name="send_to_wa" id="send_to_wa">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Active Until*</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" name="active_until" id="active_until">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position_name" class="col-sm-2 col-form-label">Status*</label>
                                <div class="col-sm-3 d-flex align-items-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="vacancy_status" value="published" checked>
                                        <label class="form-check-label">
                                            Published
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="vacancy_status" value="unpublished">
                                        <label class="form-check-label">
                                            Unpublished
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed-bottom">
            <div class="card bg-light border-radius-none">
                <div class="card-header">
                    <div class="container">
                        <button id="btn_submit_vacancy" class="btn btn-primary"><i data-feather="save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/js/admin/parttime/vacancy.js')}}" type="application/javascript" ></script>
@endsection
