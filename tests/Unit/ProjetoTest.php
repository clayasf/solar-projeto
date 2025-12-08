<?php

namespace Tests\Unit;

use App\Infrastructure\Eloquent\Projeto;
use App\Infrastructure\Eloquent\Cliente;
use App\Infrastructure\Eloquent\Equipamento;
use App\Domain\Enums\TipoInstalacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjetoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar cliente de teste
        Cliente::create([
            'nome' => 'Cliente Teste',
            'email' => 'cliente@teste.com',
            'telefone' => '(11) 99999-9999',
            'cpf_cnpj' => '123.456.789-09',
        ]);

        // Criar equipamentos de teste
        Equipamento::create([
            'nome' => 'Painel Solar 550W',
            'tipo' => 'Módulo',
            'preco_unitario' => 899.90,
            'estoque_atual' => 50,
            'ativo' => true,
        ]);

        Equipamento::create([
            'nome' => 'Inversor 5kW',
            'tipo' => 'Inversor',
            'preco_unitario' => 4500.00,
            'estoque_atual' => 10,
            'ativo' => true,
        ]);
    }

    public function test_pode_criar_projeto_com_dados_validos()
    {
        $projeto = Projeto::create([
            'cliente_id' => 1,
            'uf' => 'SP',
            'tipo_instalacao' => TipoInstalacao::CERAMICO->value,
        ]);

        $this->assertDatabaseHas('projetos', [
            'id' => $projeto->id,
            'cliente_id' => 1,
            'uf' => 'SP',
            'tipo_instalacao' => 'Cerâmico',
        ]);
    }

    public function test_projeto_pertence_a_cliente()
    {
        $projeto = Projeto::create([
            'cliente_id' => 1,
            'uf' => 'RJ',
            'tipo_instalacao' => TipoInstalacao::LAJE->value,
        ]);

        $this->assertEquals(1, $projeto->cliente_id);
        $this->assertInstanceOf(Cliente::class, $projeto->cliente);
    }

    public function test_projeto_pode_ter_multiplos_equipamentos()
    {
        $projeto = Projeto::create([
            'cliente_id' => 1,
            'uf' => 'MG',
            'tipo_instalacao' => TipoInstalacao::METALICO->value,
        ]);

        $projeto->equipamentos()->attach(1, ['quantidade' => 10]);
        $projeto->equipamentos()->attach(2, ['quantidade' => 5]);

        $this->assertEquals(2, $projeto->equipamentos->count());
    }

    public function test_pode_calcular_total_do_projeto()
    {
        $projeto = Projeto::create([
            'cliente_id' => 1,
            'uf' => 'SP',
            'tipo_instalacao' => TipoInstalacao::FIBROCIMENTO_MADEIRA->value,
        ]);

        $projeto->equipamentos()->attach(1, ['quantidade' => 5]);
        $projeto->equipamentos()->attach(2, ['quantidade' => 2]);

        $total = $projeto->getTotal();
        $esperado = (899.90 * 5) + (4500.00 * 2);

        $this->assertEquals($esperado, $total);
    }

    public function test_pode_atualizar_projeto()
    {
        $projeto = Projeto::create([
            'cliente_id' => 1,
            'uf' => 'SP',
            'tipo_instalacao' => TipoInstalacao::CERAMICO->value,
        ]);

        $projeto->update([
            'uf' => 'RJ',
            'tipo_instalacao' => TipoInstalacao::METALICO->value,
        ]);

        $this->assertEquals('RJ', $projeto->fresh()->uf);
        $this->assertEquals('Metálico', $projeto->fresh()->tipo_instalacao);
    }

    public function test_pode_deletar_projeto()
    {
        $projeto = Projeto::create([
            'cliente_id' => 1,
            'uf' => 'SP',
            'tipo_instalacao' => TipoInstalacao::LAJE->value,
        ]);

        $id = $projeto->id;
        $projeto->delete();

        $this->assertDatabaseMissing('projetos', ['id' => $id]);
    }

    public function test_deleta_equipamentos_ao_deletar_projeto()
    {
        $projeto = Projeto::create([
            'cliente_id' => 1,
            'uf' => 'BA',
            'tipo_instalacao' => TipoInstalacao::SOLO->value,
        ]);

        $projeto->equipamentos()->attach(1, ['quantidade' => 10]);

        $projectId = $projeto->id;
        $projeto->delete();

        $this->assertDatabaseMissing('projeto_equipamento', ['projeto_id' => $projectId]);
    }

    public function test_tipo_instalacao_pode_ser_castado_para_enum()
    {
        $projeto = Projeto::create([
            'cliente_id' => 1,
            'uf' => 'SP',
            'tipo_instalacao' => TipoInstalacao::CERAMICO->value,
        ]);

        $this->assertInstanceOf(TipoInstalacao::class, $projeto->tipo_instalacao);
        $this->assertEquals(TipoInstalacao::CERAMICO, $projeto->tipo_instalacao);
    }
}
