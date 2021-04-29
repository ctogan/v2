@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Operation</a></li>
                <li class="breadcrumb-item"><a href="#">Setting</a></li>
                <li class="breadcrumb-item active" aria-current="page">Category</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h4 class="m-0 text-secondary page-title">CATEGORIES</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#add_new_category"><i data-feather="plus"></i>Add New</a>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <strong>List Of Banner</strong>
                    </div>
                    <div class="card-body overflow-hidden">
                        <table id="dt_category_app" class="datatable table table-striped table-bordered responsive nowrap table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>Index</th>
                                <th width="200">Image</th>
                                <th>Category Name (Eng)</th>
                                <th>Category Name (Ina)</th>
                                <th>Deeplink</th>
                                <th>Status</th>
                                <th>Action</th>
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

    @include('admin.operation.setting.category._add')
    @include('admin.operation.setting.category._edit')
@endsection

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="{{url('/js/admin/operation/category.js')}}"></script>
@endsection