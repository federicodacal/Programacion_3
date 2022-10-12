<?php 

require_once './clases/Producto.php';

use Dacal\Federico\Producto;

$productos = Producto::traerJSON('./archivos/productos.json');

$json_array = array();

if(isset($productos))
{
    foreach($productos as $p)
    {
        array_push($json_array, json_decode($p->toJson(), true));
    }

    echo json_encode($json_array);
}

?>