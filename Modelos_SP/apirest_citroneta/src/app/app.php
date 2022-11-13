<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Routing\RouteCollectorProxy;

use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../poo/usuario.php';
require_once __DIR__ . '/../poo/auto.php';
require_once __DIR__ . '/../poo/mw.php';

use Firebase\JWT\JWT;

$app = AppFactory::create();

$app->post('/usuarios', \Usuario::class . ':agregar')
->add(\MW::class . '::verificarCorreo')
->add(\MW::class . '::verificarCampos')
->add(\MW::class . ':verificarCorreoYClave');

$app->get('/', \Usuario::class . ':traerTodos')
->add(\MW::class . ':MostrarDatosDeAutosAEncargado')
->add(\MW::class . ':MostrarDatosDeAutosAEmpleado')
->add(\MW::class . ':MostrarDatosDeAutosAPropietario');

$app->post('/', \Auto::class . ':agregar')
->add(\MW::class . ':verificarPrecio');

$app->get('/autos', \Auto::class . ':traerTodos');

$app->post('/login', \Usuario::class . ':logear')
->add(\MW::class . ':verificarUsuario')
->add(\MW::class . '::verificarCampos')
->add(\MW::class . ':verificarCorreoYClave');

$app->get('/login', \Usuario::class . ':chequearJWT');

/***********************************************************************************/

$app->delete('/', \Auto::class . ':borrar')
->add(\MW::class . '::verificarPropietario')
->add(\MW::class . ':chequearJWT');

$app->put('/', \Auto::class . ':modificar')
->add(\MW::class . ':verificarEncargado')
->add(\MW::class . ':chequearJWT');

//CORRE LA APLICACIÓN.
$app->run();

?>