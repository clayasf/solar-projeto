<?php

namespace Tests\Unit;

use App\Infrastructure\Eloquent\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_criar_cliente_com_dados_validos()
    {
        $cliente = Cliente::create([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'telefone' => '(11) 99999-9999',
            'cpf_cnpj' => '123.456.789-09',
        ]);

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
        ]);
    }

    public function test_pode_atualizar_cliente()
    {
        $cliente = Cliente::create([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'telefone' => '(11) 99999-9999',
            'cpf_cnpj' => '123.456.789-09',
        ]);

        $cliente->update(['nome' => 'João da Silva']);

        $this->assertEquals('João da Silva', $cliente->fresh()->nome);
    }

    public function test_pode_deletar_cliente()
    {
        $cliente = Cliente::create([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'telefone' => '(11) 99999-9999',
            'cpf_cnpj' => '123.456.789-09',
        ]);

        $id = $cliente->id;
        $cliente->delete();

        $this->assertDatabaseMissing('clientes', ['id' => $id]);
    }

    public function test_cliente_pode_ter_multiplos_projetos()
    {
        $cliente = Cliente::create([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'telefone' => '(11) 99999-9999',
            'cpf_cnpj' => '123.456.789-09',
        ]);

        $projetos = $cliente->projetos;
        
        $this->assertIsCollection($projetos);
    }
}
