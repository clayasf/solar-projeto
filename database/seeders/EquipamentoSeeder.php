<?php

namespace Database\Seeders;

use App\Infrastructure\Eloquent\Equipamento;
use App\Domain\Enums\TipoEquipamento;
use Illuminate\Database\Seeder;

class EquipamentoSeeder extends Seeder
{
    public function run(): void
    {
        Equipamento::create([
            'nome' => 'Painel Solar 550W',
            'tipo' => TipoEquipamento::MODULO,
            'preco_unitario' => 899.90,
            'estoque_atual' => 50,
            'ativo' => true,
        ]);

        Equipamento::create([
            'nome' => 'Inversor 5kW',
            'tipo' => TipoEquipamento::INVERSOR,
            'preco_unitario' => 4500.00,
            'estoque_atual' => 10,
            'ativo' => true,
        ]);

        Equipamento::create([
            'nome' => 'Cabo Solar 6mm²',
            'tipo' => TipoEquipamento::CABO_VERMELHO,
            'preco_unitario' => 15.90,
            'estoque_atual' => 200,
            'ativo' => true,
        ]);
    }
}