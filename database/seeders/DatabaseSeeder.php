<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Técnico']);

        // 2. Criar o Usuário passando o ID da Role recém-criada
        User::factory()->create([
            'name' => 'root',
            'email' => 'root@patrigest.com',
            'password' => Hash::make('12345678'), // Garante que a senha vá criptografada
            'role_id' => $adminRole->id,
        ]);

        $states = ['Bom', 'Regular', 'Ruim', 'Péssimo', 'Baixa'];

        foreach ($states as $state) {
            State::firstOrCreate(['name' => $state]);
        }
    }
}
