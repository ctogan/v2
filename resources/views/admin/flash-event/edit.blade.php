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
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                        <form action="{{url('/admin/flash-event/update')}}">
                            <input type="hidden" name="id" value="{{$flash_event->id}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="event_name">Event Name</label>
                                            <input type="text" class="form-control" name="event_name" aria-describedby="event_name" value="{{$flash_event->event_name}}" placeholder="Enter Event Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="event_name">Event Description</label>
                                            <textarea type="text" class="form-control" name="event_description" rows="4" aria-describedby="event_description" placeholder="Enter Event Description">{{$flash_event->event_description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="event_period">Period</label>
                                                    <select class="form-control custom-select" name="event_period" id="period">
                                                        <option value="daily" {{$flash_event->event_period == 'daily' ? 'selected' : ''}}>Daily</option>
                                                        <option value="weekly" {{$flash_event->event_period == 'weekly' ? 'selected' : ''}}>Weekly</option>
                                                        <option value="special_date" {{$flash_event->event_period == 'special_date' ? 'selected' : ''}}>Special</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="special_date" class="col-md-4 {{$flash_event->event_period == 'special_date' ? '' : 'hide'}} pl-0 pr-0">
                                                <div class="form-group">
                                                    <label for="event_date">Date</label>
                                                    <input type="input" name="event_date" class="form-control" value="{{date('d/m/Y', strtotime($flash_event->date_from)) .' - '. date('d/m/Y', strtotime($flash_event->date_to))}}">
                                                </div>
                                            </div>
                                            <div id="weekly" class="col-md-3 {{$flash_event->event_period == 'weekly' ? '' : 'hide'}} pl-0 pr-0">
                                                <div class="form-group">
                                                    <label for="day_name">Day</label>
                                                    <select class="form-control custom-select" name="day_name" id="day_name">
                                                        <option value="sunday" {{$flash_event->day_name == 'sunday' ? 'selected' : ''}}>Sunday</option>
                                                        <option value="monday" {{$flash_event->day_name == 'monday' ? 'selected' : ''}}>Monday</option>
                                                        <option value="thursday" {{$flash_event->day_name == 'thursday' ? 'selected' : ''}}>Thursday</option>
                                                        <option value="wednesday" {{$flash_event->day_name == 'wednesday' ? 'selected' : ''}}>Wednesday</option>
                                                        <option value="friday" {{$flash_event->day_name == 'friday' ? 'selected' : ''}}>Friday</option>
                                                        <option value="saturday" {{$flash_event->day_name == 'saturday' ? 'selected' : ''}}>Saturday</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="event_name">Time</label>
                                                    <div class="d-flex align-items-center">
                                                        <input type="time" name="time_from" value="{{$flash_event->time_from}}" class="form-control mr-2">
                                                        <input type="time" name="time_to" value="{{$flash_event->time_to}}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="event_name">Image Banner</label>
                                        <div class="form-group">
                                            <div class="preview-img-flash" onclick="document.getElementById('img_flash').click();">
                                                <img id="preview_img_flash" src="{{$flash_event->event_img}}" width="100%">
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
                                                    <input type="hidden" value="{{$flash_event->ut_by_register_date == 1 ? 'true' : 'false'}}" name="ut_by_register_date" id="ut_by_register_date">
                                                    <input class="form-check-input" name="is_registered_date" type="checkbox" id="is_registered_date" {{$flash_event->ut_by_register_date ? 'checked':''}}>
                                                    <label class="form-check-label" for="is_registered_date">
                                                        By Registered Date
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="target_registered" value="{{date('d/m/Y', strtotime($flash_event->registered_from)) .' - '. date('d/m/Y', strtotime($flash_event->registered_to))}}" class="form-control w-50" {{$flash_event->ut_by_register_date ? '' : 'disabled'}}>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="form-check mb-2">
                                                    <input type="hidden" value="{{$flash_event->ut_by_point_count == 1 ? 'true' : 'false'}}" name="ut_by_point_count" id="ut_by_point_count">
                                                    <input class="form-check-input" name="is_point_count" type="checkbox" id="is_point_count"  {{$flash_event->ut_by_point_count ? 'checked':''}}>
                                                    <label class="form-check-label" for="is_point_count">
                                                        By Point Count
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center">
                                                        <input type="number" name="target_point_from" class="form-control mr-2" value="{{$flash_event->target_point_from}}" {{$flash_event->ut_by_point_count ?  '' : 'disabled'}}>
                                                        <span class="mr-2">to</span>
                                                        <input type="number" name="target_point_to" class="form-control" value="{{$flash_event->target_point_to}}" {{$flash_event->ut_by_point_count ? '' : 'disabled'}}>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="event_tnc">TNC</label>
                                        <textarea name="event_tnc" type="text" class="form-control" rows="10" aria-describedby="event_tnc" placeholder="Enter Event TNC">{{$flash_event->event_tnc}}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="company" class="col-form-label">Available For</label>
                                            <div class="form-check">
                                                <input class="form-check-input" name="availability" type="radio" value="false" id="all" {{$flash_event->is_tester === false ? 'checked' : ''}}>
                                                <label class="form-check-label" for="all">
                                                    All
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="availability" type="radio" value="true" id="tester" {{$flash_event->is_tester === true ? 'checked' : ''}}>
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
                                    <button data-row="{{count($flash_event->detail)}}" type="button" id="btn_add_product" class="btn btn-primary float-right"><i data-feather="plus" class="mr-1"></i>Add Product</button>
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
                                        @foreach($flash_event->detail as $key => $item)
                                            <tr>
                                                <td>
                                                    <input type="hidden" value="{{$item->flash_detail_code}}" name="detail[{{$key}}][flash_detail_code]">
                                                    <select class="form-control custom-select select2" name="detail[{{$key}}][product_id]">
                                                        @foreach($products as $product)
                                                            <option class="text-muted" value="{{$product->id}}" {{$item->product_id == $product->id ? 'selected' : ''}}>{{$product->product_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" placeholder="Enter Point Count" value="{{$item->point}}" name="detail[{{$key}}][point]">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" placeholder="Enter Cap Value" value="{{$item->cap}}" name="detail[{{$key}}][cap]">
                                                </td>
                                                <td align="center">
                                                    <a data-code="{{$item->flash_detail_code}}" class="btn delete_from_db" href="javascript:void(0)"><i data-feather="trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group pl-2">
                                        <button id="btn_submit_news" class="btn btn-primary btn-submit" type="submit">
                                            <span class="text">UPDATE</span>
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
        $("input[name=event_date]").daterangepicker(
            {
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            }
        );
        $('input[name="event_date"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
        $("input[name=target_registered]").daterangepicker(
            {
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            }
        );
        $('input[name="target_registered"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
    </script>
@endsection