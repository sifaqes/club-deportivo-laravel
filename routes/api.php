<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeportesController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register',[AuthController::class,'register']);

Route::post('login',[AuthController::class,'login']);

Route::get('refresh',[AuthController::class,'refresh']);

Route::middleware('auth:sanctum')->group( function (){

    Route::get('deportes',[DeportesController::class,'index']);

    Route::post('update',[AuthController::class,'update']);

    Route::get('logout',[AuthController::class,'logout']);

});


