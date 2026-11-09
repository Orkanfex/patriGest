<?php

namespace Database\Seeders;

use App\Models\Environment;
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
        
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $technician = Role::firstOrCreate(['name' => 'Técnico']);
        
        User::factory(100)->create([
            'role_id' => fn () => Role::inRandomOrder()->first()->id,
        ]);
        // 2. Criar o Usuário passando o ID da Role recém-criada
        User::factory()->create([
            'name' => 'root',
            'email' => 'root@patrigest.com',
            'password' => Hash::make('12345678'), // Garante que a senha vá criptografada
            'role_id' => $adminRole->id,
        ]);

        User::factory()->create([
            'name' => 'greice',
            'email' => 'greice@gmail.com',
            'password' => Hash::make('12345678'), // Garante que a senha vá criptografada
            'role_id' => $technician->id,
        ]);
        $states = ['Bom', 'Regular', 'Ruim', 'Péssimo', 'Baixa'];
        
        foreach ($states as $state) {
            State::firstOrCreate(['name' => $state]);
        }

        $environments = ['
            Administração', 
            'Microscopia', 
            'Elétrica', 
            'Mecânica', 
            'Química',
            'Térmica'
        ];

        foreach ($environments as $environment)
        Environment::firstOrCreate(['name' => $environment]);

    }
}
