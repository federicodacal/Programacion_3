<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Producto.php";
require_once "./clases/ProductoEnvasado.php";

use Dacal\Federico\Producto;
use Dacal\Federico\ProductoEnvasado;
use Dacal\Federico\AccesoDatos;

$tabla = isset($_GET["tabla"]) ? $_GET["tabla"] : NULL;

$productos = ProductoEnvasado::traer();

if(isset($tabla) && $tabla == 'mostrar')
{
    if(isset($productos) && count($productos) > 0)
    {
        echo ProductoEnvasado::mostrarTablaBD();
    }
}
else 
{
    $json_array = array();

    foreach($productos as $n)
    {
        array_push($json_array, json_decode($n->ToJSON(), true));
    }

    echo json_encode($json_array);
}


?>