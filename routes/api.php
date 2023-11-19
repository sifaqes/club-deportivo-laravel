<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeportesController;
use App\Http\Controllers\PistasController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\SociosController;
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
    Route::post('reservas', [ReservasController::class,'store']);
    Route::get('reservas/search', [ReservasController::class,'index']);
    Route::delete('reservas', [ReservasController::class,'destroy']);
    Route::put('reservas', [ReservasController::class,'update']);


    // DeportesController
    Route::post('deportes', [DeportesController::class,'store']);
    Route::get('deportes', [DeportesController::class,'index']);
    Route::put('deportes', [DeportesController::class,'update']);
    Route::delete('deportes', [DeportesController::class,'destroy']);

    // PistasController
    Route::post('pistas', [PistasController::class,'store']);
    Route::get('pistas', [PistasController::class,'index']);
    Route::put('pistas', [PistasController::class,'update']);
    Route::delete('pistas', [PistasController::class,'destroy']);

    // SociosController
    Route::post('socios', [SociosController::class,'store']);
    Route::get('socios', [SociosController::class,'index']);
    Route::put('socios', [SociosController::class,'update']);
    Route::delete('socios', [SociosController::class,'destroy']);

});




