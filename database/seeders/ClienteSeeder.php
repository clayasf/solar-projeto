<?php
// database/seeders/ClienteSeeder.php

namespace Database\Seeders;

use App\Infrastructure\Eloquent\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::create([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'telefone' => '(11) 99999-9999',
            'cpf_cnpj' => '123.456.789-09', // CPF válido para teste
        ]);

        Cliente::create([
            'nome' => 'Empresa Solar LTDA',
            'email' => 'empresa@email.com',
            'telefone' => '(11) 88888-8888',
            'cpf_cnpj' => '12.345.678/0001-95', // CNPJ válido para teste
        ]);

        Cliente::create([
            'nome' => 'Maria Santos',
            'email' => 'maria@email.com',
            'telefone' => '(11) 77777-7777',
            'cpf_cnpj' => '987.654.321-00',
        ]);
    }
}