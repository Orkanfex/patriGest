<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use App\Models\User;
use Illuminate\Http\Request;

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
            $q->where('name', 'like', '%' . $keyword . '%');
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
            'users' => User::orderBy('name', 'asc')->get(), // Busca os usuários ordenados por nome para a seleção de responsáveis
        ]);
    }
}
