<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use App\Models\Patrimony;
use App\Models\State;
use Illuminate\Http\Request;

class PatrimonyController extends Controller
{
    //

    public function index(Request $request, Environment $environment) {
        $patrimonies = $environment->patrimonies();

        // ========================  FILTRO DE PESQUISA ========================
        // se nao existir $request->keyword nao faz nada, funciona como um if
        //quando tiver a keyword especificada na $request execute a function($query,$keyword)
        //passando $request->keyword para $keyword dentro da function 
        //que é o valor da consulta $request->keyword

        $patrimonies->when($request->keyword, function($query, $keyword){ 
            //Cria um grupo de where passando uma function 
            //use ($keyword) para injetar a variavel $keyword na  função externa 
            //e usar a variavel dentro do bloco
            $query->where(function ($q) use ($keyword){ 
                $q->where('description','like','%' .$keyword. '%')
                    ->orWhere('code','like','%' .$keyword. '%');
            });
        });
        // =======================================================================
        
        // paginate() faz a paginação da saida do filtro acima por isso
        // adicionamos a variavel $users
        $patrimonies = $patrimonies->paginate(); 

        return view('patrimonies.index', [
            'patrimonies' => $patrimonies,
            'environment' => $environment
        ]);
    }

    public function create(Environment $environment)
    {
        return view('patrimonies.create', [
            'environment' => $environment,
            'states'      => State::all(), // Apenas o estado precisa de dropdown aqui
        ]);
    }
}
