<?php

namespace App\Http\Controllers;

use App\Jobs\CorreoVerificacion;
use App\Jobs\EnviarCorreoActivacionQueue;
use App\Jobs\SMSVerificacion;
use App\Mail\VerificacionMailer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function registrarUsuario(Request $request){

        $validacion = Validator::make(
            $request->all()
        ,
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
                "errors" => $validacion->errors(),
                "data" => null
            ],400);
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

            $url = URL::temporarySignedRoute("verifymail", now()->addMinutes(30),["id"=>$user->id]);
            CorreoVerificacion::dispatch($user,$url)->delay(now()->addSeconds(15))->onQueue('email')->onConnection('database');

            return response()->json([
                "status" => 200,
                "message" => "Usuario creado correctamente",
                "errors" => null,
                "data" => $user->id
            ],200);
        }

        return response()->json([
            "status" => 400,
            "message" => "Error al crear el usuario",
            "errors" => [],
            "data" => null
        ],400);

    }

    public function verifymail(Request $request){
        
        if(!$request->hasValidSignature())
        abort(401);
        

            $user = User::find($request->id);
            $user->mail_status = '1';
            $user->save();

        try
        {
            
            //return view("mails.confirmacion");

            return response()->json([
                "status"=>200,
                "message"=>"Error al verificar correo.",
                "data" => $user
            ],400);
        }

        catch(Exception $e)
        {
            return response()->json([
                "status"=>400,
                "message"=>"Error al verificar correo."
            ],400);
        }

    }

    public function verificarCorreoActivo(Request $request){

        $user = User::find($request->id);

        if($user->mail_status == 1){
            return response()->json([
                "status"=>200,
                "message"=>"Correo verificado.",
                "errors"=>null,
                "data"=>[$user]
            ],200);
        }


        return response()->json([
            "status"=>400,
            "message"=>"Correo no verificado.",
            "errors"=>null,
            "data"=>null
        ],400);
    }

    public function enviarSMS(Request $request){
        $code = rand(1000, 9999);

        $user = User::find($request->id);
        $user->codigoSMS = $code;
        $user->save();

        $url = URL::temporarySignedRoute("verifyphone", now()->addMinutes(5), ["id" => $user->id]);
        SMSVerificacion::dispatch($user,$url,$code)->delay(now()->addSeconds(15))->onQueue("sms")->onConnection("database");

        return response()->json([
            "status"=>200,
            "message"=>"SMS enviado.",
            "errors"=>null,
            "data"=> $url
        ],200);
    }

    public function verificarSMS(Request $request){
       
        if(!$request->hasValidSignature())
        abort(401);

        $validacion = Validator::make(
            $request->all(),
            [
                "codigo"=>"required|integer",
            ],
            [
                "codigo.required"=>"El campo :attribute es obligatorio.",
                "codigo.integer"=>"El campo :attribute tiene que ser un entero.",
            ]
        );

        if($validacion->fails())
        {
            return response()->json(
                [
                    "status"=>400,
                    "message"=>"Error en la validacion.",
                    "errors"=>$validacion->errors(),
                    "data"=>[]
                ],400
            );
        }
            $user = User::find($request->id);

            if($user->codigoSMS == $request->codigo)
            {
                $user->active = '1';
                $user->save();

                return response()->json(
                    [
                        "status"=>200,
                        "message"=>"Cuenta activada con exito!!!",
                        "errors" => null,
                        "data" =>[$user]
                    ],200
                );
            }
            
                return response()->json(
                    [
                        "status"=>400,
                        "message"=>"Error en la activacion de cuenta...",
                        "erorrs" => "El codigo no coincide",
                        "data" => []
                    ],400
                );
            
    }

    public function enviarCodigoTwilio(Request $request){
        $code = rand(1000, 9999);

        $user = User::find($request->id);
        $user->codigoSMS = $code;
        $user->save();

        $url = URL::temporarySignedRoute("verifyphone", now()->addMinutes(5), ["id" => $user->id]);

        $response = Http::withBasicAuth('AC11f5b89a20d3ac2806893a2c85e1a71f', '111244c4c97e91a09935fa1687427da2')
        ->asForm()->post('https://api.twilio.com/2010-04-01/Accounts/AC11f5b89a20d3ac2806893a2c85e1a71f/Messages.json',[
            "To" => "whatsapp:+521".$user->telefono,
            "From" => "whatsapp:+14155238886",
            "Body" => "Tu código de verificación es: ".$code
        ]);

        if($response->successful())
        {
            return response()->json([
                "status"=>200,
                "message"=>"Codigo enviado",
                "errors"=>null,
                "data" => [$user],
                "url"=> $url
            ],200);
        }

        else
        {
            return response()->json([
                "status"=>400,
                "message"=>"Error enviar el codigo."
            ],400);
        }
    }

    public function login(Request $request){
        
        $validacion = Validator::make(
            $request->all(),
            [
                "correo"=>"required|email|max:60",
                "password"=>"required|string|min:8"
            ],
            [
                "correo.required"=>"El campo :attribute es obligatorio.",
                "correo.email"=>"El campo :attribute debe ser un correo valido.",
                "password.required"=>"El campo :attribute es obligatorio.",
                "password.string"=>"El campo :attribute debe ser de tipo string.",
                "password.min"=>"El campo :attribute debe tener minimo :min caracteres."
            ]);

        if($validacion->fails()){
            return response()->json(
                [
                    "status"=>400,
                    "message"=>"Error en la validacion...",
                    "errors"=>$validacion->errors(),
                    "data"=>[]
                ],400
            );
        }

        $user = User::where("correo",$request->correo)->where("active","1")->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                    "status"=>400,
                    "message"=>"No se pudo iniciar sesión",
                    "errors"=>"El correo o la contraseña son incorrectos",
                    "data"=>null
                ],400
            );
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "status"=>200,
            "message"=>"Se ha iniciado sesion correctamente",
            "errors"=>[],
            "data"=> $token,
            "role" =>  $user->role_id
        ],200);
    }

    public function logout(Request $request){

        $request->user()->Tokens()->delete();

        return response()->json(
            [
                "status"=>200,
                "message"=>"Se ha cerrado la sesion.",
            ],200
        );
    }

    public function verificarCuenta(Request $request){

        $user = User::find($request->id);
       
        if($user->active = 1){
            return response()->json(
                [
                    "status"=>200,
                    "message"=>"Correo verificado",
                    "errors" => null,
                    "data" => []
                ],200
            );
        }

        return response()->json(
            [
                "status"=>400,
                "message"=>"Error correo verificado",
                "errors" => null,
                "data" => []
            ],400
        );

    }
    
    public function login2(Request $request){

        $validacion = Validator::make(
            $request->all(),
            [
                "correo"=>"required|email|max:60",
                "password"=>"required|string|min:8"
            ],
            [
                "correo.required"=>"El campo :attribute es obligatorio.",
                "correo.email"=>"El campo :attribute debe ser un correo valido.",
                "password.required"=>"El campo :attribute es obligatorio.",
                "password.string"=>"El campo :attribute debe ser de tipo string.",
                "password.min"=>"El campo :attribute debe tener minimo :min caracteres."
            ]);

        if($validacion->fails()){
            return response()->json(
                [
                    "status"=>400,
                    "message"=>"Error en la validacion...",
                    "errors"=>$validacion->errors(),
                    "data"=>[]
                ],400
            );
        }

        $user = User::where("correo",$request->correo)->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                    "status"=>400,
                    "message"=>"No se pudo iniciar sesión",
                    "errors"=>"El correo o la contraseña son incorrectos",
                    "data"=>null
                ],400
            );
        }

        if($user->active == 0){

            $url = URL::temporarySignedRoute('verifymail',now()->addMinutes(20),['id'=>$user->id]);
            //CorreoVerificacion::dispatch($user,$url)->delay(now()->addSeconds(20))->onQueue('email')->onConnection('database');
            Mail::to($user->correo)->send(new VerificacionMailer($user, $url));

            return response()->json([
                "status"=>401,
                "message"=>"No se pudo iniciar sesión",
                "errors"=>"El correo no ha sido verificado",
                "data"=>[$user]
            ],400);
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "status"=>200,
            "message"=>"Se ha iniciado sesion correctamente",
            "errors"=>[],
            "data"=> $token
        ],200);
    }    


}
