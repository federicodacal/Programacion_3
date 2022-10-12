<?php 

require_once './clases/AccesoDatos.php';
require_once './clases/Producto.php';
require_once './clases/ProductoEnvasado.php';

use Dacal\Federico\Producto;
use Dacal\Federico\ProductoEnvasado;
use Dacal\Federico\AccesoDatos;

$obj_producto = isset($_POST["obj_producto"]) ? $_POST["obj_producto"] : NULL;

$response = "Hubo un problema";

if(isset($obj_producto))
{
    $obj = json_decode($obj_producto, true);

    $producto = new ProductoEnvasado($obj["nombre"], $obj["origen"]);

    $productos = ProductoEnvasado::traer();

    if($producto->existe($productos))
    {
        $response = $producto->toJson();
    }
    else 
    {
        $response = "{}";
    }
}

echo $response;

?>