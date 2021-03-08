@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item"><a href="{{url('/admin/par')}}">FAQ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add FAQ</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/part-time/faq')}}" class="btn btn-primary"><i data-feather="list"></i> List FAQ</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <strong>Add New FAQ</strong>
                    </div>
                    <div class="card-body">
                        <form id="form_new_faq" enctype="multipart/form-data" method="POST">
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Type*</label>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" value="candidate" type="radio" name="type" id="candidate" checked>
                                        <label class="form-check-label" for="candidate">
                                            For Candidate
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" value="employer" type="radio" name="type" id="employer">
                                        <label class="form-check-label" for="employer">
                                            For Employer
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Question*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Input Question" name="question" id="question">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Answer*</label>
                                <div class="col-sm-6">
                                    <textarea type="text" class="form-control" rows="5" placeholder="Input Answer" name="answer" id="answer"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed-bottom">
        <div class="card bg-light border-radius-none">
            <div class="card-header">
                <div class="container">
                    <button id="btn_submit_faq" class="btn btn-primary"><i data-feather="save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/js/admin/parttime/faq.js')}}" type="application/javascript" ></script>
    <script>
        init_data_table();
    </script>
@endsection