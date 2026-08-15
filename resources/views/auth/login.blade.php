@extends('layouts.auth')
@section('body-class', 'login-page')

@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="{{ route('login') }}"><b>Patri</b>GEST</a>
        </h1>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Inicie sua sessão</p>

                <form action="{{ route('login') }}" method="post">
                    @csrf

                    @session('status')
                        <div class="alert alert-success" role="alert">
                            {{ $value }}
                        </div>
                    @endsession
                    <label class="visually-hidden" for="loginEmail">Email</label>
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        <input 
                            id="loginEmail" 
                            type="email" 
                            name="email"
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
                    <label class="visually-hidden" for="loginPassword">Password</label>
                        <div class="input-group mb-3">
                            <div class="input-group-text">
                                <span class="bi bi-lock-fill"></span>
                            </div>
                            <input
                                id="loginPassword"
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
                    <!--begin::Row-->
                    <div class="row">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </div>
                    <!--end::Row-->
                </form>
                <div class="mt-2 text-center">
                    <p class="mb-1">
                        <a href="{{ route('password.request') }}">Esqueci minha senha</a>
                    </p>
                    <p class="mb-0">
                        <a href="{{ route('register') }}" class="text-center"> Register a new membership </a>
                    </p>
                </div>
            </div>
            <!-- /.login-card-body -->
        </div>
    </main>
    <!-- /.login-box -->
@endsection