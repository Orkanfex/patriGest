<?php

namespace Tests\Feature;

use App\Models\Environment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnvironmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pode_criar_um_ambiente()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->post(route('environments.store'), [
                'name' => 'Laboratório de Redes',
                'description' => 'Ambiente para aulas práticas',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('environments', [
            'name' => 'Laboratório de Redes',
        ]);
    }

    public function test_tecnico_nao_pode_acessar_formulario_de_criacao_de_ambiente()
    {
        $tecnico = User::factory()->technician()->create();

        $response = $this->actingAs($tecnico)
            ->get(route('environments.create'));

        $response->assertStatus(403);
    }

    public function test_admin_pode_atualizar_um_ambiente()
    {
        $admin = User::factory()->admin()->create();
        $ambiente = Environment::factory()->create(['name' => 'Nome Antigo']);

        $response = $this->actingAs($admin)
            ->put(route('environments.update', $ambiente->id), [
                'name' => 'Nome Atualizado',
            ]);

        $this->assertDatabaseHas('environments', [
            'id' => $ambiente->id,
            'name' => 'Nome Atualizado',
        ]);
    }

    public function test_tecnico_nao_pode_atualizar_ambiente()
    {
        $tecnico = User::factory()->technician()->create();
        $ambiente = Environment::factory()->create();

        $response = $this->actingAs($tecnico)
            ->put(route('environments.update', $ambiente->id), [
                'name' => 'Tentativa de Alteração',
            ]);

        $response->assertStatus(403);
    }
}