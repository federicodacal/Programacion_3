<?php  

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\AccesoDatos;

$marca = isset($_POST["marca"]) ? $_POST["marca"] : NULL;
$medidas = isset($_POST["medidas"]) ? $_POST["medidas"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($marca) && isset($medidas))
{
    $neumatico = new Neumatico($marca, $medidas);
    $cadena = Neumatico::verificarNeumaticoJSON($neumatico);

    if(isset($neumatico))
    {
        $exito = true;
        $mensaje = "Neumatico encontrado\n";
        $mensaje .= $cadena;
    }
    else 
    {
        $mensaje = "No se encontró\n";
        $mensaje .= $cadena;
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);


?>