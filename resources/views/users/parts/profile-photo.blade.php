@can('updateAvatar', $user)
    <div class="card">
        <div class="card-header text-center">
            <h5>Foto de perfil</h5> 

            {{-- Imagem de preview inicial antes de selecionar arquivo --}}
            <img id="avatar-preview" 
                src="{{ $user->avatar ? asset('storage/' . $user->avatar) : Vite::asset('resources/images/user-default.png') }}" 
                class="updateAvatar rounded mx-auto d-block" 
                alt="Foto de perfil">

            {{-- Área onde o Croppie vai abrir a foto com zoom e controle de movimento --}}
            <div id="croppie-container" class="d-none mx-auto"></div>
        </div>

        <div class="card-body text-center">
            {{-- Adicionado o id="avatar-form" --}}
            <form id="avatar-form" action="{{ route('users.updateAvatar', $user->id) }}" method="POST"> 
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <input class="form-control form-control-sm @error('avatar') is-invalid @enderror" 
                        name="avatar" 
                        type="file" 
                        id="avatar-input" 
                        accept="image/*"
                        placeholder="selecionar Foto"
                    >
                </div>

                @error('avatar')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit" id="btn-save" class="btn btn-sm btn-primary">Atualizar Foto</button>
            </form>
        </div>
    </div>
@endcan