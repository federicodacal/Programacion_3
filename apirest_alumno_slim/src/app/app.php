<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) : Response {  

    $response->getBody()->write("Bienvenido!!! a CRUD de Alumnos con SlimFramework 4");
    return $response;
});

//*********************************************************************************************//
/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
//*********************************************************************************************//

require_once __DIR__ . '/../poo/alumno.php';
use \Slim\Routing\RouteCollectorProxy;

$app->group('/alumno', function (RouteCollectorProxy $grupo) {

    $grupo->get('/', \Alumno::class . ':traerTodos');
    $grupo->get('/{legajo}', \Alumno::class . ':verificar');
    $grupo->post('/', \Alumno::class . ':agregar');
    $grupo->put('/{alumno_json}', \Alumno::class . ':modificar');
    $grupo->delete('/{legajo}', \Alumno::class . ':borrar');
    $grupo->post('/{alumno_json}', \Alumno::class . ':logear');
    $grupo->get('/tabla/', \Alumno::class . ':mostrar');
});


//*********************************************************************************************//
/*CORRE APP*/
//*********************************************************************************************//

$app->run();



?>