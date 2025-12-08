<?php
// app/Http/Controllers/ProjetoController.php

namespace App\Http\Controllers;

use App\Domain\Enums\TipoInstalacao;
use App\Infrastructure\Eloquent\Projeto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Infrastructure\Eloquent\Equipamento;

class ProjetoController extends Controller
{
    private const ESTADOS_BR = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];

    /**
     * @OA\Get(
     *      path="/api/projetos",
     *      operationId="getProjetos",
     *      tags={"Projetos"},
     *      summary="Listar todos os projetos",
     *      description="Retorna a lista de todos os projetos cadastrados",
     *      @OA\Response(
     *          response=200,
     *          description="Sucesso"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor"
     *      )
     * )
     */
    public function index(): JsonResponse
    {
        $projetos = Projeto::with(['cliente', 'equipamentos'])->get();
        return response()->json($projetos);
    }

    public function store(Request $request): JsonResponse
    {
        /**
         * @OA\Post(
         *      path="/api/projetos",
         *      operationId="storeProjeto",
         *      tags={"Projetos"},
         *      summary="Criar um novo projeto",
         *      description="Cria um novo projeto de energia solar",
         *      @OA\RequestBody(
         *          required=true,
         *          description="Dados do projeto",
         *          @OA\JsonContent(
         *              required={"cliente_id","uf","tipo_instalacao","equipamentos"},
         *              @OA\Property(property="cliente_id", type="integer", example=1),
         *              @OA\Property(property="uf", type="string", example="SP"),
         *              @OA\Property(property="tipo_instalacao", type="string", example="Cerâmico"),
         *              @OA\Property(property="equipamentos", type="array", @OA\Items(
         *                  @OA\Property(property="equipamento_id", type="integer", example=1),
         *                  @OA\Property(property="quantidade", type="integer", example=10)
         *              ))
         *          )
         *      ),
         *      @OA\Response(
         *          response=201,
         *          description="Projeto criado com sucesso"
         *      ),
         *      @OA\Response(
         *          response=422,
         *          description="Dados inválidos ou estoque insuficiente"
         *      )
         * )
         */
        $tiposInstalacao = array_column(TipoInstalacao::cases(), 'value');

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'uf' => 'required|in:' . implode(',', self::ESTADOS_BR),
            'tipo_instalacao' => 'required|in:' . implode(',', $tiposInstalacao),
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
            $projeto = Projeto::create([
                'cliente_id' => $validated['cliente_id'],
                'uf' => $validated['uf'],
                'tipo_instalacao' => $validated['tipo_instalacao'],
            ]);

            // Adiciona equipamentos ao projeto
            foreach ($validated['equipamentos'] as $item) {
                $projeto->equipamentos()->attach($item['equipamento_id'], [
                    'quantidade' => $item['quantidade']
                ]);
            }

            DB::commit();

            return response()->json($projeto->load(['cliente', 'equipamentos']), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao criar projeto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Projeto $projeto): JsonResponse
    {
        /**
         * @OA\Get(
         *      path="/api/projetos/{id}",
         *      operationId="showProjeto",
         *      tags={"Projetos"},
         *      summary="Obter detalhes de um projeto",
         *      description="Retorna os detalhes completos de um projeto específico",
         *      @OA\Parameter(
         *          name="id",
         *          description="ID do projeto",
         *          required=true,
         *          in="path",
         *          @OA\Schema(type="integer")
         *      ),
         *      @OA\Response(
         *          response=200,
         *          description="Projeto encontrado"
         *      ),
         *      @OA\Response(
         *          response=404,
         *          description="Projeto não encontrado"
         *      )
         * )
         */
        $projeto->load(['cliente', 'equipamentos']);
        return response()->json($projeto);
    }

    public function update(Request $request, Projeto $projeto): JsonResponse
    {
        /**
         * @OA\Put(
         *      path="/api/projetos/{id}",
         *      operationId="updateProjeto",
         *      tags={"Projetos"},
         *      summary="Atualizar um projeto",
         *      description="Atualiza os dados de um projeto existente",
         *      @OA\Parameter(
         *          name="id",
         *          description="ID do projeto",
         *          required=true,
         *          in="path",
         *          @OA\Schema(type="integer")
         *      ),
         *      @OA\RequestBody(
         *          description="Dados a atualizar",
         *          @OA\JsonContent(
         *              @OA\Property(property="uf", type="string", example="RJ"),
         *              @OA\Property(property="tipo_instalacao", type="string", example="Metálico"),
         *              @OA\Property(property="equipamentos", type="array", @OA\Items(
         *                  @OA\Property(property="equipamento_id", type="integer", example=1),
         *                  @OA\Property(property="quantidade", type="integer", example=5)
         *              ))
         *          )
         *      ),
         *      @OA\Response(
         *          response=200,
         *          description="Projeto atualizado com sucesso"
         *      ),
         *      @OA\Response(
         *          response=422,
         *          description="Dados inválidos"
         *      )
         * )
         */
        $tiposInstalacao = array_column(TipoInstalacao::cases(), 'value');

        $validated = $request->validate([
            'uf' => 'sometimes|in:' . implode(',', self::ESTADOS_BR),
            'tipo_instalacao' => 'sometimes|in:' . implode(',', $tiposInstalacao),
            'equipamentos' => 'sometimes|array|min:1',
            'equipamentos.*.equipamento_id' => 'required_with:equipamentos|exists:equipamentos,id',
            'equipamentos.*.quantidade' => 'required_with:equipamentos|integer|min:1',
        ]);

        DB::beginTransaction();
        
        try {
            // Atualiza campos de projeto se fornecidos
            if (isset($validated['uf']) || isset($validated['tipo_instalacao'])) {
                $projeto->update($request->only(['uf', 'tipo_instalacao']));
            }

            // Atualiza equipamentos se fornecidos
            if (isset($validated['equipamentos'])) {
                $projeto->equipamentos()->detach();
                
                foreach ($validated['equipamentos'] as $item) {
                    $projeto->equipamentos()->attach($item['equipamento_id'], [
                        'quantidade' => $item['quantidade']
                    ]);
                }
            }

            DB::commit();

            return response()->json($projeto->load(['cliente', 'equipamentos']));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao atualizar projeto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Projeto $projeto): JsonResponse
    {
        /**
         * @OA\Delete(
         *      path="/api/projetos/{id}",
         *      operationId="destroyProjeto",
         *      tags={"Projetos"},
         *      summary="Deletar um projeto",
         *      description="Remove um projeto do sistema",
         *      @OA\Parameter(
         *          name="id",
         *          description="ID do projeto",
         *          required=true,
         *          in="path",
         *          @OA\Schema(type="integer")
         *      ),
         *      @OA\Response(
         *          response=204,
         *          description="Projeto deletado com sucesso"
         *      ),
         *      @OA\Response(
         *          response=404,
         *          description="Projeto não encontrado"
         *      )
         * )
         */
        $projeto->delete();
        return response()->json(null, 204);
    }
}