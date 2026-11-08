@extends('layouts.default')
@section('page-title', 'Ambientes')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('page-actions')
    @can('create', \App\Models\Environment::class)
        <a href="{{ route('environments.create') }}" class="btn btn-primary btn-sm">Adicionar Ambiente</a>
    @endcan
@endsection

@section('content')
    {{-- Alertas de Mensagens --}}
    @session('status')
        <div class="alert alert-success">
            {{ $value }}
        </div>
    @endsession

    @session('error')
        <div class="alert alert-danger">
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
                placeholder="Pesquise por nome do ambiente ou responsável"
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
                                <span class="text-body-secondary fst-italic">Nenhum responsável</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('environments.patrimonies.index', $environment->id) }}" class="btn btn-primary btn-sm">
                                    Ver Patrimônios
                                </a>

                                @can('update', $environment)
                                    <a href="{{ route('environments.edit', $environment->id) }}" class="btn btn-primary btn-sm">
                                        Editar
                                    </a>
                                @endcan

                                @can('delete', $environment) 
                                    @php
                                        $patrimoniesCount = $environment->patrimonies_count ?? $environment->patrimonies->count();
                                    @endphp

                                    @if($patrimoniesCount > 0)
                                        {{-- Abre o modal de aviso/transferência se houver patrimônios --}}
                                        <button 
                                            type="button" 
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#hasPatrimoniesModal"
                                            data-env-name="{{ $environment->name }}"
                                            data-env-url="{{ route('environments.patrimonies.index', $environment->id) }}"
                                            data-count="{{ $patrimoniesCount }}"
                                        >
                                            Excluir
                                        </button>
                                    @else
                                        {{-- Formulário acionado apenas quando o ambiente estiver vazio --}}
                                        <form action="{{ route('environments.destroy', $environment->id) }}" 
                                            method="POST" 
                                            class="form-delete-patrimony d-inline"
                                            data-label="{{ $environment->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-body-secondary">
                            Nenhum ambiente encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Bootstrap: Aviso de Ambiente com Patrimônios -->
    <div class="modal fade" id="hasPatrimoniesModal" tabindex="-1" aria-labelledby="hasPatrimoniesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title text-warning d-flex align-items-center gap-2" id="hasPatrimoniesModalLabel">
                        <i class="bi bi-exclamation-triangle-fill"></i> Ambiente Não Vazio
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-start">
                    <p class="mb-2">
                        O ambiente <strong id="modalEnvName" class="text-primary"></strong> possui 
                        <span id="modalPatrimonyCountText" class="badge bg-warning text-dark fw-bold"></span> patrimônio(s) vinculado(s).
                    </p>
                    <p class="text-body-secondary mb-0">
                        Para poder excluir este ambiente, você deve primeiro mover ou reatribuir todos os patrimônios para outro local.
                    </p>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <a id="btnGoToPatrimonies" href="#" class="btn btn-primary btn-sm">
                        Ir para Patrimônios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Dialog Nativo de Confirmação -->
    <dialog id="delete-confirmation-dialog" class="p-4 rounded shadow-lg border bg-body text-body" style="max-width: 400px; margin: auto;">
        <div class="text-center">
            <!-- Elemento de Imagem para Pré-visualização -->
            <div id="dialog-photo-container" class="mb-3 d-none">
                <img id="dialog-photo" src="" alt="Foto do Item" class="img-fluid rounded border" style="max-height: 180px; object-fit: cover;">
            </div>

            <h5 class="mb-2">Confirmar Exclusão</h5>
            <p id="dialog-message" class="text-body-secondary mb-4">Tem certeza que deseja excluir este item?</p>
            
            <div class="d-flex justify-content-center gap-2">
                <button id="btn-dialog-cancel" type="button" class="btn btn-secondary btn-sm px-4">Não</button>
                <button id="btn-dialog-confirm" type="button" class="btn btn-danger btn-sm px-4">Sim</button>
            </div>
        </div>
    </dialog>
@endsection