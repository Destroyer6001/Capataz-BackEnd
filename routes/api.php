<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\HerramientaController;
use App\Http\Controllers\TareaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/users/create', [UserController::class, 'Register']);
Route::post('/users/login', [UserController::class, 'Login']);



Route::middleware(['auth:sanctum', 'role.api:user'])->group(function () {

    Route::get('/users/logoutUser', [UserController::class, 'LogoutUser']);
    Route::put('/users/{id}', [UserController::class, 'Edit']);
    Route::get('/cultivos', [CultivoController::class, 'show']);
    Route::get('/cultivos/{id}', [CultivoController::class, 'showById']);
    Route::post('/cultivos/store', [CultivoController::class, 'store']);
    Route::put('/cultivos/{id}', [CultivoController::class, 'update']);
    Route::delete('/cultivos/{id}', [CultivoController::class, 'destroy']);
    Route::get('/lotes', [LoteController::class, 'show']);
    Route::get('/lotes/{id}', [LoteController::class, 'showById']);
    Route::post('/lotes/store', [LoteController::class, 'store']);
    Route::put('/lotes/{id}', [LoteController::class, 'update']);
    Route::delete('/lotes/{id}', [LoteController::class, 'destroy']);
    Route::get('/empleados', [EmpleadoController::class, 'show']);
    Route::get('/empleados/{id}', [EmpleadoController::class, 'showById']);
    Route::post('/empleados/store', [EmpleadoController::class, 'store']);
    Route::put('/empleados/{id}', [EmpleadoController::class, 'update']);
    Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy']);
    Route::get('/tareas', [TareaController::class, 'show']);
    Route::get('/tareas/{id}', [TareaController::class, 'showById']);
    Route::post('/tareas/store', [TareaController::class, 'store']);
    Route::put('/tareas/{id}', [TareaController::class, 'update']);
    Route::delete('/tareas/{id}', [TareaController::class, 'destroy']);
    Route::get('/herramientas', [HerramientaController::class, 'show']);
    Route::get('/herramientas/{id}', [HerramientaController::class, 'showById']);
    Route::post('/herramientas/store', [HerramientaController::class, 'store']);
    Route::put('/herramientas/{id}', [HerramientaController::class, 'update']);
    Route::delete('/herramientas/{id}', [HerramientaController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role.api:empleado'])->group(function () {

    Route::get('/users/logout', [UserController::class, 'Logout']);
    Route::put('/empleado/edit/{id}', [UserController::class, 'editEmployee']);
});
