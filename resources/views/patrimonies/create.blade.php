@extends('layouts.default')
@section('page-title','Adicionar Patrimônio em '. $environment->name )
    
@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('content')
    <form action="{{ route('patrimony.store', $environment) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">

            <div class="col-12 col-md-6 mb-3">
                <label for="patrimony_code" class="form-label">Nº do Patrimônio:</label>
                <div class="input-group has-validation">
                    <input 
                        type="text" 
                        name="code" 
                        id="patrimony_code" 
                        class="form-control @error('code') is-invalid @enderror" 
                        placeholder="Digite ou escaneie o código"
                        value="{{ old('code') }}"
                    >

                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#qrModal">
                        <i class="bi bi-qr-code-scan"></i>
                    </button>
                    @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
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
    
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Conservação do patrimonio:</label>
                <select 
                    name="state_id" 
                    class="form-select @error('state_id') is-invalid @enderror"  
                    placeholder="Selecione um Ambiente"
                >
                    <option 
                        value="" 
                        disabled {{ old('state_id') == '' ? 'selected' : '' }}>
                        Estados...
                    </option>
    
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}" 
                            @selected(old('state_id') == $state->id)>{{ $state->name }}</option>
                        
                    @endforeach
                </select>
                @error('state_id')
                   <div class="invalid-feedback">
                       {{ $message }}
                   </div>
               @enderror
            </div>


            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Descrição do Patrimonio:</label>
                <textarea 
                    name="description" 
                    class="form-control @error('description') is-invalid @enderror" 
                    id="exampleFormControlTextarea1" 
                >{{old('description')}}</textarea>

                @error('description')
                <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 text-center align-items-center">
                <label class="form-label d-block">Foto do Patrimônio</label>

                <!-- Inputs de arquivo escondidos -->
                <!-- Input 1: Para abrir a Câmera direto -->
                <input type="file" id="cameraInput" accept="image/*" capture="environment" class="d-none">
                
                <!-- Input 2: Para abrir a Galeria/Arquivos (Este é enviado ao Laravel) -->
                <input type="file" name="image" id="fileInput" accept="image/*" class="d-none">

                <!-- Container com os Botões Visíveis -->
                <div class="gap-2 mb-2">
                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('cameraInput').click()">
                        <i class="bi bi-camera-fill me-1"></i> Tirar Foto
                    </button>

                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('fileInput').click()">
                        <i class="bi bi-folder-fill me-1"></i> Escolher Arquivo
                    </button>
                </div>

                <!-- Pré-visualização da Imagem -->
                <div id="previewContainer" class="d-none mt-2">
                    <img id="imagePreview" src="#" alt="Prévia da Foto" class="img-thumbnail" style="max-height: 180px;">
                    <button type="button" id="btnRemoveImage" class="btn btn-sm btn-outline-danger d-block mt-1 mx-auto" onclick="removeImage()">
                        <i class="bi bi-trash"></i> Remover foto
                    </button>
                </div>
            </div>

            @error('image')
                <div class="text-danger text-center small mt-1 font-weight-bold">{{ $message }}</div>
            @enderror

            <div class="pt-2 text-center">
                <button type="submit" class="btn btn-primary">Adicionar</button>
            </div>
        </div>


    </form>
@endsection