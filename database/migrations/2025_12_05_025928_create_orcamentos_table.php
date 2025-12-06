<?php
// database/migrations/xxxx_create_projetos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domain\Enums\TipoInstalacao;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->enum('uf', ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO']);
            $table->enum('tipo_instalacao', array_column(TipoInstalacao::cases(), 'value'));
            $table->timestamps();
            
            $table->index('cliente_id');
            $table->index('uf');
            $table->index('tipo_instalacao');
        });

        // Tabela pivot para equipamentos do projeto
        Schema::create('projeto_equipamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->foreignId('equipamento_id')->constrained()->onDelete('cascade');
            $table->integer('quantidade');
            $table->timestamps();
            
            // Garantir que não tenha duplicatas
            $table->unique(['projeto_id', 'equipamento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projeto_equipamento');
        Schema::dropIfExists('projetos');
    }
};