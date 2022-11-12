<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface ISlimeable 
{
    function traerTodos(Request $request, Response $response, array $args) : Response;
    function traerUno(Request $request, Response $response, array $args) : Response;
    function agregar(Request $request, Response $response, array $args) : Response;
    function modificar(Request $request, Response $response, array $args) : Response;
    function borrar(Request $request, Response $response, array $args) : Response;
    function logear(Request $request, Response $response, array $args) : Response;
}

?>