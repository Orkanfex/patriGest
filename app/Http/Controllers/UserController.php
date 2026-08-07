<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    //
    public function index(Request $request){
        
        $users = User::query(); //montando a query builder

        // ========================  FILTRO DE PESQUISA ========================
        // se nao existir $request->keyword nao faz nada, funciona como um if
        //quando tiver a keyword especificada na $request execute a function($query,$keyword)
        //passando $request->keyword para $keyword dentro da function 
        //que é o valor da consulta $request->keyword

        $users->when($request->keyword, function($query, $keyword){ 
            //Cria um grupo de where passando uma function 
            //use ($keyword) para injetar a variavel $keyword na  função externa 
            //e usar a variavel dentro do bloco
            $query->where(function ($q) use ($keyword){ 
                $q->where('name','like','%' .$keyword. '%')
                    ->orWhere('email','like','%' .$keyword. '%');
            });
        });
        // =======================================================================
        
        // paginate() faz a paginação da saida do filtro acima por isso
        // adicionamos a variavel $users
        $users = $users->paginate(); 

        return view('users.index', [
            'users' => $users
        ]);
    }
    public function create(){
        
        return view('users.create');
    }

    public function store(Request $request){
        // pode precisar proteger com o Gate::authorize ou pode nao precisar
        $input = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);

        User::create($input);
        return redirect()
            ->route('users.index')
            ->with('status','Usuário adicionado com sucesso');
    }

    public function edit(User $user){
        // protege o acesso ao beck end via post pela url
        Gate::authorize('edit', User::class);

        // carrega a relção perfil e interests para consultar endereço e o tipo de pessoa
        $user->load('profile', 'interests');
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(User $user, Request $request){
        // protege o acesso ao beck end via post pela url
        Gate::authorize('update', User::class);

        $input = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['exclude_if:password,null', 'min:6']
        ]);

        $user->fill($input);
        $user->save();
        
        return redirect()
            ->route('users.index')
            ->with('status', 'Usuário editado com sucesso');
    }

    public function updateProfile(User $user, Request $request){
        // protege o acesso ao beck end via post pela ur
        Gate::authorize('updateProfile', User::class);

        $input = $request->validate([
            'type' => ['required'],
            'address' => ['nullable']
        ]);

        UserProfile::updateOrCreate([
            'user_id' => $user->id
            ],$input);
        //é o mesmo metodo que utilizado acima mas resumido
        //                    \/
        // $user->profile()->updateOrCreate($input);
        
        
        return back()
            ->with('status', 'Usuário editado com sucesso');
    }

    public function updateInterests(User $user, Request $request){
        // protege o acesso ao beck end via post pela url
        Gate::authorize('updateInterests', User::class);

        $input = $request->validate([
            'interests' => ['nullable','array']
        ]);
        //deleta primeiro pra atualizar os dados
        $user->interests()->delete();
        // createMany() por que é um array que cria vários interesses
        if(!empty($input['interests'])){
            $user->interests()->createMany($input['interests']); 
        }
        return back()
            ->with('status', 'Usuário editado com sucesso');
    }

    public function updateRoles(User $user, Request $request) {
        // protege o acesso ao beck end via post pela url
        Gate::authorize('updateRoles', User::class);

        $input = $request->validate([
                'roles' => ['required', 'array']
        ]);
        //anexando um model acom o outro dentro da tabela user_roles(tabela pivô)
        $user->roles()->sync($input['roles']);

        return back()
            ->with('status', 'Usuário editado com sucesso');
    }

    public function destroy(User $user) {
        // protege o acesso ao beck end via post pela url
        Gate::authorize('destroy', User::class);

        $user->delete();
        return back()
            ->with('status', 'Usuário deletado com sucesso');
    }

    
    
}
