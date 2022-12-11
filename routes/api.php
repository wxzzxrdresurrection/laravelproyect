<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdafruitController;
use App\Http\Controllers\AdminController;
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

Route::prefix('/inicio')->group(function(){
    Route::post('/nuevo/usuario',[UserController::class,'registrarUsuario']);
    Route::get('/correo/{id}',[UserController::class,'verifymail'])->name('verifymail')->middleware('signed');
    Route::get('verificar/correo/{id}',[UserController::class,'verificarCorreoActivo']);
    Route::get('/enviar/sms/{id}',[UserController::class,'enviarSMS']);
    Route::get('/enviar/sms/twilio/{id}',[UserController::class,'enviarCodigoTwilio']);
    Route::post('/telefono/{id}',[UserController::class,'verificarSMS'])->name('verifyphone')->middleware('signed');
    Route::post('/login',[UserController::class,'login']);
});

Route::prefix('/usuario')->group(function(){
    Route::get('/datos',[AdafruitController::class,'aguaLecturaUsuario']);
    Route::get('/grupo',[AdafruitController::class,'datosGrupo']);
});

Route::prefix('/casa')->middleware(['auth:sanctum','active','roles:1'])->group(function(){
    Route::post('/nueva',[AdminController::class,'createCasa']);
    Route::post('/modificar/{id}',[AdminController::class,'modificarCasa']);
    Route::post('/eliminar/{id}',[AdminController::class,'eliminarCasa']);
    Route::post('feeds/',[AdminController::class, 'crearFeeds']);
    Route::post('/asignar',[AdminController::class,'asignarCasa']);
    Route::get('/usuario/{id}',[AdminController::class,'casasDeCadaUsuario']);
});

Route::prefix('/user')->middleware(['auth:sanctum','active','roles:1'])->group( function(){
    Route::get('/casas',[CasaController::class,'misCasas']);
    Route::get('/sensores',[CasaController::class,'infoCasa']);

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
