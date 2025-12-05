<?php

namespace App\Http\Requests;

use App\Rules\CpfCnpj;
use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clientes,email',
            'telefone' => 'nullable|string|max:20',
            'cpf_cnpj' => 'nullable|string|max:20|unique:clientes,cpf_cnpj',
        ];
    }

}