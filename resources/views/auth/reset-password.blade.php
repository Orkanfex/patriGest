@extends('layouts.auth')
@section('body-class','register-page')
    
@section('content')
<main class="register-box">
    <h1 class="register-logo">
        <a href="{{ route('login') }}"><b>Admin</b>LTE</a>
    </h1>
    <!-- /.register-logo -->
    <div class="card">
        <div class="card-body register-card-body">
            <p class="register-box-msg">Resete sua senha aqui!</p>

            <form action="{{ route('password.update') }}" method="post">
                @csrf

                <input type="hidden" name="token" value="{{ request()->token }}">
                <label class="visually-hidden" for="registerEmail">Email</label>
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <span class="bi bi-envelope"></span>
                    </div>
                    <input 
                        id="registerEmail" 
                        name="email" 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="Email" 
                        value="{{ request()->email }}"
                    />
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <label class="visually-hidden" for="registerPassword">Password</label>
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <span class="bi bi-lock-fill"></span>
                    </div>
                    <input
                        id="registerPassword"
                        name="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password"
                    />
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <label class="visually-hidden" for="registerPassword">Password confirmation</label>
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <span class="bi bi-lock-fill"></span>
                    </div>
                    <input
                        id="registerPassword"
                        name="password_confirmation"
                        type="password"
                        class="form-control"
                        placeholder="Password confirmation"
                    />
                </div>
                    
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
        <!-- /.register-card-body -->
    </div>
</main>
<!-- /.register-box -->
@endsection