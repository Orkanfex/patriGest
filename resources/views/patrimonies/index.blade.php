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
        <div class="row input-group-sm" style="width: 50%">

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
    
            <div class="col input-group input-group-sm">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#qrModal">
                    <i class="bi bi-qr-code-scan"></i>
                </button>
            </div>

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
                            <div class="col mb-2 text-center">
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
                            </div>
                            @can('edit', $patrimony)
                                <div class="col mb-2 text-center">
                                    <a href="#" class="btn btn-primary btn-sm">Editar</a>
                                </div>
                            @endcan
                            @can('destroy', $patrimony) 
                                <div class="col mb-2 text-center">
                                    <form action="{{ route('patrimonies.destroy', [$environment, $patrimony->id]) }}" method="POST">
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
    </table>

    {{ $patrimonies->links() }}
@endsection
