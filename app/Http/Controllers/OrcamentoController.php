<?php
// app/Http/Controllers/OrcamentoController.php

namespace App\Http\Controllers;

use App\Infrastructure\Eloquent\Orcamento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Infrastructure\Eloquent\Equipamento;

class OrcamentoController extends Controller
{
    public function index(): JsonResponse
    {
        $orcamentos = Orcamento::with(['cliente', 'equipamentos'])->get();
        return response()->json($orcamentos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'equipamentos' => 'required|array|min:1',
            'equipamentos.*.equipamento_id' => 'required|exists:equipamentos,id',
            'equipamentos.*.quantidade' => 'required|integer|min:1',
        ]);

        foreach ($validated['equipamentos'] as $item) {
            $equipamento = Equipamento::find($item['equipamento_id']);
            
            if ($equipamento->estoque_atual < $item['quantidade']) {
                return response()->json([
                    'message' => "Estoque insuficiente para {$equipamento->nome}. Disponível: {$equipamento->estoque_atual}",
                    'equipamento' => $equipamento->nome,
                    'estoque_disponivel' => $equipamento->estoque_atual,
                    'quantidade_solicitada' => $item['quantidade']
                ], 422);
            }
        }

        DB::beginTransaction();
        
        try {
            $orcamento = Orcamento::create([
                'cliente_id' => $validated['cliente_id']
            ]);

            // Adiciona equipamentos ao orçamento
            foreach ($validated['equipamentos'] as $item) {
                $orcamento->equipamentos()->attach($item['equipamento_id'], [
                    'quantidade' => $item['quantidade']
                ]);
            }

            DB::commit();

            return response()->json($orcamento->load(['cliente', 'equipamentos']), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao criar orçamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Orcamento $orcamento): JsonResponse
    {
        $orcamento->load(['cliente', 'equipamentos']);
        return response()->json($orcamento);
    }

    public function update(Request $request, Orcamento $orcamento): JsonResponse
    {
        $validated = $request->validate([
            'equipamentos' => 'required|array|min:1',
            'equipamentos.*.equipamento_id' => 'required|exists:equipamentos,id',
            'equipamentos.*.quantidade' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        
        try {
            // Remove todos os equipamentos atuais
            $orcamento->equipamentos()->detach();
            
            // Adiciona os novos equipamentos
            foreach ($validated['equipamentos'] as $item) {
                $orcamento->equipamentos()->attach($item['equipamento_id'], [
                    'quantidade' => $item['quantidade']
                ]);
            }

            DB::commit();

            return response()->json($orcamento->load(['cliente', 'equipamentos']));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao atualizar orçamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Orcamento $orcamento): JsonResponse
    {
        $orcamento->delete();
        return response()->json(null, 204);
    }
}