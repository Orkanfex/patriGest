<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Symfony\Component\HttpKernel\Profiler\Profile;

#[Fillable(['name', 'email', 'password', 'role_id', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function environments(){
        return $this->belongsToMany(Environment::class);
    }

    // 2. Atributo dinâmico para a Sidebar e Listagens
    public function getAccessibleEnvironmentsAttribute()
    {
        // Se for Admin, busca TODOS os ambientes do sistema na hora
        if ($this->isAdmin()) {
            return Environment::all();
        }

        // Se for Usuário comum, traz apenas os vinculados no banco
        return $this->environments;
    }

    // Helper para checar se é admin
    public function isAdmin(): bool
    {
        return $this->role->name === 'Admin'; // ou $this->role_id === 1
    }
}
