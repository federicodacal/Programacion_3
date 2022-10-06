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

    if(NeumaticoBD::eliminar($neumatico->GetId()))
    {
        $exito = true;
        $mensaje = "Neumatico eliminado\n";  
        $mensaje .= $neumatico->guardarJSON('./archivos/neumaticos_eliminados.json');
    }
    else 
    {
        $mensaje = "No se encontró";
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);

?>