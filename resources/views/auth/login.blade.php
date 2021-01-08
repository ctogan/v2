@extends('layouts.login')

@section('content')
<div class="container-login">
    <div class="overlay"></div>
    <div class="form-login">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <img class="mb-2" src="{{url('/assets/images/logo.png')}}" width="60px" >
                <h3 class="text-primary">CASHTREE ADMIN</h3>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class=" mb-1" for="inputEmailAddress">{{ __('E-Mail Address') }}</label>
                <input class="form-control py-4 @error('email') is-invalid @enderror"  name="email" type="email" placeholder="Enter email address" required autocomplete="email" autofocus />
                @error('email')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class=" mb-1" for="inputPassword">{{ __('Password') }}</label>
                <input class="form-control py-4 @error('password') is-invalid @enderror" type="password" placeholder="Enter password" name="password" required autocomplete="current-password"/>
                @error('password')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary btn-block">
                    {{ __('Login') }}
                </button>
            </div>
            <div class="form-group text-center justify-content-between mt-4 mb-0">
                <a class="small" href="/password/reset">Forgot Password?</a>
            </div>
        </form>
    </div>
</div>

<style>
    *{
        margin:0;
        padding:0;
        box-sizing: border-box;
    }
    .container-login{
        height: 100vh;
        display: flex;
        background: #5c2699a6 url("/assets/images/bg-login.jpg") no-repeat;
        background-size: cover;
    }
    .form-login{
        width: 450px;
        margin: auto;
        padding:30px;
        background-color: #ffffff;
        z-index: 1;
        border-radius:10px;
        -webkit-box-shadow: 0px 0px 6px 0px rgba(150,144,150,1);
        -moz-box-shadow: 0px 0px 6px 0px rgba(150,144,150,1);
        box-shadow: 0px 0px 6px 0px rgba(150,144,150,1);
    }
    .overlay{
        position: absolute;
        background-color: #9589a2a6;
        height: 100vh;
        width: 100%;
        z-index: 0;
    }
</style>
@endsection