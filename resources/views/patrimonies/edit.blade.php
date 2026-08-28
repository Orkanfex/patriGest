@extends('layouts.default')
@section('page-title','Editar patrimonio Nº ' . $patrimony->code . ' em '. $environment->name )
    
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

    @include('patrimonies.parts.basic-details')
    <br>
    @include('patrimonies.parts.patrimony-image')

@endsection