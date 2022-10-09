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
    
    $json = json_decode(Neumatico::verificarNeumaticoJSON($neumatico), true);

    if(isset($json))
    {
        $exito = $json["exito"];
        $mensaje = $json["mensaje"];;
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);


?>