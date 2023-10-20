<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

});






