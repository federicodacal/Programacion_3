<?php

use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once  "autentificadora.php";
require_once "usuario.php";
require_once "auto.php";

class MW
{
    // MW 1
    public function verificarCorreoYClave(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";
        $params = $request->getParsedBody();

        $obj_response = new stdclass();
        $obj_response->mensaje = "";
        $obj_response->status = 403;

        $user_json = null;

        if (isset($params["user"])) 
        {
            $user_json = json_decode(($params["user"]));
        }
        else if(isset($params["usuario"]))
        {
            $user_json = json_decode(($params["usuario"]));
        }

        if(isset($user_json))
        {

            if(isset($user_json->correo) && isset($user_json->clave))
            {
                $response = $handler->handle($request);

                $contenidoAPI = (string) $response->getBody();

                $api_response = json_decode($contenidoAPI);
                $obj_response->status = $api_response->status;
            }
            else 
            {
                $obj_response->mensaje = "Falta completar: ";
                if(!isset($user_json->correo))
                {
                    $obj_response->mensaje .= "'correo' ";
                }

                if(!isset($user_json->clave))
                {
                    $obj_response->mensaje .= "'clave'";
                }

                $contenidoAPI = json_encode($obj_response);
            }
        }
        else 
        {
            $obj_response->mensaje = "No se envio parametro 'user'.";
            $contenidoAPI = json_encode($obj_response);
        }

        $newResponse = new ResponseMW();
        $newResponse = $newResponse->withStatus($obj_response->status);

        $newResponse->getBody()->write($contenidoAPI);

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    // MW 2
    public static function verificarCampos(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";
        $params = $request->getParsedBody();

        $obj_response = new stdclass();
        $obj_response->mensaje = "";
        $obj_response->status = 409;

        $user_json = null;

        if (isset($params["user"])) 
        {
            $user_json = json_decode(($params["user"]));
        }
        else if(isset($params["usuario"]))
        {
            $user_json = json_decode(($params["usuario"]));
        }

        if(isset($user_json))
        {
            if($user_json->correo !== "" && $user_json->clave !== "")
            {
                $response = $handler->handle($request);

                $contenidoAPI = (string) $response->getBody();

                $api_response = json_decode($contenidoAPI);
                $obj_response->status = $api_response->status;
            }
            else 
            {
                $obj_response->mensaje = "Campos vacios: ";
                if($user_json->correo === "")
                {
                    $obj_response->mensaje .= "'correo' ";
                }

                if($user_json->clave === "")
                {
                    $obj_response->mensaje .= "'clave'";
                }

                $contenidoAPI = json_encode($obj_response);
            }
        }

        $newResponse = new ResponseMW();
        $newResponse = $newResponse->withStatus($obj_response->status);

        $newResponse->getBody()->write($contenidoAPI);

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    // MW 3
    public function verificarUsuario(Request $request, RequestHandler $handler): ResponseMW
    {
        $params = $request->getParsedBody();

        $obj_response = new stdClass();
        $obj_response->exito = false;
        $obj_response->mensaje = "No se encontro usuario";
        $obj_response->status = 403;

        $user_json = null;

        if (isset($params["user"])) 
        {
            $user_json = json_decode(($params["user"]));
        }
        else if(isset($params["usuario"]))
        {
            $user_json = json_decode(($params["usuario"]));
        }

        if(isset($user_json))
        {
            $user = new Usuario();
            $user->correo = $user_json->correo;
            $user->clave = $user_json->clave;

            $userBD = $user->loginUsuario();

            if(isset($userBD))
            {
                $response = $handler->handle($request);
                $contenidoAPI = (string) $response->getBody();

                $api_response = json_decode($contenidoAPI);
                $obj_response->status = $api_response->status;
            }
            else 
            {
                $contenidoAPI = json_encode($obj_response);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus($obj_response->status);

        $response->getBody()->write($contenidoAPI);

        return $response->withHeader('Content-Type', 'application/json');
    }

    // MW 4
    public static function verificarCorreo(Request $request, RequestHandler $handler): ResponseMW
    {
        $params = $request->getParsedBody();
        $obj_response = new stdClass();
        $obj_response->mensaje = "Ya esta registrado el correo";
        $obj_response->status = 403;
        $user_json = null;

        if (isset($params["user"])) 
        {
            $user_json = json_decode(($params["user"]));
        }
        else if(isset($params["usuario"]))
        {
            $user_json = json_decode(($params["usuario"]));
        }

        if(isset($user_json))
        {
            $usuario = new Usuario;
            $usuario->correo = $user_json->correo;

            $userDB = $usuario->traerPorCorreo();

            if (!isset($userDB)) 
            {
                $response = $handler->handle($request);

                $contenidoAPI = (string) $response->getBody();

                $api_response = json_decode($contenidoAPI);
                $obj_response->status = $api_response->status;
            } 
            else 
            {
                $contenidoAPI = json_encode($obj_response);
            }
        }            
         
        $response = new ResponseMW();
        $response = $response->withStatus($obj_response->status);

        $response->getBody()->write($contenidoAPI);

        return $response->withHeader('Content-Type', 'application/json');
    }
    
    // MW 5
    public function verificarPrecio(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";
        $arrayDeParametros = $request->getParsedBody();

        $obj_response = new stdClass();
        $obj_response->status = 409;

        if (isset($arrayDeParametros['auto'])) 
        {
            $auto_json = json_decode($arrayDeParametros['auto']);

            if($auto_json->color != "azul" && $auto_json->precio >= 50000 && $auto_json->precio <= 600000) 
            {
                $response = $handler->handle($request);

                $contenidoAPI = (string) $response->getBody();

                $api_response = json_decode($contenidoAPI);
                $obj_response->status = $api_response->status;
            }
            else 
            {
                $mensaje_error = "Parametros no permitidos: ";

                if($auto_json->color == "azul") 
                {
                    $mensaje_error .= "- Color azul ";
                }
                if($auto_json->precio < 50000 || $auto_json->precio > 600000) 
                {
                    $mensaje_error .= "- Precio fuera de rango ";
                }

                $obj_response->mensaje = $mensaje_error;
                $contenidoAPI = json_encode($obj_response);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus($obj_response->status);

        $response->getBody()->write($contenidoAPI);

        return $response->withHeader('Content-Type', 'application/json');
    }

    /*******************************************************************************************/

    public function chequearJWT(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";

        $obj_response = new stdClass();
        $obj_response->mensaje = "Token Invalido";
        $obj_response->status = 403;

        if(isset($request->getHeader("token")[0])) 
        {
            $token = $request->getHeader("token")[0];

            if ($obj = Autentificadora::verificarJWT($token)) 
            {
                if($obj->verificado) 
                {
                    $response = $handler->handle($request);

                    $contenidoAPI = (string) $response->getBody();

                    $api_response = json_decode($contenidoAPI);
                    $obj_response->status = $api_response->status;
                } 
                else 
                {
                    $obj_response->mensaje = $obj->mensaje;
                    $contenidoAPI = json_encode($obj_response);
                }
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus($obj_response->status);

        $response->getBody()->write($contenidoAPI);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function verificarPropietario(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";

        $obj_response = new stdclass();

        $obj_response->propietario = false;
        $obj_response->mensaje = "Usuario no autorizado.";
        $obj_response->status = 409;

        if (isset($request->getHeader("token")[0])) 
        {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::obtenerPayLoad($token);

            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            if ($perfil_usuario == "propietario") 
            {
                $response = $handler->handle($request);
                $contenidoAPI = (string) $response->getBody();

                $api_response = json_decode($contenidoAPI);

                $obj_response->status = $api_response->status;
                $obj_response->propietario = true;

                $obj_response->mensaje = "Usuario Autorizado. Es Propietario";
            } 
            else 
            {
                $obj_response->mensaje = "Usuario no autorizado. {$usuario_token->nombre} - {$usuario_token->apellido} - {$usuario_token->perfil}";
                $contenidoAPI = json_encode($obj_response);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus($obj_response->status);

        $response->getBody()->write($contenidoAPI);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function verificarEncargado(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";

        $obj_response = new stdclass();
        $obj_response->encargado = false;
        $obj_response->mensaje = "Usuario no autorizado.";
        $obj_response->status = 409;

        if (isset($request->getHeader("token")[0])) 
        {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::obtenerPayLoad($token);
            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            if ($perfil_usuario == "encargado") 
            {
                $response = $handler->handle($request);
                $contenidoAPI = (string) $response->getBody();

                $api_response = json_decode($contenidoAPI);
                $obj_response->status = $api_response->status;

                $obj_response->encargado = true;
                $obj_response->mensaje = "Usuario Autorizado. Es encargado";
            }
            else 
            {
                $obj_response->mensaje = "Usuario no autorizado. {$usuario_token->nombre} - {$usuario_token->apellido} - {$usuario_token->perfil}";
                $contenidoAPI = json_encode($obj_response);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus($obj_response->status);

        $response->getBody()->write($contenidoAPI);

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Filtrado de datos al listado de autos para propietario, encargado o empleado
    public function MostrarDatosDeAutosAEncargado(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";

        if (isset($request->getHeader("token")[0])) {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::obtenerPayLoad($token);
            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();

            if ($perfil_usuario == "encargado") {
                $api_respuesta = json_decode($contenidoAPI);
                $array_autos = json_decode($api_respuesta->dato);

                foreach ($array_autos as $auto) {
                    unset($auto->id);
                }

                $contenidoAPI = json_encode($array_autos);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus(200);
        $response->getBody()->write($contenidoAPI);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function MostrarDatosDeAutosAEmpleado(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";

        if (isset($request->getHeader("token")[0])) {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::obtenerPayLoad($token);
            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();

            if ($perfil_usuario == "empleado") {
                $api_respuesta = json_decode($contenidoAPI);
                $array_autos = json_decode($api_respuesta->dato);

                $colores = [];

                foreach ($array_autos as $item) {
                    array_push($colores, $item->color);
                }

                $cantColores = array_count_values($colores);

                $obj_respuesta = new stdClass();
                $obj_respuesta->mensaje = "Hay " . count($cantColores) . " colores distintos en el listado de autos.";
                $obj_respuesta->colores = $cantColores;

                $contenidoAPI = json_encode($obj_respuesta);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus(200);
        $response->getBody()->write($contenidoAPI);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function MostrarDatosDeAutosAPropietario(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";
        $id = isset($request->getHeader("id_auto")[0]) ? $request->getHeader("id_auto")[0] : null;

        if (isset($request->getHeader("token")[0])) {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::obtenerPayLoad($token);
            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();

            if ($perfil_usuario == "propietario") {
                $api_respuesta = json_decode($contenidoAPI);
                $array_autos = json_decode($api_respuesta->dato);

                if ($id != null) {
                    foreach ($array_autos as $auto) {
                        if ($auto->id == $id) {
                            $array_autos = $auto; // el array pasa a ser un solo obj json
                            break;
                        }
                    }
                }

                $contenidoAPI = json_encode($array_autos);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus(200);
        $response->getBody()->write($contenidoAPI);
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Filtrado de datos al listado de usuarios para propietario, encargado o empleado
    public function MostrarDatosDeUsuariosAEncargado(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";

        if (isset($request->getHeader("token")[0])) {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::obtenerPayLoad($token);
            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();

            if ($perfil_usuario == "encargado") {
                $api_respuesta = json_decode($contenidoAPI);
                $array_usuarios = json_decode($api_respuesta->dato);

                foreach ($array_usuarios as $usuario) {
                    unset($usuario->id);
                    unset($usuario->clave);
                }

                $contenidoAPI = json_encode($array_usuarios);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus(200);
        $response->getBody()->write($contenidoAPI);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function MostrarDatosDeUsuariosAEmpleado(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";

        if (isset($request->getHeader("token")[0])) {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::obtenerPayLoad($token);
            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();

            if ($perfil_usuario == "empleado") {
                $api_respuesta = json_decode($contenidoAPI);
                $array_usuarios = json_decode($api_respuesta->dato);

                foreach ($array_usuarios as $usuario) {
                    unset($usuario->id);
                    unset($usuario->clave);
                    unset($usuario->correo);
                    unset($usuario->perfil);
                }

                $contenidoAPI = json_encode($array_usuarios);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus(200);
        $response->getBody()->write($contenidoAPI);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function MostrarDatosDeUsuariosAPropietario(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";
        $apellido = isset($request->getHeader("apellido")[0]) ? $request->getHeader("apellido")[0] : null;

        if (isset($request->getHeader("token")[0])) {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::obtenerPayLoad($token);
            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();

            if ($perfil_usuario == "propietario") {
                $api_respuesta = json_decode($contenidoAPI);
                $array_usuarios = json_decode($api_respuesta->dato);

                $apellidosIguales = [];
                $todosLosApellidos = [];

                if($apellido != NULL){

                    foreach($array_usuarios as $item){
                        if($item->apellido == $apellido){
                            array_push($apellidosIguales,$item);
                        }
                    }

                    if(count($apellidosIguales) == 0){
                        $cantidad = 0;
                    }else{
                        $cantidad = count($apellidosIguales);
                    }
                    
                    $contenidoAPI = "La cantidad de apellidos iguales es : {$cantidad} - {$apellido}";
                } else {
                    
                    foreach($array_usuarios as $item){
                        array_push($todosLosApellidos,$item->apellido);
                    }

                    $todosLosApellidos = array_count_values($todosLosApellidos);
                    $contenidoAPI = json_encode($todosLosApellidos);
                }         
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus(200);
        $response->getBody()->write($contenidoAPI);
        return $response->withHeader('Content-Type', 'application/json');
    }

}

?>