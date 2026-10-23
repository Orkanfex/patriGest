<?php

namespace App\Policies;

use App\Models\Environment;
use App\Models\User;

class EnvironmentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode atualizar o ambiente.
     */
    public function update(User $user, Environment $environment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode excluir o ambiente.
     */
    public function delete(User $user, Environment $environment): bool
    {
        return $user->isAdmin();
    }
}
