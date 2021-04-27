@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{url('/admin/flash-event')}}">Flash Event</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h4 class="m-0 text-secondary page-title">ADD FLASH EVENT</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{url('/admin/flash-event')}}" class="btn btn-primary"><i data-feather="list" class="mr-2"></i>List Flash Event</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <form action="{{url('/admin/flash-event/submit')}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="event_name">Event Name</label>
                                            <input type="text" class="form-control" name="event_name" aria-describedby="event_name" placeholder="Enter Event Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="event_name">Event Description</label>
                                            <textarea type="text" class="form-control" name="event_description" rows="4" aria-describedby="event_description" placeholder="Enter Event Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="event_period">Period</label>
                                                    <select class="form-control custom-select" name="event_period" id="period">
                                                        <option value="daily">Daily</option>
                                                        <option value="weekly">Weekly</option>
                                                        <option value="special_date">Special</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="special_date" class="col-md-4 hide pl-0 pr-0">
                                                <div class="form-group">
                                                    <label for="event_date">Date</label>
                                                    <input type="input" name="event_date" class="form-control">
                                                </div>
                                            </div>
                                            <div id="weekly" class="col-md-3 hide pl-0 pr-0">
                                                <div class="form-group">
                                                    <label for="day_name">Day</label>
                                                    <select class="form-control custom-select" name="day_name" id="day_name">
                                                        <option value="sunday">Sunday</option>
                                                        <option value="monday">Monday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="event_name">Time</label>
                                                    <div class="d-flex align-items-center">
                                                        <input type="time" name="time_from" class="form-control mr-2">
                                                        <input type="time" name="time_to" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="event_name">Image Banner</label>
                                        <div class="form-group">
                                            <div class="preview-img-flash" onclick="document.getElementById('img_flash').click();">
                                                <img id="preview_img_flash" src="{{url('/assets/images/default2.png')}}" width="100%">
                                                <input type="file" name="event_img" onchange="read_url2(event)" class="custom-file-input hide" id="img_flash">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 mb-3">
                                        <fieldset>
                                            <legend>User Targeting</legend>
                                            <div>
                                                <div class="form-check mb-2">
                                                    <input type="hidden" value="false" name="ut_by_register_date">
                                                    <input class="form-check-input" name="is_registered_date" type="checkbox" id="is_registered_date">
                                                    <label class="form-check-label" for="is_registered_date">
                                                        By Registered Date
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="target_registered" class="form-control w-50" disabled>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="form-check mb-2">
                                                    <input type="hidden" value="false" name="ut_by_point_count">
                                                    <input class="form-check-input" name="is_point_count" type="checkbox" id="is_point_count">
                                                    <label class="form-check-label" for="is_point_count">
                                                        By Point Count
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center">
                                                        <input type="number" name="target_point_from" class="form-control mr-2" value="0" disabled>
                                                        <span class="mr-2">to</span>
                                                        <input type="number" name="target_point_to" class="form-control" value="0" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="event_tnc">TNC</label>
                                        <textarea name="event_tnc" type="text" class="form-control" rows="10" aria-describedby="event_tnc" placeholder="Enter Event TNC"></textarea>
                                    </div>
                                    <div class="col-md-12">
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
                            </div>

                            <div class="row">
                                <div class="col-md-12 pt-3 pb-2 mb-3">
                                    <button data-row="1" type="button" id="btn_add_product" class="btn btn-primary float-right"><i data-feather="plus" class="mr-1"></i>Add Product</button>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <select id="selection-field" class="hide">
                                        @foreach($products as $product)
                                            <option class="text-muted" value="{{$product->id}}">{{$product->product_name}}</option>
                                        @endforeach
                                    </select>
                                    <table id="table_flash_product" class="table table-hover w-100">
                                        <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th width="200">Point</th>
                                            <th width="200">Cap</th>
                                            <th width="100" class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control custom-select select2" name="detail[0][product_id]">
                                                    @foreach($products as $product)
                                                        <option class="text-muted" value="{{$product->id}}">{{$product->product_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" placeholder="Enter Point Count" name="detail[0][point]">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" placeholder="Enter Cap Value" name="detail[0][cap]">
                                            </td>
                                            <td align="center">
                                                <a class="btn delete_field" href="javascript:void(0)"><i data-feather="trash"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group pl-2">
                                        <button id="btn_submit_news" class="btn btn-primary btn-submit" type="submit">
                                            <span class="text">SUBMIT</span>
                                            <span class="show-loading">
                                                <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Please Wait ...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{url('/js/admin/flashevent.js')}}"></script>
    <script>
        $("input[name=event_date]").daterangepicker();
    </script>
@endsection