<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use App\Models\Patrimony;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request, Environment $environment){

        Gate::authorize('store', User::class);
        $input = $request->validate([
            'code' => ['required'],
            'state' => ['required', 'exists:states,id'],
            'description' => ['required'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        //mapeando state para a coluna state_id do banco de dados
        $input['state_id'] = $input['state'];
        //unset remova a chave states que nao representa nenhuma 
        //coluna no banco para usar o state_id
        unset($input['states']);

        // dd($input);
        if(!empty($input['image']) && $input['image']->isValid()){

            $path = $input['image']->store('patrimonies', 'public');

            $input['image'] = $path;

        }
        $environment->patrimonies()->create($input);

        return redirect()
            ->route('patrimonies.index', $environment)
            ->with('status','patrimonio adicionado com sucesso');
    }

    public function destroy(Environment $environment, Patrimony $patrimony){
        
    
        // 1. (Opcional) Remove o arquivo da imagem do storage
        if ($patrimony->image) {
            Storage::disk('public')->delete($patrimony->image);
        }

        // 2. Deleta o registro do banco de dados
        $patrimony->delete();

        // 3. Usa a variável $environment para voltar para a lista do ambiente correto
        return redirect()
            ->route('patrimonies.index', $environment)
            ->with('status', 'Patrimônio removido com sucesso!');
    }
}
