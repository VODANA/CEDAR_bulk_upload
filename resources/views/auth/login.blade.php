@extends('layouts.authent')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <h1>VODAN <span style="color:maroon">  Africa </span> </h1>
    </div>
    <div class="card">
        <div class="card-body login-card-body">

            @if (session('error'))
            <div class="text-danger text-center">{{ session('error') }}</div>
            @endif
            @if (session('success'))
            <div class="text-success text-center">{{ session('success') }}</div>
            @endif
            <p class="login-box-msg">Sign-In to start your session</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group mb-1">
                    <input name="email" type="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                @error('email')
                <div class="text-danger">{{$message}}</div>
                @enderror
                <div class="input-group mb-1">
                    <input name="password" type="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                @error('password')
                <div class="text-danger">{{$message}}</div>
                @enderror
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input name="remember" type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>
                        @if (Route::has('password.request'))
                        <a class="btn btn-warning" class="btn btn-link" href="{{ route('register') }}">
                            {{ __('Crete Account') }}
                        </a>
                        @endif
                        @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('register') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection