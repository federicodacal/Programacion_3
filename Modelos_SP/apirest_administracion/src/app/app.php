<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Routing\RouteCollectorProxy;

use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../poo/usuario.php';
require_once __DIR__ . '/../poo/perfil.php';
require_once __DIR__ . '/../poo/mw.php';

use Firebase\JWT\JWT;

$app = AppFactory::create();

$app->post('/usuario', \Usuario::class . ':agregar');

$app->get('/', \Usuario::class . ':traerTodos');

$app->post('/', \Perfil::class . ':agregar');

$app->get('/perfil', \Perfil::class . ':traerTodos');

$app->post('/login', \Usuario::class . ':logear');

$app->get('/login', \Usuario::class . ':chequearJWT');

$app->group('/perfiles', function (RouteCollectorProxy $grupo) {
    $grupo->delete('/{id_perfil}', \Perfil::class . ':borrar');
    $grupo->put('/{perfil}/{id_perfil}', \Perfil::class . ':modificar');
})->add(\MW::class . ':chequearJWT');

$app->group('/usuarios', function (RouteCollectorProxy $grupo) {
    $grupo->delete('/{id_usuario}', \Usuario::class . ':borrar');
    $grupo->post('/{usuario}', \Usuario::class . ':modificar');
})->add(\MW::class . ':chequearJWT');

//CORRE LA APLICACIÓN.
$app->run();

?>