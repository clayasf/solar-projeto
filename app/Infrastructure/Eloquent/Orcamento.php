<?php
// app/Infrastructure/Eloquent/Orcamento.php

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Orcamento extends Model
{
    protected $fillable = ['cliente_id'];

    // Relacionamento com cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacionamento muitos-para-muitos com equipamentos
    public function equipamentos(): BelongsToMany
    {
        return $this->belongsToMany(Equipamento::class, 'orcamento_equipamento')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }

    // Calcular total do orçamento
    public function getTotalAttribute(): float
    {
        return $this->equipamentos->sum(function ($equipamento) {
            return $equipamento->preco_unitario * $equipamento->pivot->quantidade;
        });
    }
}