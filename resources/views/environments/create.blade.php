@extends('layouts.default')
@section('page-title', 'Adicionar Ambiente')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
        ['label' => 'Ambientes', 'route' => route('environments.index')],
        ['label' => 'Novo Ambiente'],
    ];
@endphp

@section('content')
    <form action="{{ route('environments.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            {{-- Nome do Ambiente --}}
            <div class="col-12 col-md-6 mb-3">
                <label for="environment_name" class="form-label">Nome do Ambiente:</label>
                <input 
                    type="text" 
                    name="name" 
                    id="environment_name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    placeholder="Ex: Laboratório de Informática 01, Almoxarifado Central..."
                    value="{{ old('name') }}"
                >
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Tipo de Ambiente --}}
            <div class="col-12 col-md-6 mb-3">
                <label for="environment_type" class="form-label">Tipo de Ambiente:</label>
                <select 
                    name="type" 
                    id="environment_type"
                    class="form-select @error('type') is-invalid @enderror"
                >
                    <option value="" disabled {{ old('type') == '' ? 'selected' : '' }}>Selecione um tipo...</option>
                    <option value="Laboratório" @selected(old('type') == 'Laboratório')>Laboratório</option>
                    <option value="Sala Administrativa" @selected(old('type') == 'Sala Administrativa')>Sala Administrativa</option>
                    <option value="Almoxarifado" @selected(old('type') == 'Almoxarifado')>Almoxarifado</option>
                    <option value="Auditório" @selected(old('type') == 'Auditório')>Auditório</option>
                    <option value="Depósito" @selected(old('type') == 'Depósito')>Depósito</option>
                    <option value="Outro" @selected(old('type') == 'Outro')>Outro</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Responsáveis pelo Ambiente (Seleção Múltipla) --}}
            <div class="col-12 mb-3">
                <label for="environment_users" class="form-label">Responsáveis pelo Ambiente:</label>
                <select 
                    name="user_ids[]" 
                    id="environment_users"
                    class="form-select @error('user_ids') is-invalid @enderror" 
                    multiple
                    size="4"
                >
                    @foreach ($users as$user)
                        <option value="{{ $user->id }}" 
                            @selected(is_array(old('user_ids')) && in_array($user->id, old('user_ids')))>
                            {{ $user->name }} ({{$user->email }})
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Segure a tecla <kbd>Ctrl</kbd> (ou <kbd>Cmd</kbd> no Mac) para selecionar múltiplos responsáveis.</div>
                @error('user_ids')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Descrição do Ambiente --}}
            <div class="col-12 mb-3">
                <label for="environment_description" class="form-label">Descrição do Ambiente:</label>
                <textarea 
                    name="description" 
                    id="environment_description"
                    rows="3"
                    class="form-control @error('description') is-invalid @enderror" 
                    placeholder="Adicione informações adicionais sobre o ambiente (localização, regras de acesso, etc.)..."
                >{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Botão de Envio --}}
            <div class="pt-2 text-center">
                <button type="submit" class="btn btn-primary">Cadastrar Ambiente</button>
            </div>
        </div>
    </form>
@endsection