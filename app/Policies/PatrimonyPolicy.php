<?php

namespace App\Policies;

use App\Models\Patrimony;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatrimonyPolicy
{
    
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user): bool 
    {// $user é o usuário logado injetado pelo laravel
        
        // verificar $user é Editor ou não
        // o metodo abaixo retorna true ou false, se for true o 
        // usuário possui permissão para usar o destroy
        return $user->isAdmin();
    }
    
    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
