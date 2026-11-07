<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnvironmentController extends Controller
{
    public function index(Request $request) 
{
    // Inicia a query do Model Environment
    $environments = Environment::query();

    // ======================== FILTRO DE PESQUISA ========================
    // Quando houver uma 'keyword' na requisição, aplica o filtro de busca
    $environments->when($request->keyword, function ($query, $keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
              ->orWhereHas('users', function ($userQuery) use ($keyword) {
                $userQuery->where('name','like','%' . $keyword . '%');
              });
              // Adicione outros campos se necessário, ex: ->orWhere('code', 'like', '%' . $keyword . '%');
        });
    });
    // =======================================================================

    // Executa a paginação mantendo os parâmetros da URL (como o ?keyword=...)
    $environments = $environments->paginate()->appends($request->query());

    return view('environments.index', [
        'environments' => $environments
    ]);
    }

    public function create()
    {
        return view('environments.create', [
            'users' => User::orderBy('name', 'asc')->get(), 
            // Busca os usuários ordenados por nome para a seleção de responsáveis
        ]);
    }

    public function store(Request $request){
        // 1. Autorização via Gate (Lança Http 403 se o usuário não for autorizado)
        Gate::authorize('create', Environment::class);

        // 2. Validação dos dados de entrada
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:environments,name',
            'user_ids'    => 'nullable|array',
            'user_ids.*'  => 'exists:users,id',
        ], [
            'name.required'     => 'O nome do ambiente é obrigatório.',
            'name.unique'       => 'Já existe um ambiente cadastrado com esse nome.',
            'user_ids.array'    => 'O formato dos responsáveis selecionados é inválido.',
            'user_ids.*.exists' => 'Um ou mais usuários selecionados não existem no sistema.',
        ]);

        // 3. Criação direta com Eloquent ->create()
        $environment = Environment::create([
            'name' => $validated['name'],
        ]);

        // 4. Vincula os responsáveis na tabela pivô (se houver algum selecionado)
        if (!empty($validated['user_ids'])) {
            $environment->users()->attach($validated['user_ids']);
        }

        // 5. Redirecionamento
        return redirect()
            ->route('environments.index')
            ->with('success', 'Ambiente cadastrado com sucesso!');
    }

    public function edit(Environment $environment)
    {
        // 1. Carrega os responsáveis vinculados ao ambiente (Eager Loading)
        $environment->load('users');

        // 2. Busca todos os usuários ordenados por nome para popular o Tom Select
        $users = User::orderBy('name', 'asc')->get();

        // 3. Renderiza a view 'environments.edit' passando as variáveis necessárias
        return view('environments.edit', compact('environment', 'users'));
    }

    public function update(Request $request, Environment $environment)
    {
        // O middleware já validou a permissão antes de entrar aqui.
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:environments,name,' . $environment->id,
            'user_ids'    => 'nullable|array',
            'user_ids.*'  => 'exists:users,id',
        ]);

        // Atualiza o nome do ambiente
        $environment->update([
            'name' => $validated['name'],
        ]);

        // Sincroniza a tabela pivô (adiciona novos, remove desmarcados)
        $environment->users()->sync($validated['user_ids'] ?? []);

        return redirect()
            ->route('environments.index')
            ->with('success', 'Ambiente atualizado com sucesso!');
    }
}
