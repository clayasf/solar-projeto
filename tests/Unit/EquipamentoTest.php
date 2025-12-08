<?php

namespace Tests\Unit;

use App\Infrastructure\Eloquent\Equipamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipamentoTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_criar_equipamento_com_dados_validos()
    {
        $equipamento = Equipamento::create([
            'nome' => 'Painel Solar 550W',
            'tipo' => 'Módulo',
            'preco_unitario' => 899.90,
            'estoque_atual' => 50,
            'ativo' => true,
        ]);

        $this->assertDatabaseHas('equipamentos', [
            'id' => $equipamento->id,
            'nome' => 'Painel Solar 550W',
            'tipo' => 'Módulo',
        ]);
    }

    public function test_pode_atualizar_equipamento()
    {
        $equipamento = Equipamento::create([
            'nome' => 'Painel Solar 550W',
            'tipo' => 'Módulo',
            'preco_unitario' => 899.90,
            'estoque_atual' => 50,
            'ativo' => true,
        ]);

        $equipamento->update([
            'preco_unitario' => 950.00,
            'estoque_atual' => 100,
        ]);

        $this->assertEquals(950.00, $equipamento->fresh()->preco_unitario);
        $this->assertEquals(100, $equipamento->fresh()->estoque_atual);
    }

    public function test_pode_deletar_equipamento()
    {
        $equipamento = Equipamento::create([
            'nome' => 'Inversor 5kW',
            'tipo' => 'Inversor',
            'preco_unitario' => 4500.00,
            'estoque_atual' => 10,
            'ativo' => true,
        ]);

        $id = $equipamento->id;
        $equipamento->delete();

        $this->assertDatabaseMissing('equipamentos', ['id' => $id]);
    }

    public function test_equipamento_pode_estar_ativo_ou_inativo()
    {
        $ativo = Equipamento::create([
            'nome' => 'Equipamento Ativo',
            'tipo' => 'Estrutura',
            'preco_unitario' => 500.00,
            'estoque_atual' => 20,
            'ativo' => true,
        ]);

        $inativo = Equipamento::create([
            'nome' => 'Equipamento Inativo',
            'tipo' => 'Cabo vermelho',
            'preco_unitario' => 50.00,
            'estoque_atual' => 0,
            'ativo' => false,
        ]);

        $this->assertTrue($ativo->ativo);
        $this->assertFalse($inativo->ativo);
    }

    public function test_tipo_equipamento_pode_ter_categoria()
    {
        $equipamento = Equipamento::create([
            'nome' => 'Painel Solar 550W',
            'tipo' => 'Módulo',
            'preco_unitario' => 899.90,
            'estoque_atual' => 50,
            'ativo' => true,
        ]);

        // O accessor deve retornar a categoria
        $categoria = $equipamento->categoria;
        $this->assertEquals('Geração', $categoria);
    }

    public function test_multiplos_equipamentos_podem_ser_listados()
    {
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

        $equipamentos = Equipamento::all();
        $this->assertEquals(2, $equipamentos->count());
    }
}
