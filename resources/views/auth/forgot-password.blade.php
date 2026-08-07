@extends('layouts.auth')
@section('body-class', 'login-page')

@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="{{ route('login') }}"><b>Admin</b>LTE</a>
        </h1>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Recuperar Senha</p>

                @session('status')
                    <div class="alert alert-success" role="alert">
                        {{ $value }}
                    </div>
                @endsession
                <form action="{{ route('password.email') }}" method="post">
                    @csrf

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
                    <div class="row">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enviar-me o link</button>
                        </div>
                    </div>
                    <!--end::Row-->
                </form>
                <div class="mt-2 text-center">
                    <p class="mb-1">
                        <a href="{{ route('login') }}">Back to login</a>
                    </p>
                </div>
            </div>
            <!-- /.login-card-body -->
        </div>
    </main>
    <!-- /.login-box -->
@endsection