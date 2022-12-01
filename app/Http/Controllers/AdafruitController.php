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
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.infrarojo");
    }

    public function lluviaLectura(Request $request){
        
        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.agualluvia");
    }

    public function pesoLectura(Request $request){
        
        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.peso");

    }

    public function iluminacionLectura(Request $request){

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.luminosidad");
    }

    public function aguaLectura(Request $request){
        
        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.agua");
    }

    public function temperaturaLectura(Request $request){

        $response = Http::withHeaders([
            "X-AIO-Key" => "aio_wxOi45wuZyR3eETnx1l7y3hRihw8"
        ])
        ->get("https://io.adafruit.com/api/v2/isradios/feeds/casa.temperatura");
    }
}
