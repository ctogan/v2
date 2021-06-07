@extends('layouts.webapp')
@section('css')
    <link rel="stylesheet" href="{{asset('/css/webapp/cerdas-cermat.css')}}" />
@endsection

@section('content')
    <input type="hidden" name="session_code" value="{{$session->session_code}}">
    <div id="cerdas-cermat"></div>
@endsection

@section('js')
    <script src="{{asset('/js/webapp/cerdascermatstart.js')}}"></script>
@endsection