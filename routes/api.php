<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdafruitController;
use App\Http\Controllers\CasaController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('nuevo/usuario',[UserController::class,'registrarUsuario']);
Route::get('/correo/{id}',[tokenController::class,'verificarCorreo'])->name('verifymail')->middleware('signed');
Route::get('/telefono/{id}',[tokenController::class,'verificarSMS'])->name('verifyphone')->middleware('signed');

Route::prefix('/usuario')->group(function(){
Route::get('/datos',[AdafruitController::class,'aguaLecturaUsuario']);
Route::get('/grupo',[AdafruitController::class,'datosGrupo']);
});

Route::prefix('/casa')->group(function(){
    Route::post('/nueva',[CasaController::class,'nuevaCasa']);
    Route::post('/modificar/{id}',[CasaController::class,'modificarCasa']);
    Route::post('/eliminar/{id}',[CasaController::class,'eliminarCasa']);
});

Route::prefix('/sensor')->group(function(){
    Route::get('/peso',[AdafruitController::class,'pesoLectura']);
    Route::get('/temperatura',[AdafruitController::class,'temperaturaLectura']);
    Route::get('/infrarrojo',[AdafruitController::class,'infrarrojoLectura']);
    Route::get('/lluvia',[AdafruitController::class,'lluviaLectura']);
    Route::get('/agua',[AdafruitController::class,'aguaLectura']);
    Route::get('/iluminacion',[AdafruitController::class,'iluminacionLectura']);
});

//Route::post('/nuevo/feeds',[AdafruitController::class,'crearFeeds']);
Route::post('/nuevo/grupo',[AdafruitController::class,'datosCasa']);
