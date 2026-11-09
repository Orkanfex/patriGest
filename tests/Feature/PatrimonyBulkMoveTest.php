<?php

namespace Tests\Feature;

use App\Models\Environment;
use App\Models\Patrimony;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatrimonyBulkMoveTest extends TestCase
{
    use RefreshDatabase; // Limpa e recria as tabelas no banco SQLite em memória a cada teste

    public function test_admin_pode_transferir_patrimonios_em_lote()
    {
        // 1. Cria o Admin (usando o método ->admin() que configuramos na UserFactory)
        $admin = User::factory()->admin()->create();
        $origem = Environment::factory()->create(['name' => 'Sala 101']);
        $destino = Environment::factory()->create(['name' => 'Depósito']);

        // 2. Cria 3 patrimônios no ambiente de origem
        $patrimonios = Patrimony::factory()->count(3)->create([
            'environment_id' => $origem->id,
        ]);
        $ids = $patrimonios->pluck('id')->toArray();

        // 3. Simula a requisição POST para a rota bulk-move
        $response = $this->actingAs($admin)
            ->post(route('environments.patrimonies.bulk-move', $origem->id), [
                'patrimony_ids' => $ids,
                'destination_environment_id' => $destino->id,
            ]);

        // 4. Assertions: Verifica redirecionamento e atualização no banco
        $response->assertRedirect(route('patrimonies.index', $origem->id));
        $response->assertSessionHas('status');

        foreach ($ids as $id) {
            $this->assertDatabaseHas('patrimonies', [
                'id' => $id,
                'environment_id' => $destino->id,
            ]);
        }
    }

    public function test_tecnico_nao_pode_transferir_para_ambiente_sem_acesso()
    {
        // 1. Cria o Técnico e associa apenas ao ambiente de origem
        $tecnico = User::factory()->technician()->create();
        $origem = Environment::factory()->create();
        $destinoSemAcesso = Environment::factory()->create();

        $tecnico->environments()->attach($origem->id);

        $patrimonio = Patrimony::factory()->create([
            'environment_id' => $origem->id,
        ]);

        // 2. Tenta mover para o ambiente proibido
        $response = $this->actingAs($tecnico)
            ->post(route('environments.patrimonies.bulk-move', $origem->id), [
                'patrimony_ids' => [$patrimonio->id],
                'destination_environment_id' => $destinoSemAcesso->id,
            ]);

        // 3. Verifica se o acesso foi negado (403 Forbidden) e se o item continuou na origem
        $response->assertStatus(403);

        $this->assertDatabaseHas('patrimonies', [
            'id' => $patrimonio->id,
            'environment_id' => $origem->id,
        ]);
    }

    public function test_valida_erro_quando_nenhum_patrimonio_e_selecionado()
    {
        $admin = User::factory()->admin()->create();
        $origem = Environment::factory()->create();
        $destino = Environment::factory()->create();

        $response = $this->actingAs($admin)
            ->post(route('environments.patrimonies.bulk-move', $origem->id), [
                'patrimony_ids' => [], // Array vazio
                'destination_environment_id' => $destino->id,
            ]);

        $response->assertSessionHasErrors('patrimony_ids');
    }

    public function test_valida_erro_quando_ambiente_de_destino_e_igual_ao_de_origem()
    {
        $admin = User::factory()->admin()->create();
        $origem = Environment::factory()->create();

        $patrimonio = Patrimony::factory()->create(['environment_id' => $origem->id]);

        $response = $this->actingAs($admin)
            ->post(route('environments.patrimonies.bulk-move', $origem->id), [
                'patrimony_ids' => [$patrimonio->id],
                'destination_environment_id' => $origem->id, // Tenta mover para a própria origem
            ]);

        $response->assertSessionHasErrors('destination_environment_id');
    }

    public function test_garante_que_apenas_patrimonios_do_ambiente_correto_sejam_movidos()
    {
        $admin = User::factory()->admin()->create();
        $origem = Environment::factory()->create();
        $outroAmbiente = Environment::factory()->create();
        $destino = Environment::factory()->create();

        $patrimonioOrigem = Patrimony::factory()->create(['environment_id' => $origem->id]);
        $patrimonioOutro = Patrimony::factory()->create(['environment_id' => $outroAmbiente->id]);

        $response = $this->actingAs($admin)
            ->post(route('environments.patrimonies.bulk-move', $origem->id), [
                'patrimony_ids' => [$patrimonioOrigem->id, $patrimonioOutro->id],
                'destination_environment_id' => $destino->id,
            ]);

        // O item da origem mudou para o destino
        $this->assertDatabaseHas('patrimonies', [
            'id' => $patrimonioOrigem->id,
            'environment_id' => $destino->id,
        ]);

        // O item do outro ambiente PERMANECEU intacto no ambiente dele
        $this->assertDatabaseHas('patrimonies', [
            'id' => $patrimonioOutro->id,
            'environment_id' => $outroAmbiente->id,
        ]);
    }
}
