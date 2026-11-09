<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pode_listar_usuarios()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->get(route('users.index'));

        $response->assertStatus(200);
    }

    public function test_tecnico_nao_pode_listar_usuarios()
    {
        $tecnico = User::factory()->technician()->create();

        $response = $this->actingAs($tecnico)
            ->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_pode_deletar_outro_usuario()
    {
        $admin = User::factory()->admin()->create();
        $usuarioParaDeletar = User::factory()->create();

        $response = $this->actingAs($admin)
            ->delete(route('users.destroy', $usuarioParaDeletar->id));

        $this->assertDatabaseMissing('users', [
            'id' => $usuarioParaDeletar->id,
        ]);
    }
}
