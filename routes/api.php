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
    Route::get('/correo/{id}',[UserController::class,'verifymail'])->name('verifymail')->middleware('signed')->where('id', '[0-9]+');
    Route::get('/verificar/correo/{id}',[UserController::class,'verificarCorreoActivo'])->where('id', '[0-9]+');
    Route::get('/enviar/sms/{id}',[UserController::class,'enviarSMS'])->where('id', '[0-9]+');
    Route::get('/enviar/sms/twilio/{id}',[UserController::class,'enviarCodigoTwilio'])->where('id', '[0-9]+');
    Route::post('/telefono/{id}',[UserController::class,'verificarSMS'])->name('verifyphone')->middleware('signed')->where('id', '[0-9]+');
    Route::post('/login',[UserController::class,'login']);
});

Route::prefix('/usuario')->group(function(){
    Route::get('/datos',[AdafruitController::class,'aguaLecturaUsuario']);
    Route::get('/grupo',[AdafruitController::class,'datosGrupo']);
});

Route::prefix('/casa')->middleware(['auth:sanctum','active','roles:1'])->group(function(){
    Route::post('/nueva',[AdminController::class,'createCasa']);
    Route::post('/modificar/{id}',[AdminController::class,'modificarCasa'])->where('id', '[0-9]+');
    Route::post('/eliminar/{id}',[AdminController::class,'eliminarCasa'])->where('id', '[0-9]+');
    Route::post('/feeds',[AdminController::class, 'crearFeeds']);
    Route::post('/asignar',[AdminController::class,'asignarCasa']);
    Route::get('/usuario/{id}',[AdminController::class,'casasDeCadaUsuario'])->where('id', '[0-9]+');
});

Route::prefix('/user')->middleware(['auth:sanctum','active','roles:2'])->group( function(){
    Route::get('/casas',[CasaController::class,'misCasas']);
    //Route::get('/sensores',[CasaController::class,'infoCasa']);
    Route::prefix('/sensor')->group(function(){
        Route::get('/comida',[CasaController::class,'comidaLectura']);
        Route::get('/agua',[CasaController::class,'aguaLectura']);
        Route::get('/peso',[CasaController::class,'pesoLectura']);
        Route::post('/lluvia',[CasaController::class,'lluviaLectura']);
        Route::get('/temperatura',[CasaController::class,'temperaturaLectura']);
        Route::get('/iluminacion',[CasaController::class,'iluminacionLectura']);
    });

});

Route::prefix('/admin')->middleware(['active','auth:sanctum','roles:1'])->group(function(){
    Route::get('/users',[AdminController::class,'verUsuarios']);
    Route::get('/casas',[AdminController::class,'verCasas']);
    Route::get('/relacion',[AdminController::class,'casasUsuario']);

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
//Route::post('/nuevo/grupo',[AdafruitController::class,'datosCasa']);
