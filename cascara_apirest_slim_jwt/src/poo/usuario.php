<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once 'accesoDatos.php';
require_once 'autentificadora.php';

class Usuario 
{ 
    public int $id;
    public string $nombre;
    public string $apellido;
    public string $correo;
    public string $clave;
    public string $foto;
    public string $perfil;

}

?>