@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                @if($vacancy)
                    <li class="breadcrumb-item"><a href="{{url('/admin/part-time/vacancy/'.$vacancy->id)}}">{{$vacancy->position_name}}</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/admin/part-time/company/edit/'.$vacancy->company_id)}}">{{$vacancy->company_name}}</a></li>
                @else
                    <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">Applicant</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <input type="hidden" id="vacancy_id" value="{{$vacancy_id}}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Applicant</strong>
                    </div>
                    <div class="card-body overflow-hidden">
                        <table id="dt_applicant" class="datatable table table-striped table-bordered responsive nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Photo</th>
                                <th>UID</th>
                                <th>Name</th>
                                <th>Company Name</th>
                                <th>Vacancy Name</th>
                                <th>Category</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Applied At</th>
                                <th>Place Of Birth</th>
                                <th>Date Of Birth</th>
                                <th>Sex</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Weight</th>
                                <th>Height</th>
                                <th>Religion</th>
                                <th>Education</th>
                                <th>Skills</th>
                                <th>Hobby</th>
                                <th>Address</th>
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
    <script src="{{url('/js/admin/parttime/applicant.js')}}" type="application/javascript" ></script>
    <script>
        init_data_table();
    </script>
@endsection