<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeportesController;
use App\Http\Controllers\PistasController;
use App\Http\Controllers\ReservasController;
use Illuminate\Http\Request;
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


    Route::apiResource('reservas/list', ReservasController::class);
    Route::apiResource('reservas/create', ReservasController::class);
    Route::apiResource('reservas/read', ReservasController::class);
    Route::apiResource('reservas/upload', ReservasController::class);
    Route::apiResource('reservas/delete', ReservasController::class);



    Route::apiResource('deportes/list',DeportesController::class);
    Route::apiResource('deportes/create',DeportesController::class);
    Route::apiResource('deportes/read',DeportesController::class);
    Route::apiResource('deportes/upload',DeportesController::class);
    Route::apiResource('deportes/delete',DeportesController::class);



    Route::apiResource('pistas/list', PistasController::class);
    Route::apiResource('pistas/create', PistasController::class);
    Route::apiResource('pistas/read', PistasController::class);
    Route::apiResource('pistas/upload', PistasController::class);
    Route::apiResource('pistas/delete', PistasController::class);

    Route::apiResource('socios/list', PistasController::class);
    Route::apiResource('socios/create', PistasController::class);
    Route::apiResource('socios/read', PistasController::class);
    Route::apiResource('socios/upload', PistasController::class);
    Route::apiResource('socios/delete', PistasController::class);
});




