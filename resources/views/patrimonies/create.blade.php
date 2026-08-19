@extends('layouts.default')
@section('page-title','Adicionar Patrimônio em '. $environment->name )
    
@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('content')
    <form action="">
        @csrf

        <div class="row">

            <div class="col mb-3">
                <label for="code" class="form-label">Nº do Patrimonio:</label>
                <div class="input-group">
                    <input type="text" name="code" id="patrimony_code" class="form-control" placeholder="Digite ou escaneie o código" required>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#qrModal">
                        <i class="bi bi-qr-code-scan"></i> Ler QR Code
                    </button>
                </div>
            </div>
    
            <div class="col mb-3">
                <label class="form-label">Conservação do patrimonio:</label>
                <select class="form-select"  placeholder="Selecione um Ambiente">
                    <option selected>Estados...</option>
    
                    @foreach ($states as $status)
                        <option>{{ $status->name }}</option>
                        
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Descrição do Patrimonio:</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
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
        </div>


    </form>
@endsection