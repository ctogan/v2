@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item">Company Category</li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12 text-right">
                <a href="{{url('/admin/master/province')}}" class="btn btn-primary"><i data-feather="list"></i> List Category</a>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        New Province
                    </div>
                    <div class="card-body">
                        <form id="form_new_company_category" enctype="multipart/form-data" method="POST">
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Category Name*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Input Category Name" name="category_name" id="category_name">
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
                        <button id="btn_submit_company_category" class="btn btn-primary"><i data-feather="save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/js/master/company_category.js')}}" type="application/javascript" ></script>
@endsection