<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\LoteController;
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

    Route::put('/users/{id}',[UserController::class,'Edit']);
    Route::get('/users/logout',[UserController::class,'Logout']);
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

});






