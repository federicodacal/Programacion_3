<?php 

/*
Crear las tablas para usuarios

perfiles (id, descripcion, estado)
usuarios (id, nombre, apellido, correo, foto, id_perfil, clave)

Crear un ApiRest para gestionar un ABM de usuarios,
con su respectivo 'Login'.

Para el 'Login', ingresar correo y clave. Retornar un
JSON que indique si está registrado en la base de datos
o no.

En todos los casos, retornar un JSON, indicando lo
acontecido.
*/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) : Response {  

    $response->getBody()->write("Bienvenido!!! a CRUD de Usuarios con SlimFramework 4");
    return $response;
});

//*********************************************************************************************//
/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
//*********************************************************************************************//

require_once __DIR__ . '/../poo/usuario.php';
use \Slim\Routing\RouteCollectorProxy;

$app->group('/usuario', function (RouteCollectorProxy $grupo) {

    $grupo->get('/', \Usuario::class . ':traerTodos');
    $grupo->get('/{id}', \Usuario::class . ':traerUno');
    $grupo->post('/', \Usuario::class . ':agregar');
    $grupo->put('/{usuario_json}', \Usuario::class . ':modificar');
    $grupo->delete('/{id}', \Usuario::class . ':borrar');
    $grupo->post('/login/', \Usuario::class . ':logear');
});


//*********************************************************************************************//
/*CORRE APP*/
//*********************************************************************************************//

$app->run();



?>