<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Routing\RouteCollectorProxy;

use Slim\Factory\AppFactory;


require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/poo/verificadora.php';
require_once __DIR__ . '/../src/poo/cd.php';

//NECESARIO PARA GENERAR EL JWT
use Firebase\JWT\JWT;

$app = AppFactory::create();

$app->post('/login[/]', \Verificadora::class . ':VerificarUsuario')->add(\Verificadora::class . ':ValidarParametrosUsuario');

$app->get('/login/test', \Verificadora::class . ':ObtenerDataJWT')->add(\Verificadora::class . ':ChequearJWT');

$app->group('/json_bd', function (RouteCollectorProxy $grupo) {

    $grupo->get('/', \Cd::class . ':TraerTodos');

    $grupo->get('/{id}', \Cd::class . ':TraerUno');

    $grupo->post('/', \Cd::class . ':Agregar')->add(\Verificadora::class . ':ValidarParametrosCDAgregar');

    $grupo->put('/', \Cd::class . ':Modificar')->add(\Verificadora::class . ':ValidarParametrosCDModificar');

    $grupo->delete('/', \Cd::class . ':Eliminar')->add(\Verificadora::class . ':ValidarParametrosCDModificar');

})->add(\Verificadora::class . ':ChequearJWT');

//CORRE LA APLICACIÓN.
$app->run();
