@can('updateRoles', $user)
    <div class="card">
        <form action="{{ route('users.updateRoles', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header">
                Cargos
            </div>
            <div class="card-body">
                @foreach ($roles as $role)
                    <div class="form-check">
                        <input 
                            class="form-check-input @error('role_id') is-invalid @enderror" 
                            type="radio" 
                            name="role_id" 
                            id="radioDefault1"
                            value="{{ $role->id }}"
                            @checked(old('role_id', $user->role_id ?? null) == $role->id)
                        >   
                        <label class="form-check-label" for="{{ $role->name }}">
                            {{ $role->name }}
                        </label>

                        @if ($loop->last)
                            @error('role_id')
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

@endcan