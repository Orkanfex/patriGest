@extends('layouts.default')
@section('page-title', $environment->name)

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('page-actions')
    <div class="d-flex gap-2 ms-auto">
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
    </div>
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
        <div class="d-flex gap-2">

            <div class="col input-group input-group-sm">
                
                <input 
                    type="text" 
                    id="patrimony_code"
                    name="keyword" 
                    class="form-control" 
                    placeholder="Pesqueise por descrição ou codigo"
                    value="{{ request()?->keyword }}"
                    data-auto-submit="true"
                >
                
                <button type="submit" class="btn btn-primary">Pesquiar</button>
                
            </div>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#qrModal">
                <i class="bi bi-qr-code-scan"></i>
            </button>

            <!-- MODAL NECESSÁRIO (Com o ID #qrModal e a div #qr-reader) -->
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
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="th-move-checkbox d-none text-center" style="width: 40px;">
                        <input type="checkbox" id="select-all-patrimonies" class="form-check-input">
                    </th>
                    <th scope="col">Nº Patrimonio</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Estado</th>
                    <th scope="col" class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patrimonies as $patrimony)            
                    <tr>
                        <td class="td-move-checkbox d-none text-center">
                            <input type="checkbox" name="patrimony_ids[]" value="{{ $patrimony->id }}" class="form-check-input patrimony-checkbox" form="form-bulk-move">
                        </td>
                        <th scope="row">{{ $patrimony->code }}</th>
                        <td>{{ Str::limit( $patrimony->description, 50)}}</td>
                        <td>{{ $patrimony->state->name }}</td>
                        <td>
                            <div class="row">
                                <div class="col mb-2 text-center">
                                    <button 
                                        type="button" 
                                        class="btn btn-primary btn-sm w-100 w-md-auto"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#showPatrimonyModal"
                                        data-code="{{ $patrimony->code }}"
                                        data-state="{{ $patrimony->state->name }}"
                                        data-description="{{ $patrimony->description }}"
                                        data-image="{{ $patrimony->image ? asset('storage/' . $patrimony->image) : '' }}"
                                    >
                                        Ver
                                    </button>
                                </div>
                                @can('update', $patrimony)
                                    <div class="col mb-2 text-center">
                                        <a href="{{ route('patrimony.update', [$environment, $patrimony->id]) }}" class="btn btn-primary btn-sm w-100 w-md-auto">Editar</a>
                                    </div>
                                @endcan

                                @can('delete', $patrimony) 
                                    <div class="col mb-2 d-grid text-center">
                                        <form action="{{ route('patrimonies.destroy', [$environment, $patrimony->id]) }}" 
                                            method="POST" 
                                            class="form-delete-confirm"
                                            data-label="{{ $patrimony->code ?? $patrimony->name }}"
                                            data-photo="{{ $patrimony->image ? asset('storage/' . $patrimony->image) . '?v=' . filemtime(storage_path('app/public/' . $patrimony->image)) : '' }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100 w-md-auto">Excluir</button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach

                <dialog id="delete-confirmation-dialog" class="p-4 rounded shadow-lg border-0" style="max-width: 400px;">
                    <div class="text-center">
                        <!-- Elemento de Imagem para Pré-visualização -->
                        <div id="dialog-photo-container" class="mb-3 d-none">
                            <img id="dialog-photo" src="" alt="Foto do Patrimônio" class="img-fluid rounded border" style="max-height: 180px; object-fit: cover;">
                        </div>

                        <h5 class="mb-2">Confirmar Exclusão</h5>
                        <p id="dialog-message" class="text-muted mb-4">Tem certeza que deseja excluir este item?</p>
                        
                        <div class="d-flex justify-content-center gap-2">
                            <button id="btn-dialog-cancel" type="button" class="btn btn-primary btn-sm px-4">Não</button>
                            <button id="btn-dialog-confirm" type="button" class="btn btn-danger btn-sm px-4">Sim</button>
                        </div>
                    </div>
                </dialog>
            </tbody>
    
        </table>
    </div>

    <!-- Modal para Selecionar o Ambiente de Destino -->
    <div class="modal fade" id="movePatrimoniesModal" tabindex="-1" aria-labelledby="movePatrimoniesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                
                <form id="form-bulk-move" action="{{ route('environments.patrimonies.bulk-move', $environment->id) }}" method="POST">
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
                    <!-- Imagem -->
                    <div class="mb-3">
                        <img id="modalPatrimonyImage" src="#" alt="Foto do Patrimônio" class="img-fluid rounded d-none">
                        <p id="modalPatrimonyNoImage" class="text-muted d-none mb-0">Nenhuma foto cadastrada.</p>
                    </div>

                    <!-- Dados do Patrimônio -->
                    <div class="text-start">
                        <p class="mb-1"><strong>Nº do Patrimônio:</strong> <span id="modalPatrimonyCode"></span></p>
                        <p class="mb-1"><strong>Estado:</strong> <span id="modalPatrimonyState" class="badge bg-secondary"></span></p>
                        <div class="mt-2">
                            <strong>Descrição:</strong>
                            <p id="modalPatrimonyDescription" class="p-2 rounded text-break mb-0 mt-1"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    {{ $patrimonies->links() }}
@endsection
