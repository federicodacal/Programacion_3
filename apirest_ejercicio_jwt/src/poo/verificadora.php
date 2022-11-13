<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once 'accesoDatos.php';
require_once 'usuario.php';

class Verificadora 
{
    public function VerificarUsuario(Request $request, Response $response, array $args) 
    {
        $obj_response = new stdclass();
        $obj_response->mensaje = "Problema en login.";
        $obj_response->exito = false;
        $obj_response->usuario = null;
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
                $obj_response->mensaje = "Usuario logeado!";
                $obj_response->exito = true;
                $obj_response->usuario = $usuario;
                $status = 200;
            }
        }  

        $newResponse = $response->withStatus($status);
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    
}

?>