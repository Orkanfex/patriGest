@extends('layouts.default')
@section('page-title', 'Adicionar Usuario')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('content')
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="pb-2">
            <h3>Dados Pessoais</h3>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nome Completo</label>
            <input 
                type="text" 
                name="name" 
                class="form-control @error('name') is-invalid @enderror" 
                id="name"
                value="{{ old('name') }}"
            >
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input 
                type="text" 
                name="email" 
                class="form-control @error('email') is-invalid @enderror" 
                id="email" 
                aria-describedby="emailHelp"
                value="{{ old('email') }}"
            >
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                name="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="password" 
            >
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="pb-2">
            <h3>Cargos</h3>
        </div>
        @foreach ($roles as $role)
            <div class="form-check">
                <input 
                    class="form-check-input @error('role_id') is-invalid @enderror" 
                    type="radio" 
                    name="role_id" 
                    id="radioDefault1"
                    value="{{ $role->id }}"
                    @checked(old('role_id'))
                >   
                <label class="form-check-label" for="{{ $role->name }}">
                    {{ $role->name }}
                </label>

                @if ($loop->last)
                    @error('role_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                @endif
            </div>
        @endforeach

        <br>

        <div class="pt-2">
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </div>
    </form>

@endsection