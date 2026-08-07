<div class="card">
    <form action="{{ route('users.updateInterests', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-header">
            Interesses
        </div>
        <div class="card-body">
            @foreach (['Futebol', 'Formula 1'] as $item)
                <div class="form-check">
                    <input 
                        class="form-check-input @error('interests') is-invalid @enderror" 
                        type="checkbox" 
                        value="{{ $item }}" 
                        name="interests[][name]"
                        id="checkDefault"
                        {{-- checa se no array $user com 'name' isolado(pluck*()) da coleção
                         possui o $item e converte em array com toArray()  --}}
                        @checked(in_array($item, $user->interests->pluck('name')->toArray()))
                    >
                        
                    <label class="form-check-label" for="{{ $item }}">
                        {{ $item }}
                    </label>

                    @if ($loop->last)
                        @error('interests')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    @endif
                </div>
            @endforeach
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Editar</button>
        </div>
    </form>
</div>
