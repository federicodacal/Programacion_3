<?php

use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once "autentificadora.php";
require_once "usuario.php";
require_once "perfil.php";

class MW
{
    public function chequearJWT(Request $request, RequestHandler $handler): ResponseMW
    {
        $contenidoAPI = "";

        $obj_response = new stdClass();
        $obj_response->mensaje = "Token Invalido";
        $obj_response->status = 403;

        if(isset($request->getHeader("token")[0])) 
        {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::verificarJWT($token);

            if ($datos_token->verificado)
            {
                $response = $handler->handle($request);
                $contenidoAPI = (string) $response->getBody();
                $api_response = json_decode($contenidoAPI);
                $obj_response->status = $api_response->status;
            }
            else 
            {
                $obj_response->mensaje = $datos_token->mensaje;
                $contenidoAPI = json_encode($obj_response);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus($obj_response->status);
        $response->getBody()->write($contenidoAPI);

        return $response->withHeader('Content-Type', 'application/json');
    }
}