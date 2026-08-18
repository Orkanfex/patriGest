@extends('layouts.default')
@section('page-title','Editar Usuário '. $user->name )
    
@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('content')
    @session('status')
        <div class="alert alert-success">
            {{ $value }}
        </div>
    @endsession
    @include('users.parts.profile-photo')
    <br>
    @include('users.parts.basic-details')
    <br>
    @include('users.parts.roles')
    
@endsection