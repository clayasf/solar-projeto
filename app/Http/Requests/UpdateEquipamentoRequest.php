<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Domain\Enums\TipoEquipamento;

class UpdateEquipamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|max:255',
            'tipo' => 'sometimes|string|in:' . implode(',', TipoEquipamento::valores()),
            'preco_unitario' => 'sometimes|numeric|min:0',
            'estoque_atual' => 'sometimes|integer|min:0',
            'ativo' => 'sometimes|boolean',
        ];
    }
}