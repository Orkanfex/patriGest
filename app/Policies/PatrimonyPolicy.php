<?php

namespace App\Policies;

use App\Models\Patrimony;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatrimonyPolicy
{
    
    public function store(User $user): bool
    {
        return $user->role()->where('name', 'Admin')->exists();
    }

    public function edit(User $user) {// $user é o usuário logado injetado pelo laravel
        
        // verificar $user é Editor ou não
        // o metodo abaixo retorna true ou false, se for true o 
        // usuário possui permissão para usar o destroy
        return $user->role()->where('name', 'Admin')->exists();
    }

    public function update(User $user, Patrimony $patrimony): bool
    {
        return false;
    }
    
    public function destroy(User $user, Patrimony $patrimony): bool
    {
        return $user->role()->where('name', 'Admin')->exists();
    }
}
