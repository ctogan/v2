@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item active" aria-current="page">FAQ</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/part-time/faq/add')}}" class="btn btn-primary"><i data-feather="plus"></i> New FAQ</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <strong>List Of FAQ</strong>
                    </div>
                    <div class="card-body">
                        <hr/>
                        <table id="dt_faq" class="datatable table table-striped table-bordered nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Type</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th data-priority="1">Action</th>
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
    </div>
@endsection

@section('js')
    <script src="{{url('/js/admin/parttime/faq.js')}}" type="application/javascript" ></script>
    <script>
        init_data_table();
    </script>
@endsection