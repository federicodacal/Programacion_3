<?php  

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";
require_once "./clases/neumaticoBD.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\NeumaticoBD;
use Dacal\Federico\AccesoDatos;

$obj_neumatico = isset($_POST["obj_neumatico"]) ? $_POST["obj_neumatico"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($obj_neumatico))
{
    
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);


?>