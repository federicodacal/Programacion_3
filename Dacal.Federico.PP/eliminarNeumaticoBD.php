<?php

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";
require_once "./clases/neumaticoBD.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\NeumaticoBD;
use Dacal\Federico\AccesoDatos;

$neumatico_json = isset($_POST["neumatico_json"]) ? $_POST["neumatico_json"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($neumatico_json))
{
    $obj = json_decode($neumatico_json, true);

    $neumatico = new NeumaticoBD($obj["marca"], $obj["medidas"], $obj["precio"], "", $obj["id"]);

    if(NeumaticoBD::eliminar($neumatico->getId()))
    {
        $exito = true;
        $mensaje = "Neumatico eliminado. ";  

        $response = $neumatico->guardarJSON('./archivos/neumaticos_eliminados.json');

        $objResponse = json_decode($response, true);

        $mensaje .= $objResponse["mensaje"];
    }
    else 
    {
        $mensaje = "No se encontró";
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);

?>