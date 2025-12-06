<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\ProjetoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|AA
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('clientes', ClienteController::class);
Route::apiResource('equipamentos', EquipamentoController::class);
Route::apiResource('projetos', ProjetoController::class);

// Rotas para gerenciar equipamentos do projeto
// Route::post('/projetos/{projeto}/equipamentos', [ProjetoController::class, 'addEquipamento']);
// Route::delete('/projetos/{projeto}/equipamentos/{equipamento}', [ProjetoController::class, 'removeEquipamento']);