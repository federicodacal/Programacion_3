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

    $producto = new ProductoEnvasado($obj["nombre"], $obj["origen"], 0, $obj["codigoBarra"], $obj["precio"]);

    if($producto->agregar())
    {
        $exito = true;
        $mensaje = "Agregado";
    }
    else 
    {
        $mensaje = "No se pudo agregar";
    }
}

$response = array("exito"=>$exito,"mensaje"=>$mensaje);

echo json_encode($response);

?>