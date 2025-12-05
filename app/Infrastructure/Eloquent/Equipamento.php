<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\Enums\TipoEquipamento;
use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $fillable = [
        'nome',
        'tipo',
        'preco_unitario',
        'estoque_atual',
        'ativo'
    ];

    protected $casts = [
        'tipo' => TipoEquipamento::class,
        'preco_unitario' => 'decimal:2',
        'ativo' => 'boolean'
    ];

    // Accessor para categoria
    public function getCategoriaAttribute(): string
    {
        return $this->tipo->categoria();
    }
}