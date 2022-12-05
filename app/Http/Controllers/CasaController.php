<?php

namespace App\Http\Controllers;

use App\Models\Casa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CasaController extends Controller
{
    public function casasDeUsuario(){

        $results = Casa::join("users","casas.id","=","users.casa_id")->select("users.id","users.nombre","casas.nombre")->get();

        return response()->json([
            "status" => 200,
            "message" => "",
            "errors" => [],
            "data" => $results
        ],200);
    }

    public function nuevaCasa(Request $request){

        $validacion = Validator::make(
        $request->all(),
        [
            "nombre" => "required|max:30|string",
            "descripcion" => "max|string|"
        ],
        [
            "nombre.required" => "El campo :attribute es obligatorio",
            "nombre.max" => "El campo :attribute debe contener máximo :max caracteres",
            "nombre.string" => "El campo :attribute debe ser un texto",
            "descripcion.max" => "El campo :attribute debe contener máximo :max caracteres",
            "descripcion.string" => "El campo :attribute debe ser un texto"
        ]);

        if($validacion->fails()){
            return response()->json([
                "status" => 400,
                "message" => "Ocurrió un error en las validaciones",
                "errors" => $validacion->errors(),
                "data" => []
            ]);
        }

        $casa = Casa::create([
            "nombre" => $request->nombre
        ]);

        return response()->json([
            "status" => 200,
            "message" => "Casa creada de manera exitosa",
            "errors" => [],
            "data" => $casa
        ]);
    }

    public function modificarCasa(Request $request){

        //encontrar casa y cambair nombre
        $casa = Casa::all();

        $casa->nombre = $request->nombre;
        $casa->descripcion = $request->descripcion;
        

        return response()->json([
            "status" => 200,
            "message" => "Casa actualizada de manera exitosa...",
            "errors" => [],
            "data" => $casa
        ],200);
    }


}
