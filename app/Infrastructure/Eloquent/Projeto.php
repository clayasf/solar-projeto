<?php
// app/Infrastructure/Eloquent/Projeto.php

namespace App\Infrastructure\Eloquent;

use App\Domain\Enums\TipoInstalacao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Projeto extends Model
{
    protected $table = 'projetos';

    protected $fillable = [
        'cliente_id',
        'uf',
        'tipo_instalacao',
    ];

    protected $casts = [
        'tipo_instalacao' => TipoInstalacao::class,
    ];

    // Relacionamento com cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacionamento muitos-para-muitos com equipamentos
    public function equipamentos(): BelongsToMany
    {
        return $this->belongsToMany(Equipamento::class, 'projeto_equipamento')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }

    // Calcular total do projeto
    public function getTotal(): float
    {
        return $this->equipamentos->sum(function ($equipamento) {
            return $equipamento->preco_unitario * $equipamento->pivot->quantidade;
        });
    }
}