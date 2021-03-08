@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Job Part Time</a></li>
                <li class="breadcrumb-item"><a href="{{url('/admin/par')}}">Job Part Time</a></li>
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