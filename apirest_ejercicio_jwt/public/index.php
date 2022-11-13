<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Routing\RouteCollectorProxy;

use Slim\Factory\AppFactory;


require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/poo/verificadora.php';

//NECESARIO PARA GENERAR EL JWT
use Firebase\JWT\JWT;

$app = AppFactory::create();

$app->post('/login[/]', \Verificadora::class . ':VerificarUsuario');


//CORRE LA APLICACIÓN.
$app->run();