<?php  

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";
require_once "./clases/neumaticoBD.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\NeumaticoBD;
use Dacal\Federico\AccesoDatos;

$obj_neumatico = isset($_POST["obj_neumatico"]) ? $_POST["obj_neumatico"] : NULL;

if(isset($obj_neumatico))
{
    $obj = json_decode($obj_neumatico, true);

    $neumatico = new NeumaticoBD($obj["marca"], $obj["medidas"]);

    $neumaticos = NeumaticoBD::traer();

    if($neumatico->existe($neumaticos))
    {
        $exito = true;
        $mensaje = $neumatico->toJSON();
    }
    else 
    {
        $mensaje = "{}";
    }
}

echo $mensaje;

?>