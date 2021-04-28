@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Operation</a></li>
                <li class="breadcrumb-item"><a href="#">Setting</a></li>
                <li class="breadcrumb-item active" aria-current="page">Theme</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h4 class="m-0 text-secondary page-title">THEME SETTING</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body overflow-hidden">
                        <div class="row">
                            <div class="col-md-6">
                               <div class="app-layout">
                                   <div class="background" style="background-color: {{$background_color->setting_value_full}}">
                                       <img id="background_image_app" src="{{$background_image->setting_value_full}}" alt="">
                                   </div>
                                   <div class="top-layout">
                                       <div class="d-flex align-items-center">
                                           <div class="mr-2">
                                               <div class="profile-image"></div>
                                           </div>
                                           <div class="flex-1">
                                               <p class="m-0 p-0 point-1">15.000</p>
                                               <p class="m-0 p-0 point-2">500</p>
                                           </div>
                                           <div class="text-right">
                                               <i data-feather="bell" style="width: 50px"></i>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="banner"></div>
                                   <ul id="default" class="hide">
                                       <li data-name="categories" data-position="1" class="item">
                                           <ul class="categories">
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                           </ul>
                                       </li>
                                       <li data-name="unfinished" data-position="1" class="item">
                                           <p class="mb-2">Ayo, selesaikan misimu!</p>
                                           <ul class="unfinished">
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                           </ul>
                                       </li>
                                       <li data-name="flash_event" data-position="1" class="item">
                                           <p class="mb-2">Flash Event</p>
                                           <ul class="flash-event">
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                           </ul>
                                       </li>
                                       <li data-name="dynamic" data-position="1" class="item">
                                           <p class="mb-2">Dynamic State</p>
                                           <ul class="dynamic">
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                               <li></li>
                                           </ul>
                                       </li>
                                       <li data-name="news" data-position="1" class="item">
                                           <p class="mb-2">News</p>
                                           <ul class="news">
                                               <li>
                                                   <div class="d-flex">
                                                       <div class="image"></div>
                                                       <div>
                                                           Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae, minus.
                                                       </div>
                                                   </div>
                                               </li>
                                               <li>
                                                   <div class="d-flex">
                                                       <div class="image"></div>
                                                       <div>
                                                           Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae, minus.
                                                       </div>
                                                   </div>
                                               </li>
                                               <li>
                                                   <div class="d-flex">
                                                       <div class="image"></div>
                                                       <div>
                                                           Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae, minus.
                                                       </div>
                                                   </div>
                                               </li>
                                           </ul>
                                       </li>
                                   </ul>
                                   <ul id="sortable" class="home-layout">
                                       {!! $layout_content->setting_value_full !!}
                                   </ul>
                               </div>
                            </div>
                            <div class="col-md-6">
                                <form method="POST" id="form_theme_update" action="{{url('/admin/operation/theme/update')}}">
                                    <input type="hidden" name="layout_content" value="">
                                    @foreach($layout as $item)
                                        @if($item->page_name == 'categories')
                                            <input type="hidden" name="categories" value="{{$item->sequence}}">
                                        @elseif($item->page_name == 'flash_event')
                                            <input type="hidden" name="flash_event" value="{{$item->sequence}}">
                                        @elseif($item->page_name == 'unfinished')
                                            <input type="hidden" name="unfinished" value="{{$item->sequence}}">
                                        @elseif($item->page_name == 'dynamic')
                                            <input type="hidden" name="dynamic" value="{{$item->sequence}}">
                                        @elseif($item->page_name == 'news')
                                            <input type="hidden" name="news" value="{{$item->sequence}}">
                                        @endif
                                    @endforeach

                                    <div class="form-group">
                                        <label for="company" class="col-sm-5 col-form-label">Background Color</label>
                                        <div class="col-sm-9">
                                            <div class="d-flex">
                                                <div class="color-preview">
                                                    <input type="color" id="works2" value="{{$background_color->setting_value_full}}" onchange="change_color(this.value);" />
                                                </div>
                                                <input type="text" value="{{$background_color->setting_value_full}}" class="form-control" placeholder="#543453" name="background_color" id="background_color">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="company" class="col-sm-5 col-form-label">Background Image</label>
                                        <div class="col-sm-9">
                                            <div class="d-block">
                                                <div class="preview-img-rectangle mb-2">
                                                    <img class="preview" onclick="document.getElementById('background_image').click();" id="preview_background_image" src="{{$background_image->setting_value_full}}" width="100%">
                                                </div>
                                            </div>
                                            <div class="d-block">
                                                <div class="custom-file hide">
                                                    <input type="file" name="background_image" onchange="read_url_theme(event)" class="custom-file-input" id="background_image">
                                                    <label class="custom-file-label" for="img">Upload Image</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-9">
                                        <button id="btn_update_theme" class="btn btn-primary btn-submit mr-2" type="button">
                                            <span class="text">UPDATE</span>
                                            <span class="show-loading">
                                                <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading
                                            </span>
                                        </button>
                                        <button id="btn_restore_default" class="btn btn-outline-primary" type="button">Restore Default</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="{{url('/js/admin/operation/category.js')}}"></script>
    <script>
        $( function() {
            $( "#sortable").sortable().disableSelection();

            $("#btn_update_theme").click(function () {
                var ul = document.getElementById("sortable");
                var items = ul.getElementsByClassName("item");
                for (var i = 0; i < items.length; ++i) {
                    if(items[i].getAttribute('data-name') !== null){
                        $('input[name='+items[i].getAttribute('data-name')+']').val(i);
                    }
                }
                $('input[name=layout_content]').val($("#sortable").html());

                submit($("#form_theme_update"));
            });

            $("#btn_restore_default").click(function () {
                $("#sortable").html($("#default").html())
            });
        });
        function change_color(color) {
            $("#background_color").val(color);
            $(".app-layout .background").css('background-color',color);
        }
    </script>
@endsection