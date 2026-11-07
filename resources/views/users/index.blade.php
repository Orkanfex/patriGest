@extends('layouts.default')
@section('page-title', 'Usuarios')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('page-actions')
    @can('create', \App\Models\User::class)
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Adicionar</a>
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

    <form action="{{ route('users.index') }}" method="GET" class="mb-3">
        <div class="d-flex gap-2">
            <div class="col input-group input-group-sm">
    
                <input 
                    type="text" 
                    name="keyword" 
                    class="form-control" 
                    placeholder="Pesqueise por nome ou email"
                    value="{{ request()?->keyword }}"
                >
                <button type="submit" class="btn btn-primary">Pesquiar</button>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col" class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)            
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <div class="row">
                                @can('edit', $user)
                                    <div class="col text-center mb-2">
    
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm w-100 w-md-auto">Editar</a>
                                    </div>
                                @endcan
                                @can('destroy', $user) 
                                    <div class="col text-center mb-2">
                                        <form action="{{ route('users.destroy', $user->id) }}" 
                                            method="POST" 
                                            class="form-delete-confirm d-inline"
                                            data-label="o usuário {{ $user->name }}"
                                            data-photo="{{ $user->avatar ? asset('storage/' . $user->avatar) : Vite::asset('resources/images/user-default.png') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- caixa de dialogo para confirmação de exclusao de usuário --}}
        <dialog id="delete-confirmation-dialog" class="p-4 rounded-4 shadow-lg border-0" style="max-width: 400px; backdrop-filter: blur(4px);">
            <div class="text-center">
                <!-- Elemento de Imagem / Avatar para Pré-visualização -->
                <div id="dialog-photo-container" class="mb-3 d-none">
                    <img id="dialog-photo" src="" alt="Foto / Avatar" class="img-fluid rounded-circle border shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                </div>

                <h5 class="fw-bold mb-2 text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Confirmar Exclusão
                </h5>
                
                <p id="dialog-message" class="text-muted mb-4">
                    Tem certeza que deseja excluir este registro?
                </p>
                
                <div class="d-flex justify-content-center gap-2">
                    <button id="btn-dialog-cancel" type="button" class="btn btn-secondary btn-sm px-4">Cancelar</button>
                    <button id="btn-dialog-confirm" type="button" class="btn btn-danger btn-sm px-4">Excluir</button>
                </div>
            </div>
        </dialog>
    </div>
    

    {{ $users->links() }}
@endsection