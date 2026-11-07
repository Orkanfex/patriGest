@extends('layouts.default')
@section('page-title', 'Adicionar Ambiente')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
        ['label' => 'Ambientes', 'route' => route('environments.index')],
        ['label' => 'Novo Ambiente', 'route' => null],
    ];
@endphp

@section('content')
    <form action="{{ route('environments.store') }}" method="POST">
        @csrf

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
                    value="{{ old('name') }}"
                    required
                >
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Responsáveis pelo Ambiente (Seleção Múltipla N:N) --}}
            <div class="col-12 mb-3">
                <label for="environment_users" class="form-label">Responsáveis pelo Ambiente:</label>
                <select 
                    name="user_ids[]" 
                    id="environment_users"
                    class="form-select @error('user_ids') is-invalid @enderror @error('user_ids.*') is-invalid @enderror" 
                    multiple
                    size="5"
                >
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" 
                            @selected(is_array(old('user_ids')) && in_array($user->id, old('user_ids')))>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Segure a tecla <kbd>Ctrl</kbd> (ou <kbd>Cmd</kbd> no Mac) para selecionar múltiplos responsáveis.</div>
                
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
                <button type="submit" class="btn btn-primary">Cadastrar Ambiente</button>
            </div>

        </div>
    </form>
@endsection