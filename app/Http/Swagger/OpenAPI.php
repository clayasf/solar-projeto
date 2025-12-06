<?php

namespace App\Http\Swagger;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Solar Projeto API",
 *      description="API para gestão de projetos de energia solar",
 *      @OA\Contact(
 *          email="contato@solarprojeto.com.br",
 *          name="Suporte Solar Projeto"
 *      ),
 * )
 *
 * @OA\Server(
 *      url="http://localhost:8989",
 *      description="Servidor local via Docker"
 * )
 *
 * @OA\Components(
 *      @OA\Schema(
 *          schema="Projeto",
 *          type="object",
 *          @OA\Property(property="id", type="integer", example=1),
 *          @OA\Property(property="cliente_id", type="integer", example=1),
 *          @OA\Property(property="uf", type="string", example="SP"),
 *          @OA\Property(property="tipo_instalacao", type="string", example="Cerâmico"),
 *          @OA\Property(property="created_at", type="string", format="date-time"),
 *          @OA\Property(property="updated_at", type="string", format="date-time")
 *      ),
 *      @OA\Schema(
 *          schema="Cliente",
 *          type="object",
 *          @OA\Property(property="id", type="integer", example=1),
 *          @OA\Property(property="nome", type="string", example="João Silva"),
 *          @OA\Property(property="email", type="string", example="joao@email.com"),
 *          @OA\Property(property="telefone", type="string", example="(11) 99999-9999"),
 *          @OA\Property(property="cpf_cnpj", type="string", example="123.456.789-09"),
 *          @OA\Property(property="created_at", type="string", format="date-time"),
 *          @OA\Property(property="updated_at", type="string", format="date-time")
 *      ),
 *      @OA\Schema(
 *          schema="Equipamento",
 *          type="object",
 *          @OA\Property(property="id", type="integer", example=1),
 *          @OA\Property(property="nome", type="string", example="Painel Solar 550W"),
 *          @OA\Property(property="tipo", type="string", example="Módulo"),
 *          @OA\Property(property="preco_unitario", type="number", format="float", example=899.90),
 *          @OA\Property(property="estoque_atual", type="integer", example=50),
 *          @OA\Property(property="ativo", type="boolean", example=true),
 *          @OA\Property(property="created_at", type="string", format="date-time"),
 *          @OA\Property(property="updated_at", type="string", format="date-time")
 *      )
 * )
 */
class OpenAPI
{
}
