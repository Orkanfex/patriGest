<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    //Contem os metodos que vao fazer as permissoes acontecer
    public function __construct()
    {
        //
    }

    // funcao para destruir ou deletar usuário
    public function destroy(User $user) {// $user é o usuário logado injetado pelo laravel
        
        // verificar $user é admin ou não
        // o metodo abaixo retorna true ou false, se for true o 
        // usuário possui permissão para usar o destroy
        return $user->role()
                ->where('name', 'Admin')->exists();
    }

    public function edit(User $user, User $model) {// $user é o usuário logado injetado pelo laravel
        
        // verificar $user é Editor ou não
        // o metodo abaixo retorna true ou false, se for true o 
        // usuário possui permissão para usar o destroy
        return $user->id === $model->id 
                || $user->role()->where('name', 'Admin')->exists();
    }

    public function store(User $user){
        return $user->role()
                ->where('name','Admin')->exists();
    }

    public function update(User $user, User $model){
        
        return $user->id === $model->id 
                || $user->role()->where('name', 'Admin')->exists();
    }

    public function updateRoles(User $user){
        return $user->role()
                ->where('name', 'Admin')->exists();
                
    }

    public function updateAvatar(User $user, $model){
        return $user->id === $model->id;
    }
}
