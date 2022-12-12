<?php

namespace App\Http\Controllers;

use App\Models\Casa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

define('apikey','aio_IMtU88xJQHKUyq7Xm6mi8UPqPC3V');

class CasaController extends Controller
{
    
    public function misCasas(Request $request){

        $user = User::find($request->user()->id);

        $casas = $user->casas;

        return response()->json([
            "status" => 200,
            "message" => "Casas encontradas de manera exitosa",
            "errors" => [],
            "casas" => [$casas]
        ]);

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

    public function aguaLectura(Request $request){

        $validacion = Validator::make(
            $request->all(),
            [
                "id" => "required|integer"
            ],
            [
                "id.required" => "El campo :attribute es obligatorio",
                "id.integer" => "El campo :attribute debe ser un número entero"
            ]);
        
            if($validacion->fails()){
                return response()->json([
                    "status" => 400,
                    "message" => "Ocurrió un error en las validaciones",
                    "errors" => $validacion->errors(),
                    "data" => []
                ]);
            }

        $casa = Casa::find($request->id);

        $response = Http::withHeaders([
            'X-AIO-Key' => apikey])
        ->get('https://io.adafruit.com/api/v2/isradios/feeds/'.$casa->nombre.'.agua/data/last');

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Información de la casa encontrada de manera exitosa",
                "errors" => [],
                "valor" => $response->object()->value
            ],200);
        }       

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener la información de la casa",
            "errors" => [$response->json()],
            "data" => $casa
        ],400);

    }

    public function pesoLectura(Request $request){        

        $validacion = Validator::make(
            $request->all(),
            [
                "id" => "required|integer"
            ],
            [
                "id.required" => "El campo :attribute es obligatorio",
                "id.integer" => "El campo :attribute debe ser un número entero"
            ]);
        
            if($validacion->fails()){
                return response()->json([
                    "status" => 400,
                    "message" => "Ocurrió un error en las validaciones",
                    "errors" => $validacion->errors(),
                    "data" => []
                ]);
            }

        $casa = Casa::find($request->id);

        $response = Http::withHeaders([
            'X-AIO-Key' => apikey])
        ->get('https://io.adafruit.com/api/v2/isradios/feeds/'.$casa->nombre.'.peso/data/last');

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Información de la casa encontrada de manera exitosa",
                "errors" => [],
                "valor" => $response->object()->value
            ],200);
        }       

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener la información de la casa",
            "errors" => [$response->json()],
            "data" => $casa
        ],400);

    }

    public function comidaLectura(Request $request){

        $validacion = Validator::make(
            $request->all(),
            [
                "id" => "required|integer"
            ],
            [
                "id.required" => "El campo :attribute es obligatorio",
                "id.integer" => "El campo :attribute debe ser un número entero"
            ]);
        
            if($validacion->fails()){
                return response()->json([
                    "status" => 400,
                    "message" => "Ocurrió un error en las validaciones",
                    "errors" => $validacion->errors(),
                    "data" => []
                ]);
            }

        $casa = Casa::find($request->id);

        $response = Http::withHeaders([
            'X-AIO-Key' => apikey])
        ->get('https://io.adafruit.com/api/v2/isradios/feeds/'.$casa->nombre.'.comida/data/last');

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Información de la casa encontrada de manera exitosa",
                "errors" => [],
                "valor" => $response->object()->value
            ],200);
        }       

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener la información de la casa",
            "errors" => [$response->json()],
            "data" => $casa
        ],400);

    }

    public function lluviaLectura(Request $request){

        $validacion = Validator::make(
            $request->all(),
            [
                "id" => "required|integer"
            ],
            [
                "id.required" => "El campo :attribute es obligatorio",
                "id.integer" => "El campo :attribute debe ser un número entero"
            ]);
        
            if($validacion->fails()){
                return response()->json([
                    "status" => 400,
                    "message" => "Ocurrió un error en las validaciones",
                    "errors" => $validacion->errors(),
                    "data" => []
                ]);
            }

        $casa = Casa::find($request->id);

        $response = Http::withHeaders([
            'X-AIO-Key' => apikey])
        ->get('https://io.adafruit.com/api/v2/isradios/feeds/'.$casa->nombre.'.lluvia/data/last');

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Información de la casa encontrada de manera exitosa",
                "errors" => [],
                 "valor" => $response->object()->value
            ],200);
        }       

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener la información de la casa",
            "errors" => [$response->json()],
            "data" => $casa
        ],400);

    }

    public function iluminacionLectura(Request $request){

        $validacion = Validator::make(
            $request->all(),
            [
                "id" => "required|integer"
            ],
            [
                "id.required" => "El campo :attribute es obligatorio",
                "id.integer" => "El campo :attribute debe ser un número entero"
            ]);
        
            if($validacion->fails()){
                return response()->json([
                    "status" => 400,
                    "message" => "Ocurrió un error en las validaciones",
                    "errors" => $validacion->errors(),
                    "data" => []
                ]);
            }
     
        $casa = Casa::find($request->id);

        $response = Http::withHeaders([
            'X-AIO-Key' => apikey])
        ->get('https://io.adafruit.com/api/v2/isradios/feeds/'.$casa->nombre.'.luminosidad/data/last');

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Información de la casa encontrada de manera exitosa",
                "errors" => [],
                "valor" => $response->object()->value
            ],200);
        }       

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener la información de la casa",
            "errors" => [$response->json()],
            "data" => $casa
        ],400);

    }

    public function temperaturaLectura(Request $request){

        $validacion = Validator::make(
            $request->all(),
            [
                "id" => "required|integer"
            ],
            [
                "id.required" => "El campo :attribute es obligatorio",
                "id.integer" => "El campo :attribute debe ser un número entero"
            ]);
        
            if($validacion->fails()){
                return response()->json([
                    "status" => 400,
                    "message" => "Ocurrió un error en las validaciones",
                    "errors" => $validacion->errors(),
                    "data" => []
                ]);
            }

        $casa = Casa::find($request->id);

        $response = Http::withHeaders([
            'X-AIO-Key' => apikey])
        ->get('https://io.adafruit.com/api/v2/isradios/feeds/'.$casa->nombre.'.temperatura/data/last');

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Información de la casa encontrada de manera exitosa",
                "errors" => [],
                "valor" => $response->object()->value
            ],200);
        }       

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener la información de la casa",
            "errors" => [$response->json()],
            "data" => $casa
        ],400);

    }

    
}
