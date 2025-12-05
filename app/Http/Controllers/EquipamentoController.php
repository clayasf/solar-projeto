<?php

namespace App\Http\Controllers;

use App\Infrastructure\Eloquent\Equipamento;
use App\Http\Requests\StoreEquipamentoRequest;
use App\Http\Requests\UpdateEquipamentoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Domain\Enums\TipoEquipamento;

class EquipamentoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Equipamento::query();
        
        // Filtro por tipo
        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        // Filtro por categoria (via tipo)
        if ($request->has('categoria')) {
            $tipos = [];
            foreach (TipoEquipamento::cases() as $tipo) {
                if ($tipo->categoria() === $request->categoria) {
                    $tipos[] = $tipo->value;
                }
            }
            $query->whereIn('tipo', $tipos);
        }
        
        // Filtro por ativo
        if ($request->has('ativo')) {
            $query->where('ativo', filter_var($request->ativo, FILTER_VALIDATE_BOOLEAN));
        }
        
        // Busca por nome
        if ($request->has('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }
        
        return response()->json($query->get());
    }

    public function store(StoreEquipamentoRequest $request): JsonResponse
    {
        $equipamento = Equipamento::create($request->validated());
        return response()->json($equipamento, 201);
    }

    public function show(Equipamento $equipamento): JsonResponse
    {
        return response()->json($equipamento);
    }

    public function update(UpdateEquipamentoRequest $request, Equipamento $equipamento): JsonResponse
    {
        $equipamento->update($request->validated());
        return response()->json($equipamento);
    }

    public function destroy(Equipamento $equipamento): JsonResponse
    {
        $equipamento->delete();
        return response()->json(null, 204);
    }
}