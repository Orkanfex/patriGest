@can('update', $patrimony)
    <div class="card">
        <form action="{{ route('patrimony.update', [$environment->id, $patrimony->id])}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header">
                Dados Básicos
            </div>    
            <div class="card-body">
                <div class="col-12 col-md-6 mb-3">
                    <label for="patrimony_code" class="form-label">Nº do Patrimônio:</label>
                    <div class="input-group has-validation">
                        <input 
                            type="text" 
                            name="code" 
                            id="patrimony_code" 
                            class="form-control @error('code') is-invalid @enderror" 
                            placeholder="Digite ou escaneie o código"
                            value="{{ old('code', $patrimony->code)}}"
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
        
                        @foreach ($states as $state)
                            <option value="{{ $state->id }}" 
                                @selected(old('state_id', $patrimony->state_id) == $state->id)
                            >{{ $state->name }}</option>
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
                    >{{old('description', $patrimony->description)}}</textarea>
        
                    @error('description')
                    <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Editar</button>
            </div>
        </form>
    </div>
@endcan