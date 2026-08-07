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
            <p class="register-box-msg">Register a new membership</p>

            <form action="{{ route('register') }}" method="post">
                @csrf
                <label class="visually-hidden" for="registerName">Full Name</label>
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <span class="bi bi-person"></span>
                    </div>
                    <input 
                        id="registerName" 
                        name="name" 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        placeholder="Full Name" 
                        value="{{ old('name') }}"
                    />
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
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
                        value="{{ old('email') }}"
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
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>

            <p class="mb-0 mt-2 text-center" >
                <a href="{{ route('login') }}" class="text-center"> Back to login </a>
            </p>
        </div>
        <!-- /.register-card-body -->
    </div>
</main>
<!-- /.register-box -->
@endsection