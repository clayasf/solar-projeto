<?php

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf_cnpj'  
    ];

    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class);
    }
}