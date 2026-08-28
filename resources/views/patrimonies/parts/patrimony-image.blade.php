@can('update', $patrimony)
    <div class="card">
        <form action="{{ route('patrimony.updateImage', [$environment, $patrimony]) }}" 
            method="POST" 
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')
            <div class="card-header">

                <label class="form-label d-block">Foto do Patrimônio</label>
            </div>
            <div class="card-body">
        
                <div class="mb-3 text-center align-items-center">
                
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
                    <div id="previewContainer" class="{{ $patrimony->image ? '' : 'd-none' }} mt-2">
                        <img id="imagePreview" 
                            src="{{ $patrimony->image ? asset('storage/'. $patrimony->image) : '#'}}" 
                            alt="Prévia da Foto" 
                            class="img-thumbnail" 
                            style="max-height: 180px;"
                        >
                        <button type="button" id="btnRemoveImage" class="btn btn-sm btn-outline-danger d-block mt-1 mx-auto" onclick="removeImage()">
                            <i class="bi bi-trash"></i> Remover foto
                        </button>
                    </div>
                </div>
                
                @error('image')
                    <div class="text-danger text-center small mt-1 font-weight-bold">{{ $message }}</div>
                @enderror
                
            </div>
            <div class="card-footer">
                <div class="pt-2 ">
                    <button type="submit" class="btn btn-primary">Editar</button>
                </div>
            </div>
        </form>
    </div>
@endcan