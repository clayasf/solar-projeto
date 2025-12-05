<?php
// app/Http/Requests/UpdateClienteRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:clientes,email,' . $this->cliente->id,
            'telefone' => 'sometimes|string|max:20',
            'cpf_cnpj' => 'sometimes|string|max:20|unique:clientes,cpf_cnpj,' . $this->cliente->id,
        ];
    }
}