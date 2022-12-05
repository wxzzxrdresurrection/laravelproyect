<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
}
