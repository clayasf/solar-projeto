<?php
// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers;

use App\Infrastructure\Eloquent\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    public function index(): JsonResponse
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    public function store(StoreClienteRequest $request): JsonResponse
    {
        $cliente = Cliente::create($request->validated());
        return response()->json($cliente, 201);
    }

    public function show(Cliente $cliente): JsonResponse
    {
        return response()->json($cliente);
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente): JsonResponse
    {
        $cliente->update($request->validated());
        return response()->json($cliente);
    }

    public function destroy(Cliente $cliente): JsonResponse
    {
        $cliente->delete();
        return response()->json(null, 204);
    }
}