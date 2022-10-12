<?php 

require_once './clases/AccesoDatos.php';
require_once './clases/Producto.php';
require_once './clases/ProductoEnvasado.php';

use Dacal\Federico\Producto;
use Dacal\Federico\ProductoEnvasado;
use Dacal\Federico\AccesoDatos;

$producto_json = isset($_POST["producto_json"]) ? $_POST["producto_json"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($producto_json))
{
    $obj = json_decode($producto_json, true);

    $producto = new ProductoEnvasado($obj["nombre"], $obj["origen"], $obj["id"]);

    if(ProductoEnvasado::eliminar($obj["id"]))
    {
        $exito = true;
        $mensaje = "Eliminado. ";

        $json = json_decode($producto->guardarJSON('./archivos/productos_eliminados.json'), true);

        if(isset($json))
        {
            $mensaje .= $json["mensaje"];
        }
    }
    else 
    {
        $mensaje = "No se pudo eliminar";
    }
}

$response = array("exito"=>$exito,"mensaje"=>$mensaje);

echo json_encode($response);

?>