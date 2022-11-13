<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once 'accesoDatos.php';
require_once 'autentificadora.php';
require_once 'usuario.php';

class Verificadora 
{
    /*
    PARTE 1
    */
    public function VerificarUsuario(Request $request, Response $response, array $args) : Response
    {
        //$obj_response = new stdclass();
        //$obj_response->mensaje = "Problema en login.";
        //$obj_response->exito = false;
        //$obj_response->usuario = null;
        $jwt = null;
        $status = 403;

        $arrayParams = $request->getParsedBody();

        if(isset($arrayParams['obj_json']))
        {
            $obj_json = json_decode($arrayParams['obj_json'], true);
            $correo = isset($obj_json['correo']) ? $obj_json['correo'] : "";
            $clave = isset($obj_json['clave']) ? $obj_json['clave'] : "";

            $usuario = new Usuario();
            $usuario->correo = $correo;
            $usuario->clave = $clave;
            
            $usuario = $usuario->obtenerLogin();

            if(isset($usuario))
            {
                //$obj_response->mensaje = "Usuario logeado!";
                //$obj_response->exito = true;
                //$obj_response->usuario = $usuario;

                $data = new stdclass();
                $data->id = $usuario->id;
                $data->nombre = $usuario->nombre;
                $data->correo = $usuario->correo;
                $data->perfil = $usuario->perfil;
                $data->foto = $usuario->foto;

                $jwt = Autentificadora::crearJWT($data, 60*5);
                $status = 200;
            }
        }  

        $response = $response->withStatus($status);
        //$response->getBody()->write(json_encode($obj_response));

        $contenidoAPI = json_encode(array("jwt"=>$jwt));
        $response->getBody()->write($contenidoAPI);

        return $response->withHeader('Content-Type', 'application/json');
    }

    /*
    PARTE 2
    */
    public function ValidarParametrosUsuario(Request $request, RequestHandler $handler) : ResponseMW 
    {
        $contenidoAPI = "";
        $arrayParams = $request->getParsedBody();

        $obj_response = new stdclass();
        $obj_response->mensaje = "";
        $status = 403;

        if(isset($arrayParams['obj_json']))
        {
            $obj_json = json_decode($arrayParams['obj_json']);

            if(isset($obj_json->correo) && isset($obj_json->clave))
            {
                $response = $handler->handle($request);
                $contenidoAPI = (string) $response->getBody();
                $status = $response->getStatusCode();
            }
            else 
            {
                $obj_response->mensaje = "Campo/s incompleto/s: ";
                if(!isset($obj_json->correo))
                {
                    $obj_response->mensaje .= "'correo' ";
                }

                if(!isset($obj_json->clave))
                {
                    $obj_response->mensaje .= "'clave'";
                }

                $contenidoAPI = json_encode($obj_response);
            }
        }
        else 
        {
            $obj_response->mensaje = "Falta parametro 'obj_json'. ";
            $contenidoAPI = json_encode($obj_response);
        }

        $newResponse = new ResponseMW();
        $newResponse = $newResponse->withStatus($status);

        $newResponse->getBody()->write($contenidoAPI);

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    /*
    PARTE 3
    */
    public function ObtenerDataJWT(Request $request, Response $response, array $args) : Response
    {
        $token = $request->getHeader('token')[0];

        $obj_response = Autentificadora::obtenerPayLoad($token);

        $status = $obj_response->exito ? 200 : 500;

        $newResponse = $response->withStatus($status);

        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    /*
    PARTE 4
    */
    public function ChequearJWT(Request $request, RequestHandler $handler) : ResponseMW
    {
        $contenidoAPI = "";

        $obj_response = new stdClass();
        $obj_response->exito = false;
        $obj_response->mensaje = "Token no recibido";
        $obj_response->datos = null;

        $status = 500;

        if(isset($request->getHeader('token')[0]))
        {
            $token = $request->getHeader('token')[0];
            $payload = Autentificadora::obtenerPayLoad($token);

            if($payload->exito)
            {
                $response = $handler->handle($request);
                $obj_response->datos = (string) $response->getBody();
                $status = $response->getStatusCode();
            }

            $obj_response->exito = $payload->exito;
            $obj_response->mensaje = $payload->mensaje;
        }

        $contenidoAPI = json_encode($obj_response);

        $newResponse = new ResponseMW();
        $newResponse = $newResponse->withStatus($status);
        $newResponse->getBody()->write($contenidoAPI);

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    /*
    PARTE 6
    */
    public function ValidarParametrosCDAgregar(Request $request, RequestHandler $handler) : ResponseMW
    {
        $contenidoAPI = "";
        $arrayParams = $request->getParsedBody();
        
        $obj_response = new stdclass();
        $status = 403;

        if(isset($arrayParams['titulo']) && isset($arrayParams['cantante']) && isset($arrayParams['anio']))
        {
            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();
            $status = $response->getStatusCode();
        }
        else 
        {
            $obj_response->mensaje = "Campo/s incompleto/s: ";

            if(!isset($arrayParams['titulo']))
            {
                $obj_response->mensaje .= "'titulo' ";
            }

            if(!isset($arrayParams['cantante']))
            {
                $obj_response->mensaje .= "'cantante' ";
            }

            if(!isset($arrayParams['anio']))
            {
                $obj_response->mensaje .= "'anio' ";
            }

            $contenidoAPI = json_encode($obj_response);
        }

        $response = new ResponseMW();
        $response = $response->withStatus($status);
        $response->getBody()->write($contenidoAPI);

        return $response->withHeader('Content-Type', 'application/json');
    }    

    public function ValidarParametrosCDModificar(Request $request, RequestHandler $handler) : ResponseMW
    {
        $json = $request->getBody();

        $contenidoAPI = "";
        $obj_rta = new stdClass();
        $status = 500;

        if (isset($json)) {

            $obj = json_decode($json);

            if (
                isset($obj->id) &&
                isset($obj->titulo)  &&
                isset($obj->cantante)  &&
                isset($obj->anio)
            ) {
                $response = $handler->handle($request);
                $contenidoAPI = (string) $response->getBody();
                $status = 200;
            } else {
                $mensaje_error = "Parametros faltantes: \n";
                if (!isset($obj->id)) {
                    $mensaje_error .= "- id \n";
                }
                if (!isset($obj->titulo)) {
                    $mensaje_error .= "- titulo \n";
                }
                if (!isset($obj->cantante)) {
                    $mensaje_error .= "- cantante \n";
                }
                if (!isset($obj->anio)) {
                    $mensaje_error .= "- anio \n";
                }
                $obj_rta->mensaje = $mensaje_error;
                $contenidoAPI = json_encode($obj_rta);
            }
        } else {
            $obj_rta->mensaje = "Falta parametro 'obj'";
            $contenidoAPI = json_encode($obj_rta);
        }

        $response = new ResponseMW();
        $response = $response->withStatus($status);
        $response->getBody()->write($contenidoAPI);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValidarParametrosCDBorrar(Request $request, RequestHandler $handler): ResponseMW
    {
        $json = $request->getBody();

        $contenidoAPI = "";
        $obj_rta = new stdClass();
        $status = 500;

        if (isset($put_vars['obj'])) {

            $obj = json_decode($json);

            if (isset($obj->id)) {
                $response = $handler->handle($request);
                $contenidoAPI = (string) $response->getBody();
                $status = 200;
            } else {
                $mensaje_error = "Parametros faltantes: \n";
                if (!isset($obj->id)) {
                    $mensaje_error .= "- id \n";
                }
                $obj_rta->mensaje = $mensaje_error;
                $contenidoAPI = json_encode($obj_rta);
            }
        } else {
            $obj_rta->mensaje = "Falta parametro 'obj'";
            $contenidoAPI = json_encode($obj_rta);
        }

        $response = new ResponseMW();
        $response = $response->withStatus($status);
        $response->getBody()->write($contenidoAPI);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>