<?php 

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\AccesoDatos;

$neumaticos = Neumatico::traerJSON("./archivos/neumaticos.json");

/*
if(isset($neumaticos))
{
    foreach($neumaticos as $neumatico)
    {
        echo $neumatico->ToJSON() . "\n";
    }
}
*/

$json_array = array();

if(isset($neumaticos))
{
    foreach($neumaticos as $n)
    {
        array_push($json_array, json_decode($n->ToJSON(), true));
    }
}


echo json_encode($json_array);


?>