<?php 

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";
require_once "./clases/neumaticoBD.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\NeumaticoBD;
use Dacal\Federico\AccesoDatos;

$tabla = isset($_GET["tabla"]) ? $_GET["tabla"] : NULL;

$neumaticos = NeumaticoBD::traer();

if(isset($tabla) && $tabla == 'tabla')
{
    if(isset($neumaticos) && count($neumaticos) > 0)
    {
        echo NeumaticoBD::mostrarTablaBD();
    }
}
else 
{
    $json_array = array();

    foreach($neumaticos as $n)
    {
        array_push($json_array, json_decode($n->ToJSON(), true));
    }

    echo json_encode($json_array);
}


?>