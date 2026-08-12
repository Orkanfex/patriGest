@extends('layouts.default')
@section('page-title', 'Usuarios')
@section('page-actions')
    @can('store', \App\Models\User::class)
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Adicionar</a>
    @endcan
@endsection

@section('content')
    @session('status')
        <div class="alert alert-success">
            {{ $value }}
        </div>
    @endsession
    
    {{--n @can precisa passar o contexto inteiro \App\Models\User::class  --}}
    {{-- @can passa a permissao destroy e o contexto para checar a permissão --}}

    <form action="{{ route('users.index') }}" method="GET" class="mb-3">
        <div class="input-group input-group-sm" style="width: 300px">

            <input 
                type="text" 
                name="keyword" 
                class="form-control" 
                placeholder="Pesqueise por nome ou email"
                value="{{ request()?->keyword }}"
            >
            <button type="submit" class="btn btn-primary">Pesquiar</button>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Ação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)            
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @can('edit', \App\Models\User::class)
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>
                        @endcan
                        @can('destroy', \App\Models\User::class) 
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
@endsection