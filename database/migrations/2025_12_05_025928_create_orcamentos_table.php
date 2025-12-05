<?php
// database/migrations/xxxx_create_orcamentos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Tabela pivot para equipamentos do orçamento
        Schema::create('orcamento_equipamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipamento_id')->constrained()->onDelete('cascade');
            $table->integer('quantidade');
            $table->timestamps();
            
            // Garantir que não tenha duplicatas
            $table->unique(['orcamento_id', 'equipamento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orcamento_equipamento');
        Schema::dropIfExists('orcamentos');
    }
};