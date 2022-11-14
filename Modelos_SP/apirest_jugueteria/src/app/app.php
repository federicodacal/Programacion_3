<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Routing\RouteCollectorProxy;

use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../poo/usuario.php';
require_once __DIR__ . '/../poo/juguete.php';
require_once __DIR__ . '/../poo/mw.php';

use Firebase\JWT\JWT;

$app = AppFactory::create();

$app->get('/', \Usuario::class . ':traerTodos');

$app->post('/', \Juguete::class . ':agregar');

$app->get('/juguetes', \Juguete::class . ':traerTodos');

$app->post('/login', \Usuario::class . ':logear');

$app->get('/login', \Usuario::class . ':chequearJWT');

//CORRE LA APLICACIÓN.
$app->run();

?>