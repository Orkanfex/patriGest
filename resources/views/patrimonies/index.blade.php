@extends('layouts.default')

{{-- 1. Título dinâmico: mostra o nome do ambiente ou o título geral com o filtro --}}
@section('page-title', isset($environment) ? $environment->name : 'Patrimônios ' . (request('status') ? '(' . ucfirst(request('status')) . ')' : ''))

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
    if (isset($environment)) {
        $breadcrumbs[] = ['label' => $environment->name];
    }
@endphp

@section('page-actions')
    <div class="d-flex gap-2 ms-auto">
        {{-- Exibe ações de lote e criação apenas se estiver no contexto de um ambiente específico --}}
        @if(isset($environment))
            {{-- Botão para alternar o modo de seleção em lote --}}
            <button type="button" id="btn-toggle-move-mode" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-left-right me-1"></i> Mover Patrimônios
            </button>

            {{-- Botão de ação (Oculto inicialmente) --}}
            <button type="button" id="btn-open-move-modal" class="btn btn-success btn-sm d-none" data-bs-toggle="modal" data-bs-target="#movePatrimoniesModal" disabled>
                <i class="bi bi-box-arrow-right me-1"></i> Mover Selecionados (<span id="selected-count">0</span>)
            </button>

            @can('create', \App\Models\Patrimony::class)
                <a href="{{ route('patrimony.create', $environment->id) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Adicionar Patrimônio
                </a>
            @endcan
        @endif
    </div>
@endsection

@section('content')
    @session('status')
        <div class="alert alert-success">
            {{ $value }}
        </div>
    @endsession

    {{-- Form de Busca adaptável à rota atual --}}
    <form action="{{ isset($environment) ? route('patrimonies.index', $environment->id) : route('patrimonies.index') }}" method="GET" class="mb-3">
        {{-- Mantém o filtro de status vindo do Dashboard ao buscar por palavra-chave --}}
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <div class="d-flex gap-2">
            <div class="col input-group input-group-sm">
                <input 
                    type="text" 
                    id="patrimony_code"
                    name="keyword" 
                    class="form-control" 
                    placeholder="Pesquise por descrição ou código"
                    value="{{ request()?->keyword }}"
                    data-auto-submit="true"
                >
                <button type="submit" class="btn btn-primary">Pesquisar</button>
            </div>
            
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#qrModal">
                <i class="bi bi-qr-code-scan"></i>
            </button>

            {{-- MODAL NECESSÁRIO (Com o ID #qrModal e a div #qr-reader) --}}
            <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="qrModalLabel">Escanear QR Code</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <div id="qr-reader" style="width: 100%; min-height: 280px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Indicador visual caso haja filtro ativado via Dashboard --}}
    @if(request('status'))
        <div class="alert alert-info d-flex justify-content-between align-items-center py-2 mb-3">
            <span>
                <i class="bi bi-filter me-1"></i> Filtrando por estado: <strong>{{ strtoupper(request('status')) }}</strong>
            </span>
            <a href="{{ isset($environment) ? route('patrimonies.index', $environment->id) : route('patrimonies.index') }}" class="btn btn-sm btn-outline-dark">
                Limpar filtro
            </a>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    @if(isset($environment))
                        <th scope="col" class="th-move-checkbox d-none text-center" style="width: 40px;">
                            <input type="checkbox" id="select-all-patrimonies" class="form-check-input">
                        </th>
                    @endif
                    <th scope="col">Nº Patrimônio</th>
                    <th scope="col">Descrição</th>
                    {{-- 2. Coluna extra de Ambiente se a lista for geral/Dashboard --}}
                    @if(!isset($environment))
                        <th scope="col">Ambiente</th>
                    @endif
                    <th scope="col">Estado</th>
                    <th scope="col" class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patrimonies as $patrimony)            
                    <tr>
                        @if(isset($environment))
                            <td class="td-move-checkbox d-none text-center">
                                <input type="checkbox" name="patrimony_ids[]" value="{{ $patrimony->id }}" class="form-check-input patrimony-checkbox" form="form-bulk-move">
                            </td>
                        @endif
                        <th scope="row">{{ $patrimony->code }}</th>
                        <td>{{ Str::limit($patrimony->description, 50) }}</td>
                        
                        @if(!isset($environment))
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $patrimony->environment->name ?? 'N/A' }}
                                </span>
                            </td>
                        @endif

                        <td>{{ $patrimony->state->name }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button 
                                    type="button" 
                                    class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#showPatrimonyModal"
                                    data-code="{{ $patrimony->code }}"
                                    data-state="{{ $patrimony->state->name }}"
                                    data-description="{{ $patrimony->description }}"
                                    data-image="{{ $patrimony->image ? asset('storage/' . $patrimony->image) : '' }}"
                                >
                                    Ver
                                </button>

                                @can('update', $patrimony)
                                    <a href="{{ route('patrimony.update', [$patrimony->environment_id, $patrimony->id]) }}" class="btn btn-primary btn-sm">Editar</a>
                                @endcan

                                @can('delete', $patrimony) 
                                    <form action="{{ route('patrimonies.destroy', [$patrimony->environment_id, $patrimony->id]) }}" 
                                        method="POST" 
                                        class="form-delete-confirm d-inline"
                                        data-label="{{ $patrimony->code ?? $patrimony->name }}"
                                        data-photo="{{ $patrimony->image ? asset('storage/' . $patrimony->image) . '?v=' . filemtime(storage_path('app/public/' . $patrimony->image)) : '' }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Modal para Selecionar o Ambiente de Destino -->
    <div class="modal fade" id="movePatrimoniesModal" tabindex="-1" aria-labelledby="movePatrimoniesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                
                <form id="form-bulk-move" action="{{ isset($environment) ? route('environments.patrimonies.bulk-move', $environment->id) : '#' }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="movePatrimoniesModalLabel">Transferir Patrimônios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <p>Selecione o ambiente para onde os itens escolhidos serão transferidos:</p>
                        
                        <div class="mb-3">
                            <label for="destination_environment_id" class="form-label">Ambiente de Destino</label>
                            <select name="destination_environment_id" id="destination_environment_id" class="form-select" required>
                                <option value="">Selecione um ambiente...</option>
                                @foreach($otherEnvironments ?? [] as $destEnv)
                                    <option value="{{ $destEnv->id }}">{{ $destEnv->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Confirmar Transferência</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Visualização do Patrimônio -->
    <div class="modal fade" id="showPatrimonyModal" tabindex="-1" aria-labelledby="showPatrimonyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showPatrimonyModalLabel">Detalhes do Patrimônio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Imagem do Patrimônio -->
                    <div class="mb-3">
                        <img id="modalPatrimonyImage" src="" alt="Foto do Patrimônio" class="img-fluid rounded d-none" style="max-height: 250px; object-fit: cover;">
                        <p id="modalPatrimonyNoImage" class="text-body-secondary d-none mb-0 py-3 bg-body-secondary border rounded">
                            <i class="bi bi-image me-1"></i> Nenhuma foto cadastrada.
                        </p>
                    </div>

                    <!-- Detalhes do Patrimônio -->
                    <div class="text-start">
                        <p class="mb-2">
                            <strong>Nº do Patrimônio:</strong> 
                            <span id="modalPatrimonyCode" class="fw-bold text-primary"></span>
                        </p>
                        <p class="mb-2">
                            <strong>Estado:</strong> 
                            <span id="modalPatrimonyState" class="badge bg-secondary"></span>
                        </p>
                        <div class="mt-3">
                            <strong>Descrição:</strong>
                            <p id="modalPatrimonyDescription" class="p-2 bg-body-secondary border rounded text-break mb-0 mt-1"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dialog Nativo para Confirmação de Exclusão -->
    <dialog id="delete-confirmation-dialog" class="p-4 rounded shadow-lg border bg-body text-body" style="max-width: 400px;">
        <div class="text-center">
            <!-- Pré-visualização da Foto -->
            <div id="dialog-photo-container" class="mb-3 d-none">
                <img id="dialog-photo" src="" alt="Foto do Patrimônio" class="img-fluid rounded border" style="max-height: 180px; object-fit: cover;">
            </div>

            <h5 class="mb-2">Confirmar Exclusão</h5>
            <p id="dialog-message" class="text-body-secondary mb-4">Tem certeza que deseja excluir este item?</p>
            
            <div class="d-flex justify-content-center gap-2">
                <button id="btn-dialog-cancel" type="button" class="btn btn-secondary btn-sm px-4">Não</button>
                <button id="btn-dialog-confirm" type="button" class="btn btn-danger btn-sm px-4">Sim</button>
            </div>
        </div>
    </dialog>

    {{ $patrimonies->links() }}
@endsection