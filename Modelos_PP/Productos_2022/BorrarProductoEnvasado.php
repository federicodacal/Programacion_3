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

    $producto = ProductoEnvasado::traerPorId($obj["id"]);

    if(isset($producto))
    {
        if(ProductoEnvasado::eliminar($producto->id))
        {
            $mensaje = "Eliminado. ";
            
            $json = $producto->guardarEnArchivo();

            $response = json_decode($json, true);

            $mensaje .= $response["mensaje"];
            $exito = $response["exito"];
        }
        else 
        {
            $mensaje = "No se elimino";
        }
    }
}

echo json_encode(array("exito"=>$exito,"mensaje"=>$mensaje));

?>