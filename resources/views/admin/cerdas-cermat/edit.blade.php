@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Cerdas Cermat</a></li>
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h4 class="m-0 text-secondary page-title">Add New Session</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{url('/admin/cerdas-cermat')}}" class="btn btn-primary"><i data-feather="list"></i>List Session</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body overflow-hidden">
                        <b>Basic Information</b>
                        <form action="{{url('/admin/cerdas-cermat/update')}}">
                            <input type="hidden" name="id" value="{{$session->id}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class="col-sm-3 col-form-label">Title*</label>
                                        <div class="col-sm-12">
                                            <input value="{{$session->title}}" type="text" class="form-control" placeholder="Input Title" name="title" id="title" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="company" class="col-sm-4 col-form-label">Registration Fee*</label>
                                        <div class="col-sm-12">
                                            <input type="number" value="{{$session->registration_fee}}"  class="form-control" placeholder="Input Point" name="registration_fee" id="registration_fee" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="company" class="col-sm-4 col-form-label">Open Date*</label>
                                        <div class="col-sm-12">
                                            <div class="d-flex">
                                                <input type="date" value="{{$session->open_date}}"  class="form-control mr-2" name="open_date" id="open_date" autocomplete="off">
                                                <input type="time" value="{{$session->time_start}}"  class="form-control mr-2" placeholder="Input Point" name="time_start" id="time_start" autocomplete="off">
                                                <input type="time" value="{{$session->time_end}}"  class="form-control" placeholder="Input Point" name="time_end" id="time_end" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="company" class="col-sm-4 col-form-label">TNC*</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" name="tnc" id="tnc" rows="3">{{$session->tnc}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="company" class="col-sm-4 col-form-label">Available For</label>
                                        <div class="col-sm-12">
                                            <div class="form-check">
                                                <input class="form-check-input" name="availability" type="radio" value="false" id="all" {{$session->is_tester ? '' : 'checked'}} >
                                                <label class="form-check-label" for="all">
                                                    All
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="availability" type="radio" value="true" id="tester" {{$session->is_tester ? 'checked' : ''}} >
                                                <label class="form-check-label" for="tester">
                                                    Tester Only
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4 pr-0">
                                                <label for="company" class="col-sm-12 col-form-label">Random Question*</label>
                                                <div class="col-sm-12">
                                                    <input value="{{$session->random_question}}"  type="number" class="form-control" name="random_question" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4 pl-0 pr-0">
                                                <label for="company" class="col-sm-12 col-form-label">Displayed Question*</label>
                                                <div class="col-sm-12">
                                                    <input value="{{$session->displayed_question}}" type="number" class="form-control" name="displayed_question">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="company" class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9 d-flex align-center">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="row_status" id="active" value="active" {{$session->row_status == 'active' ? 'checked' : ''}}>
                                                <label class="form-check-label" for="active">Active</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="row_status" id="inactive" value="inactive" {{$session->row_status == 'inactive' ? 'checked' : ''}}>
                                                <label class="form-check-label" for="inactive">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class="col-sm-4 col-form-label">Prize*</label>
                                        <div class="col-sm-12">
                                            <select id="selection-field" class="hide">
                                                <option value="">Choose</option>
                                                @foreach($products as $product)
                                                    <option class="text-muted" value="{{$product->id}}">{{$product->product_name}}</option>
                                                @endforeach
                                            </select>
                                            <table id="table_prize" class="table w-100">
                                                <thead>
                                                    <tr>
                                                        <th width="100">Rank</th>
                                                        <th>Prize Name</th>
                                                        <th class="text-center" width="20">
                                                            <a href="javascript:void(0)" id="add_prize" data-row="{{count($session->prize)}}" class="btn btn-outline-primary btn-sm">Add</a>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($session->prize as $i=>$v)
                                                        <tr>
                                                            <td>
                                                                <input type="number" class="form-control" value="{{$v->rank}}" name="prize[{{$i}}][rank]">
                                                                <input type="hidden" class="form-control" value="{{$v->id}}" name="prize[{{$i}}][id]">
                                                            </td>
                                                            <td>
                                                                <select class="form-control custom-select select2" name="prize[{{$i}}][item]">
                                                                    <option value="">Choose</option>
                                                                    @foreach($products as $product)
                                                                        <option class="text-muted" value="{{$product->id}}" {{$v->product_id == $product->id ? 'selected' : ''}}>{{$product->product_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td align="center" class="vertical-align-middle">
                                                                <a class="btn delete_prize_db" data-id="{{$v->id}}" href="javascript:void(0)"><i data-feather="trash"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col-md-12">
                                    <table id="table_question_generated" class="table table-hover table-striped w-100">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Question</th>
                                            <th>Image</th>
                                            <th>Level</th>
                                            <th>Answer Count</th>
                                            <th>Correct Answer</th>
                                            <th>View</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($session->question as $i=>$v)
                                            <tr>
                                                <td>{{$i+1}}</td>
                                                <td>{{$v->question->question}}</td>
                                                <td>
                                                    @if($v->question->question_image)
                                                        <img src="{{$v->question->question_image}}" width="100px">
                                                    @endif
                                                </td>
                                                <td>{{$v->question->question_level}}</td>
                                                <td align="center">{{count($v->question->answer)}}</td>
                                                <td>{{$v->question->correct_answer->answer}}</td>
                                                <td><a href="javascript:void(0)" data-id="{{$v->question->id}}" data-toggle="modal" data-target="#view_question_modal"><i data-feather="eye"></i></a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
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
    @include('admin.cerdas-cermat.question._view');
@endsection

@section('js')
    <script src="{{url('/js/admin/cerdascermat.js')}}"></script>
@endsection