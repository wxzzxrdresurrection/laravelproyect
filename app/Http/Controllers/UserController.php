<?php

namespace App\Http\Controllers;

use App\Jobs\CorreoVerificacion;
use App\Jobs\SMSVerificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use League\Flysystem\UrlGeneration\TemporaryUrlGenerator;

class UserController extends Controller
{
    public function registrarUsuario(Request $request){

        $validacion = Validator::make([
            $request->all()
        ],
        [
            "nombre" => "required|string|max:40",
            "ap_paterno" => "required|string|max:40",
            "ap_materno" => "required|string|max:40",
            "telefono" => "required|string|max:10",
            "correo" => "required|string|max:80|email:rfc:dns",
            "password" => "required|string|min:8"

        ],
        [
            "nombre.required" => "El campo :attribute es obligatorio",
            "nombre.string" => "El campo :attribute debe ser de tipo string",
            "nombre.max" => "El campo :attribute debe tener máximo :max caracteres",
            "ap_paterno.required" => "El campo :attribute es obligatorio",
            "ap_paterno.string" => "El campo :attribute debe ser de tipo string",
            "ap_paterno.max" => "El campo :attribute debe tener máximo :max caracteres",
            "ap_materno.required" => "El campo :attribute es obligatorio",
            "ap_materno.string" => "El campo :attribute debe ser de tipo string",
            "ap_materno.max" => "El campo :attribute debe tener máximo :max caracteres",
            "correo.required" => "El campo :attribute es obligatorio",
            "correo.string" => "El campo :attribute debe ser de tipo string",
            "correo.max" => "El campo :attribute debe tener máximo :max caracteres",
            "telefono.required" => "El campo :attribute es obligatorio",
            "telefono.string" => "El campo :attribute debe ser de tipo string",
            "telefono.max" => "El campo :attribute debe tener máximo :max caracteres",
            "password.required" => "El campo :attribute es obligatorio",
            "password.string" => "El campo :attribute debe ser de tipo string",
            "password.min" => "El campo :attribute debe tener minimo :min caracteres"
        ]);

        if($validacion->fails()){

            return response()->json([
                "status" => 400,
                "message" => "Ocurrió un error en las validaciones",
                "errors" => $validacion->failed(),
                "data" => null
            ],200);
        }

        $user = User::create([
            "nombre" => $request->nombre,
            "correo" => $request->correo,
            "ap_paterno" => $request->ap_paterno,
            "ap_materno" => $request->ap_materno,
            "telefono" => $request->telefono,
            "password" => Hash::make($request->password)
        ]);

        if($user->save()){

            $url = URL::temporarySignedRoute('verifymail',now()->addMinutes(20),['id'=>$user->id]);
            CorreoVerificacion::dispatch($user,$url)->delay(now()->addSeconds(20))->onQueue('email')->onConnection('database');

            return response()->json([
                "status" => 200,
                "message" => "Usuario creado de manera exitosa",
                "errors" => null,
                "data" => $user
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Error al crear el usuario",
            "errors" => [],
            "data" => null
        ],400);

    }

    public function verificarCorreo(Request $request){

        if(!$request->hasValidSignature())
        abort(401);


        
        $user = User::find($request->id);
        URL::temporarySignedRoute('verifyphone',now()->addMinutes(20),['id'=>$user->id]);

        $randomcode = rand(100000,999999);
        $user->codigoSMS = $randomcode;
        $user->save();

        SMSVerificacion::dispatch()->delay(now()->addSeconds(15))->onQueue('sms')->onConnection('database');

        

    }

    public function enviarSMS(Request $request){

    }

    public function verificarSMS(Request $request){
        
    }

    public function login(Request $request){

    }

    public function logout(Request $request){

    }
}
