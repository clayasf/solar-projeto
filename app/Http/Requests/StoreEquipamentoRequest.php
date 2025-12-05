<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Domain\Enums\TipoEquipamento;

class StoreEquipamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string|in:' . implode(',', TipoEquipamento::valores()),
            'preco_unitario' => 'required|numeric|min:0',
            'estoque_atual' => 'required|integer|min:0',
            'ativo' => 'boolean',
        ];
    }
}