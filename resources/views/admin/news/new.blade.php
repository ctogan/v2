@extends('layouts.app')

@section('css')
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{url('/admin/news')}}">News</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12 mt-3">
                <div class="card pt-2 pb-2 pl-5 pr-5">
                    <div class="row">
                        <form method="POST" id="form_news" class="w-100" action="{{url('/admin/news/submit')}}">
                            <div class="d-flex w-100">
                                <div style="width: 70%;max-width: 70%">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-form-label">Title</label>
                                            <input class="form-control form-control-lg" name="title" type="text" placeholder="Title" autocomplete="off">
                                        </div>
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-form-label">Category</label>
                                            <select name="category" class="form-control form-control-lg">
                                                <option>Category</option>
                                            </select>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label">Body</label>
                                            <input type="hidden" name="body_content" id="hdn_body_content">
                                            <div id="body_content" style="height: 500px" class="w-100">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-4">
                                            <button id="btn_submit_news" class="btn btn-primary btn-submit" type="button">
                                                <span class="text">POST</span>
                                                <span class="show-loading">
                                                    <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Posting ...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-5 pr-3">
                                    <div class="form-group">
                                        <label for="company" class="col-form-label">Image Banner</label>
                                        <div>
                                            <div class="d-block">
                                                <div class="preview-img-rectangle mb-2" style="max-width: 300px">
                                                    <img id="preview_img_news" src="{{url('/assets/images/default.png')}}" width="300">
                                                </div>
                                            </div>
                                            <div class="d-block">
                                                <div class="custom-file">
                                                    <input type="file" name="url_to_image" onchange="read_url(event)" class="custom-file-input" id="img_news">
                                                    <label class="custom-file-label" for="img">Upload Image</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="company" class="col-form-label">Author</label>
                                        <input class="form-control form-control" value="{{\Illuminate\Support\Facades\Auth::user()->name}}" name="author" type="text" placeholder="Title" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="company" class="col-form-label">Publish For</label>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" name="publish_for" type="checkbox" value="cashtree_app" id="cashtree_app" checked>
                                            <label class="form-check-label" for="cashtree_app">
                                                Cashtree App
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" name="publish_for" type="checkbox" value="cashtree_web" id="cashtree_web" disabled>
                                            <label class="form-check-label" for="cashtree_web">
                                                Cashtree Web
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="company" class="col-form-label">Available For</label>
                                        <div class="form-check">
                                            <input class="form-check-input" name="availability" type="radio" value="false" id="all" checked>
                                            <label class="form-check-label" for="all">
                                                All
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" name="availability" type="radio" value="true" id="tester">
                                            <label class="form-check-label" for="tester">
                                                Tester Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="fixed-bottom">--}}
            {{--<div class="card bg-light border-radius-none">--}}
                {{--<div class="card-header">--}}
                    {{--<div class="container">--}}
                        {{--<button id="btn_submit_vacancy" class="btn btn-primary"><i data-feather="save"></i> Save</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
@endsection

@section('js')
    <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="{{url('/js/admin/news.js')}}"></script>
    <script>
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'],

            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction

            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'align': [] }],
            ['link'],
            ['image'],
            ['clean']                                       // remove formatting button
        ];

        var quill = new Quill('#body_content', {
            modules: {
                toolbar: toolbarOptions
            },
            theme: 'snow'
        });
    </script>
@endsection