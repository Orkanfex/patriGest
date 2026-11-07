@extends('layouts.default')
@section('page-title', 'Editar Ambiente: ' . $environment->name)

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
        ['label' => 'Ambientes', 'route' => route('environments.index')],
        ['label' => 'Editar Ambiente', 'route' => null],
    ];
@endphp

@section('content')
    @session('status')
        <div class="alert alert-success">
            {{ $value }}
        </div>
    @endsession

    @session('success')
        <div class="alert alert-success">
            {{ $value }}
        </div>
    @endsession

    <form action="{{ route('environments.update', $environment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- Nome do Ambiente --}}
            <div class="col-12 mb-3">
                <label for="environment_name" class="form-label">Nome do Ambiente:</label>
                <input 
                    type="text" 
                    name="name" 
                    id="environment_name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    placeholder="Ex: Laboratório de Informática 01, Almoxarifado Central..."
                    value="{{ old('name', $environment->name) }}"
                    required
                >
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Responsáveis pelo Ambiente (Com busca e seleção múltipla) --}}
            <div class="col-12 mb-3">
                <label for="environment_users" class="form-label">Responsáveis pelo Ambiente:</label>
                <select 
                    name="user_ids[]" 
                    id="environment_users"
                    class="form-select @error('user_ids') is-invalid @enderror @error('user_ids.*') is-invalid @enderror" 
                    multiple
                    placeholder="Digite para buscar e selecionar os responsáveis..."
                    autocomplete="off"
                >
                    @php
                        // Carrega os IDs selecionados do old() ou dos usuários atualmente vinculados ao ambiente
                        $selectedUsers = old('user_ids', $environment->users->pluck('id')->toArray());
                    @endphp

                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" 
                            @selected(in_array($user->id, $selectedUsers))>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Digite o nome ou e-mail do usuário para filtrar e selecione múltiplos responsáveis.</div>
                
                @error('user_ids')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
                @error('user_ids.*')
                    <div class="invalid-feedback d-block">
                        Um ou mais responsáveis selecionados são inválidos.
                    </div>
                @enderror
            </div>

            {{-- Botões de Ação --}}
            <div class="col-12 pt-2 d-flex justify-content-between">
                <a href="{{ route('environments.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>

        </div>
    </form>
@endsection