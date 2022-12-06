<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AdafruitController extends Controller
{
    public function infrarrojoLectura(Request $request){

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.infrarojo/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function lluviaLectura(Request $request){
        
        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.agualluvia/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function pesoLectura(Request $request){
        
        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.peso/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);

    }

    public function iluminacionLectura(Request $request){

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.luminosidad/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function aguaLectura(Request $request){
        
        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.agua/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function temperaturaLectura(Request $request){

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.temperatura/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function datosCasa(Request $request){
        $validacion = Validator::make($request->all(),[
            "nombre" => "required|string"
        ],
        [
            "nombre.required" => "El nombre del grupo es requerido",
            "nombre.string" => "El nombre del grupo debe ser una cadena de texto"
        ]);

        if($validacion->fails()){
            return response()->json([
                "status" => 400,
                "message" => "Ocurrió un error en las validaciones",
                "errors" => $validacion->errors(),
                "data" => null
            ],400);
        }

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->post("https://io.adafruit.com/api/v2/isradios/groups",[
            "name" => $request->nombre
        ]);

        if($response->successful()){

            $feeds = array("agua","luminosidad","peso","temperatura","lluvia","comida");
            for ($i=0; $i <= 5; $i++) { 
                
            $response2 = Http::withHeaders([
                "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
            ])
            ->post("https://io.adafruit.com/api/v2/isradios/groups/$request->nombre/feeds",[
                "name" => $feeds[$i]
            ]);
            }

            if($response2->successful()){

                $response3 = Http::withHeaders([
                    "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
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

    }

    public function crearFeeds(Request $request){
        $validacion = Validator::make(
            $request->all(),
        [
            "nombre" => "required|string"
        ],
        [
            "nombre.required" => "El campo :attribute es obligatorio",
            "nombre.string" => "El campo :attribute debe ser una cadena de texto"
        ]);
        
        if($validacion->fails()){
            return response()->json([
                "status" => 400,
                "message" => "Ocurrió un error en las validaciones",
                "errors" => $validacion->errors(),
                "data" => null
            ],400);
        }

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->post("https://io.adafruit.com/api/v2/isradios/feeds",[
            "name" => $request->nombre
        ]);


        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Feed creado de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al crear el feed",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function datosGrupo(){

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get('https://io.adafruit.com/api/v2/isradios/groups/casadeevy');

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->object(),
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function infrarrojoUsuario(Request $request){

        $user = User::select("users.id","users.nombre","casas.nombre")
        ->join("casas","casas.id","=","users.casa_id")
        ->where("user.id",$request->user()->id)->get();

        $user->casa->nombre;

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.infrarojo/data/last");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function lluviaUsuario(Request $request){
        
        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.agualluvia/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function pesoUsuario(Request $request){
        
        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.peso/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);

    }

    public function iluminacionUsuario(Request $request){

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.luminosidad/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function aguaUsuario(Request $request){
        
        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.agua/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

    public function temperaturaUsuario(Request $request){

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.temperatura/data");

        if($response->successful()){
            return response()->json([
                "status" => 200,
                "message" => "Datos obtenidos de manera exitosa",
                "errors" => null,
                "data" => $response->json()
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Ocurrió un error al obtener los datos",
            "errors" => $response->json(),
            "data" => null
        ],400);
    }

}
