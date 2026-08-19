@extends('layouts.default')
@section('page-title', $environment->name)

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('page-actions')
    @can('store', \App\Models\Patrimony::class)
        <a href="{{ route('patrimony.create', $environment->id) }}" class="btn btn-primary btn-sm">Adicionar Patrimonio</a>
    @endcan
@endsection



@section('content')
    @session('status')
        <div class="alert alert-success">
            {{ $value }}
        </div>
    @endsession
    
    {{--n @can precisa passar o contexto inteiro \App\Models\User::class  --}}
    {{-- @can passa a permissao destroy e o contexto para checar a permissão --}}

    <form action="{{ route('patrimonies.index', $environment->id) }}" method="GET" class="mb-3">
        <div class="input-group input-group-sm" style="width: 300px">

            <input 
                type="text" 
                name="keyword" 
                class="form-control" 
                placeholder="Pesqueise por nome ou email"
                value="{{ request()?->keyword }}"
            >
            <button type="submit" class="btn btn-primary">Pesquiar</button>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nº Patrimonio</th>
                <th scope="col">Descrição</th>
                <th scope="col">Estado</th>
                <th scope="col" class="text-center">Ação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($patrimonies as $patrimony)            
                <tr>
                    <th scope="row">{{ $patrimony->code }}</th>
                    <td>{{ Str::limit( $patrimony->description, 50)}}</td>
                    <td>{{ $patrimony->state->name }}</td>
                    <td>
                        <div class="row">
                            <div class="col text-center">
                                <a href="#" class="btn btn-primary btn-sm">Ver</a>
                            </div>
                            <div class="col text-center">
                                @can('edit', $patrimony)
                                    <a href="#" class="btn btn-primary btn-sm">Editar</a>
                                @endcan
                            </div>
                            <div class="col text-center">
                                @can('destroy', $patrimony) 
                                    <form action="#" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $patrimonies->links() }}
@endsection
