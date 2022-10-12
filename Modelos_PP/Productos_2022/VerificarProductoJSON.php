<?php 

require_once './clases/Producto.php';

use Dacal\Federico\Producto;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$origen = isset($_POST["origen"]) ? $_POST["origen"] : NULL;

$exito = false;
$mensaje = "Ocurrio un problema";

if(isset($nombre) && isset($origen))
{
    $producto = new Producto($nombre, $origen);

    $json = json_decode(Producto::verificarProductoJSON($producto), true);

    if(isset($json))
    {
        $exito = $json["exito"];
        $mensaje = $json["mensaje"];

        if($exito)
        {
            setcookie($nombre . "_" . $origen, date('YmdHis') . $mensaje, time()+3600, '/');
        }
    }
}

$response = array("exito"=>$exito,"mensaje"=>$mensaje);

echo json_encode($response);

?>