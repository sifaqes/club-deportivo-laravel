<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeportesController;
use App\Http\Controllers\PistasController;
use App\Http\Controllers\ReservasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('login',[AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);

Route::middleware('auth:sanctum')->group( function (){

    // UsuariosController
    Route::apiResource('profil',AuthController::class);
    Route::apiResource('update',AuthController::class);
    Route::apiResource('delete',AuthController::class);
    Route::get('logout',[AuthController::class,'logout']);

    // ReservasController
    Route::get('reservas', [ReservasController::class,'index']);
    Route::post('reservas', [ReservasController::class,'store']);
    Route::delete('reservas', [ReservasController::class,'destroy']);
    Route::put('reservas', [ReservasController::class,'update']);

    Route::get('deportes', [DeportesController::class,'index']);
    Route::post('deportes', [DeportesController::class,'store']);
    Route::delete('deportes', [DeportesController::class,'destroy']);
    Route::put('deportes', [DeportesController::class,'update']);

    Route::apiResource('pistas', PistasController::class);

    Route::apiResource('socios', PistasController::class);

});




