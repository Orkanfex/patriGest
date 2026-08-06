@extends('layouts.auth')
@section('body-class','register-page')
    
@section('content')
<main class="register-box">
    <h1 class="register-logo">
        <a href="../index2.html"><b>Admin</b>LTE</a>
    </h1>
    <!-- /.register-logo -->
    <div class="card">
        <div class="card-body register-card-body">
            <p class="register-box-msg">Register a new membership</p>

            <form action="{{ route('register') }}" method="post">
                @csrf
                <label class="visually-hidden" for="registerName">Full Name</label>
                <div class="input-group mb-3">
                    <input id="registerName" name="name" type="text" class="form-control" placeholder="Full Name" />
                    <div class="input-group-text">
                        <span class="bi bi-person"></span>
                    </div>
                </div>
                <label class="visually-hidden" for="registerEmail">Email</label>
                <div class="input-group mb-3">
                    <input id="registerEmail" name="email" type="email" class="form-control" placeholder="Email" />
                    <div class="input-group-text">
                        <span class="bi bi-envelope"></span>
                    </div>
                </div>
                <label class="visually-hidden" for="registerPassword">Password</label>
                <div class="input-group mb-3">
                    <input
                    id="registerPassword"
                    name="password"
                    type="password"
                    class="form-control"
                    placeholder="Password"
                    />
                    <div class="input-group-text">
                        <span class="bi bi-lock-fill"></span>
                    </div>
                </div>

                <label class="visually-hidden" for="registerPassword">Password confirmation</label>
                <div class="input-group mb-3">
                    <input
                    id="registerPassword"
                    name="password_confirmation"
                    type="password"
                    class="form-control"
                    placeholder="Password confirmation"
                    />
                    <div class="input-group-text">
                        <span class="bi bi-lock-fill"></span>
                    </div>
                </div>
                    
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>

            <p class="mb-0 text-center" >
                <a href="login.html" class="text-center"> I already have a membership </a>
            </p>
        </div>
        <!-- /.register-card-body -->
    </div>
</main>
<!-- /.register-box -->
@endsection