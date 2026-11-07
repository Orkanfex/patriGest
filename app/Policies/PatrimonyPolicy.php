<?php

namespace App\Policies;

use App\Models\Patrimony;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatrimonyPolicy
{
    
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null; // Caso não seja Admin (ex: Técnico), continua para a regra do método específico
    }

    /**
     * Determina se o usuário pode visualizar a lista de patrimônios.
     */
    public function viewAny(User $user): bool
    {
        // Admins e Técnicos podem visualizar
        return $user->isTechnician();
    }

    /**
     * Determina se o usuário pode visualizar um patrimônio específico.
     */
    public function view(User $user, Patrimony $patrimony): bool
    {
        return $user->isTechnician();
    }

    /**
     * Determina se o usuário pode criar patrimônios.
     */
    public function create(User $user): bool
    {
        // Técnicos podem cadastrar novos patrimônios
        return $user->isTechnician();
    }

    /**
     * Determina se o usuário pode editar patrimônios.
     */
    public function update(User $user, Patrimony $patrimony): bool
    {
        // Técnicos podem editar dados e movimentar patrimônios
        return $user->isTechnician();
    }

    /**
     * Determina se o usuário pode excluir um patrimônio.
     */
    public function delete(User $user, Patrimony $patrimony): bool
    {
        // Técnicos NÃO podem excluir patrimônios.
        // Como o Admin já foi liberado no método before(), qualquer outro perfil retornará false.
        return false;
    }
}
