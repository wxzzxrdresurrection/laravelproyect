<?php

namespace App\Http\Controllers;

use App\Mail\ContraseñaMailer;
use App\Models\Casa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    public function createCasa(Request $request){

        $validacion = Validator::make(
            $request->all(),
            [
                "nombre" => "required|string|max:30",
                "descripcion" => "string|max:100",
            ],
        [
            "nombre.required" => "El campo :attribute es requerido",
            "nombre.string" => "El campo :attribute debe ser un texto",
            "nombre.max" => "El campo :attribute debe tener un maximo de :max caracteres",
            "descripcion.max" => "El campo :attribute debe tener un maximo de :max caracteres",
            "descripcion.string" => "El campo :attribute debe ser un texto",
        ]);

        if($validacion->fails()){

            return response()->json([
                "status" => 400,
                "message" => "Error en las validaciones",
                "errors" => $validacion->errors(),
                "data" => null
            ],400);
        }

        $casa = Casa::create([
            "nombre" => $request->nombre,
            "descripcion" => $request->descripcion,
        ]);

        $response = Http::withHeaders([
            "X-AIO-Key" => apikey
        ])
        ->post("https://io.adafruit.com/api/v2/isradios/groups",[
            "name" => $request->nombre
        ]);

        if($response->successful()){

            $feeds = array("agua","luminosidad","peso","temperatura","lluvia","comida");
            for ($i=0; $i <= 5; $i++) { 
                
            $response2 = Http::withHeaders([
                "X-AIO-Key" => apikey
            ])
            ->post("https://io.adafruit.com/api/v2/isradios/groups/$casa->nombre/feeds",[
                "name" => $feeds[$i]
            ]);
            }

            if($response2->successful()){

                $response3 = Http::withHeaders([
                    "X-AIO-Key" => apikey
                ])
                ->get("https://io.adafruit.com/api/v2/isradios/groups/$request->nombre");

                return response()->json([
                    "status" => 200,
                    "message" => "Feed creado de manera exitosa",
                    "errors" => null,
                    "data" => $response3->json()
                ],200);
            }
        }

        return response()->json([
            "status" => 400,
            "message" => "No se pudo crear la casa",
            "errors" => null,
            "data" => [$response2->json()]
        ],400);
    }

    public function crearFeeds(Request $request){

        $feeds = array("agua","luminosidad","peso","temperatura","lluvia","comida");
        for ($i=0; $i <= 5; $i++) { 
            
        $response2 = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->post("https://io.adafruit.com/api/v2/isradios/groups/$request->nombre/feeds",[
            "name" => $feeds[$i]
        ]);
        }

        return response()->json([
            "data" => [$response2->json()]
        ]);

    }

    public function asignarCasa(Request $request){

        $validacion = Validator::make(
            $request->all(),
            [
                "user_id" => "required|integer",
                "casa_id" => "required|integer",
            ],
            [
            "user_id.required" => "El campo :attribute es requerido",
            "user_id.integer" => "El campo :attribute debe ser un numero entero",
            "casa_id.required" => "El campo :attribute es requerido",
            "casa_id.integer" => "El campo :attribute debe ser un numero entero",
            ]);

        if($validacion->fails()){

            return response()->json([
                "status" => 400,
                "message" => "Error en las validaciones",
                "errors" => $validacion->errors(),
                "data" => null
            ],400);
        }

        $user = User::find($request->user_id);

        if(!$user){
            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar al usuario",
                "errors" => "No se encontro al usuario",
                "data" => null
            ],400);
        }

        $casa = Casa::find($request->casa_id);

        if(!$casa){
            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar la casa",
                "errors" => "No se encontro la casa",
                "data" => null
            ],400);
        }

        $user->casas()->save($casa);

        return response()->json([
            "status" => 200,
            "message" => "Casa asignada de manera exitosa",
            "errors" => null,
            "data" => [$user]
        ],200);


    }

    public function verUsuarios(){
        $user = User::all()->where('id','=','2');

        if(!$user){
            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar usuarios",
                "errors" => "No se encontraron usuarios",
                "data" => null
            ],400);

            return response()->json([
                "status" => 200,
                "message" => "Usuarios encontrados con exito",
                "errors" => null,
                "data" => [$user]
            ],200);

        }
    }

    public function verCasas(){
        
        $casa = Casa::all();

        if(!$casa){
            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar las casas",
                "errors" => "No se econtraron casas registradas",
                "data" => null
            ],400);
        }

        return response()->json([
            "status" => 200,
            "message" => "Casas encontradas de manera exitosa",
            "errors" => null,
            "casas" => [$casa]
        ],200);
    }

    public function casasDeCadaUsuario(Request $request,$id){

        $user = User::find($id);

        if(!$user){
            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar al usuario",
                "errors" => "El usuario no existe",
                "data" => null
            ],400);
        }

        $casas = $user->casas()->get();

        return response()->json([
            "status" => 200,
            "message" => "Casas encontradas de manera exitosa : " .count($casas) ." registros",
            "errors" => null,
            "data" => $casas
        ],200);

    }

    public function desactivarCuenta(Request $request,$id){

        $user = User::find($id);

        if(!$user){

            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar al usuario",
                "errors" => "El usuario no existe",
                "data" => null
            ],400);
        }

        $user->active = '0';
        $user->save();

            return response()->json([
                "status" => 200,
                "message" => "Usuario encontrado de manera exitosa",
                "errors" => null,
                "data" => [$user]
            ],200);

    }

    public function activarCuenta(Request $request,$id){

        $user = User::find($id);

        if(!$user){
            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar al usuario",
                "errors" => "El usuario no existe",
                "data" => null
            ],400);
        }

        $user->active = '1';
        $user->save();

            return response()->json([
                "status" => 200,
                "message" => "Usuario encontrado de manera exitosa",
                "errors" => null,
                "data" => [$user]
            ],200);

    }

    public function cambiarInfoCuenta(Request $request, $id){

        $validacion = Validator::make(
            $request->all(),
            [
                "nombre" => "string",
                "ap_paterno" => "string",
                "ap_materno" => "string",
                "correo" => "email",
                "telefono" => "string"
            ],
            [
                "nombre.string" => "El campo :attribute debe ser un texto",
                "ap_paterno.string" => "El campo :attribute debe ser un texto",
                "ap_materno.string" => "El campo :attribute debe ser un texto",
                "correo.email" => "El campo :attribute debe ser un correo",
                "telefono.string" => "El campo :attribute debe ser un texto",
            ]);

        if($validacion->fails()){

            return response()->json([
                "status" => 400,
                "message" => "Error en las validaciones",
                "errors" => $validacion->errors(),
                "data" => null
            ],400);
         }

        $user = User::find($id);

        if(!$user){
            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar al usuario",
                "errors" => "El usuario no existe",
                "data" => null
            ],400);
        }


        $user->nombre = $request->nombre;
        $user->ap_paterno = $request->ap_paterno;
        $user->ap_materno = $request->ap_materno;
        $user->correo = $request->correo;
        $user->telefono = $request->telefono;
        $user->save();

        return response()->json([
            "status" => 200,
            "message" => "Informacion actualizada de manera exitosa",
            "errors" => null,
            "data" => [$user]
        ],200);

    }

    public function cambiarContraseña(Request $request,$id){

        $validacion = Validator::make(
            $request->all(),
            [
                "password" => "string|required",
                "password_confirmation" => "string|required|min:8"
            ],
            [
                "password.string" => "El campo :attribute debe ser un texto",
                "password.required" => "El campo :attribute es requerido",
                "password_confirmation.string" => "El campo :attribute debe ser un texto",
                "password_confirmation.required" => "El campo :attribute es requerido",
                "password_confirmation.min" => "El campo :attribute debe tener al menos :min caracteres"
            ]);

            if($validacion->fails()){
                return response()->json([
                    "status" => 400,
                    "message" => "Error en las validaciones",
                    "errors" => $validacion->errors(),
                    "data" => null
                ],400);
            }

        $user = User::find($id);

        if(!$user){
            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar al usuario",
                "errors" => "El usuario no existe",
                "data" => null
            ],400);
        }

        if(!Hash::check($request->password,$user->password)){
            return response()->json([
                "status" => 400,
                "message" => "Error al encontrar al usuario",
                "errors" => "La contraseña no coincide",
                "data" => null
            ],400);
        }

        $user->password = Hash::make($request->password_confirmation);
        $user->save();

        Mail::to($user->correo)->send(new ContraseñaMailer($request->password_confirmation));

        return response()->json([
            "status" => 200,
            "message" => "Contraseña actualizada de manera exitosa",
            "errors" => null,
            "data" => [$user]
        ],200);

        
            
    }

  
}
