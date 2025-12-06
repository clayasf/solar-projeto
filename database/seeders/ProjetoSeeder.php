<?php

namespace Database\Seeders;

use App\Infrastructure\Eloquent\Projeto;
use App\Domain\Enums\TipoInstalacao;
use Illuminate\Database\Seeder;

class ProjetoSeeder extends Seeder
{
    public function run(): void
    {
        // Projeto 1
        $projeto1 = Projeto::create([
            'cliente_id' => 1,
            'uf' => 'SP',
            'tipo_instalacao' => TipoInstalacao::CERAMICO->value,
        ]);

        // Adicionar equipamentos ao projeto 1
        $projeto1->equipamentos()->attach([
            1 => ['quantidade' => 10],  // Painel Solar 550W
            2 => ['quantidade' => 2],   // Inversor 5kW
            4 => ['quantidade' => 100], // Cabo Solar 6mm²
        ]);

        // Projeto 2
        $projeto2 = Projeto::create([
            'cliente_id' => 2,
            'uf' => 'RJ',
            'tipo_instalacao' => TipoInstalacao::METALICO->value,
        ]);

        // Adicionar equipamentos ao projeto 2
        $projeto2->equipamentos()->attach([
            1 => ['quantidade' => 15],  // Painel Solar 550W
            3 => ['quantidade' => 3],   // Microinversor
            5 => ['quantidade' => 50],  // Estrutura
        ]);

        // Projeto 3
        $projeto3 = Projeto::create([
            'cliente_id' => 3,
            'uf' => 'MG',
            'tipo_instalacao' => TipoInstalacao::LAJE->value,
        ]);

        // Adicionar equipamentos ao projeto 3
        $projeto3->equipamentos()->attach([
            1 => ['quantidade' => 8],   // Painel Solar 550W
            2 => ['quantidade' => 1],   // Inversor 5kW
        ]);
    }
}
