<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\MovimientosController;
use App\Http\Controllers\LaborController;
use App\Http\Controllers\HerramientaController;
use App\Http\Controllers\PrestamosController;
use App\Http\Controllers\TareasController;
use App\Http\Controllers\RecoleccionController;
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

Route::post('/users/create',[UserController::class,'Register']);
Route::post('/users/login',[UserController::class,'Login']);



Route::middleware(['auth:sanctum','role.api:user'])->group(function(){

    Route::get('/users/logoutUser',[UserController::class,'LogoutUser']);
    Route::put('/users/{id}',[UserController::class,'Edit']);
    Route::get('/cultivos',[CultivoController::class, 'show']);
    Route::get('/cultivos/{id}',[CultivoController::class,'showById']);
    Route::post('/cultivos/store',[CultivoController::class,'store']);
    Route::put('/cultivos/{id}',[CultivoController::class,'update']);
    Route::delete('/cultivos/{id}',[CultivoController::class,'destroy']);
    Route::get('/lotes',[LoteController::class,'show']); 
    Route::get('/lotes/{id}',[LoteController::class,'showById']);
    Route::post('/lotes/store',[LoteController::class,'store']);
    Route::put('/lotes/{id}',[LoteController::class,'update']);
    Route::delete('/lotes/{id}',[LoteController::class,'destroy']);
    Route::get('/empleados',[EmpleadoController::class,'show']);
    Route::get('/empleados/{id}',[EmpleadoController::class,'showById']);
    Route::post('/empleados/store',[EmpleadoController::class,'store']);
    Route::put('/empleados/{id}',[EmpleadoController::class,'update']);
    Route::delete('/empleados/{id}',[EmpleadoController::class,'destroy']);
    Route::get('/productos',[ProductosController::class,'show']);
    Route::get('/productos/{id}',[ProductosController::class,'showById']);
    Route::post('/productos/store',[ProductosController::class,'store']);
    Route::put('/productos/{id}',[ProductosController::class,'update']);
    Route::get('/movimientos/{id}',[MovimientosController::class,'showByProducto']);
    Route::post('/movimientos/store',[MovimientosController::class,'store']);
    Route::patch('/productos/dissable/{id}',[ProductosController::class, 'dissable']);
    Route::get('/labores',[LaborController::class,'show']);
    Route::get('/labores/{id}',[LaborController::class,'showById']);
    Route::post('/labores/store',[LaborController::class,'store']);
    Route::put('/labores/{id}',[LaborController::class,'update']);
    Route::delete('/labores/{id}',[LaborController::class,'destroy']);
    Route::get('/herramientas',[HerramientaController::class,'show']);
    Route::get('/herramientas/{id}',[HerramientaController::class,'showById']);
    Route::post('/herramientas/store',[HerramientaController::class,'store']);
    Route::put('/herramientas/{id}',[HerramientaController::class,'update']);
    Route::delete('/herramientas/{id}',[HerramientaController::class,'destroy']);
    Route::get('/tareas',[TareasController::class,'show']);
    Route::get('/tareas/{id}',[TareasController::class,'showById']);
    Route::post('/tareas/store',[TareasController::class,'store']);
    Route::put('/tareas/{id}',[TareasController::class,'update']);
    Route::patch('/tareas/{id}',[TareasController::class,'CambioDeEstado']);
    Route::get('/prestamos',[PrestamosController::class,'show']);
    Route::get('/prestamos/{id}',[PrestamosController::class,'showById']);
    Route::post('/prestamos/store',[PrestamosController::class,'store']);
    Route::put('/prestamos/{id}',[PrestamosController::class,'update']);
    Route::patch('/prestamos/{id}',[PrestamosController::class,'CambioDeEstado']);
    Route::get('/recoleccion',[RecoleccionController::class,'show']);
    Route::get('/recoleccion/{id}',[RecoleccionController::class,'showById']);
    Route::post('/recoleccion/store',[RecoleccionController::class,'store']);
});

Route::middleware(['auth:sanctum','role.api:empleado'])->group(function(){

    Route::get('/users/logout',[UserController::class,'Logout']);
    Route::put('/empleado/edit/{id}',[UserController::class,'editEmployee']);
    Route::get('/employee/gather',[RecoleccionController::class,'ShowByEmployee']);
    Route::get('/employee/tasks',[TareasController::class,'ShowByEmployee']);
    Route::get('/employee/loans',[PrestamosController::class,'ShowByEmployee']);
   
});






