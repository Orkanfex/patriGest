@extends('layouts.default')
@section('page-title', 'Ambientes')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('page-actions')
    {{-- @can('create', \App\Models\Environment::class) --}}
        {{-- <a href="{{ route('environments.create') }}" class="btn btn-primary btn-sm">Adicionar Ambiente</a> --}}
    {{-- @endcan --}}
@endsection

@section('content')
    @session('status')
        <div class="alert alert-success">
            {{ $value }}
        </div>
    @endsession

    <form action="{{ route('environments.index') }}" method="GET" class="mb-3">
        <div class="col input-group input-group-sm">
            <input 
                type="text" 
                id="environment_search"
                name="keyword" 
                class="form-control" 
                placeholder="Pesquise por nome ou descrição do ambiente"
                value="{{ request()?->keyword }}"
                data-auto-submit="true"
            >
            <button type="submit" class="btn btn-primary">Pesquisar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Responsáveis</th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($environments as $environment)            
                    <tr>
                        <th scope="row">{{ $environment->name }}</th>
                        
                        {{-- Coluna de Responsáveis (Exibindo apenas o primeiro nome) --}}
                        <td>
                            @if($environment->users->isNotEmpty())
                                {{ $environment->users->map(fn($user) => Str::before($user->name, ' '))->implode(', ') }}
                            @else
                                <span class="text-muted fst-italic">Nenhum responsável</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('patrimonies.index', $environment->id) }}" class="btn btn-primary btn-sm">
                                    Ver Patrimônios
                                </a>

                                {{-- @can('update', $environment)
                                    <a href="{{ route('environments.edit', $environment->id) }}" class="btn btn-primary btn-sm">
                                        Editar
                                    </a>
                                @endcan --}}

                                {{-- @can('delete', $environment) 
                                    <form action="{{ route('environments.destroy', $environment->id) }}" 
                                        method="POST" 
                                        class="form-delete-patrimony"
                                        data-label="{{ $environment->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                @endcan --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            Nenhum ambiente encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Dialog de Confirmação Neutro (Limpo e fora de condicionais do Eloquent) -->
    <dialog id="delete-confirmation-dialog" class="p-4 rounded shadow-lg border-0" style="max-width: 400px; margin: auto;">
        <div class="text-center">
            <!-- Elemento de Imagem para Pré-visualização (Injetado dinamicamente via JS se houver) -->
            <div id="dialog-photo-container" class="mb-3 d-none">
                <img id="dialog-photo" src="" alt="Foto do Item" class="img-fluid rounded border" style="max-height: 180px; object-fit: cover;">
            </div>

            <h5 class="mb-2">Confirmar Exclusão</h5>
            <p id="dialog-message" class="text-muted mb-4">Tem certeza que deseja excluir este item?</p>
            
            <div class="d-flex justify-content-center gap-2">
                <button id="btn-dialog-cancel" type="button" class="btn btn-secondary btn-sm px-4">Não</button>
                <button id="btn-dialog-confirm" type="button" class="btn btn-danger btn-sm px-4">Sim</button>
            </div>
        </div>
    </dialog>
@endsection