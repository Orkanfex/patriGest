@extends('layouts.default')
@section('page-title','Editar Usuário '. $user->name )
    
@section('content')
    @session('status')
        <div class="alert alert-success">
            {{ $value }}
        </div>
    @endsession
    
    @include('users.parts.basic-details')
    <br>
    @include('users.parts.profile')
    <br>
    @include('users.parts.interestes')
    <br>
    @include('users.parts.roles')
    
@endsection