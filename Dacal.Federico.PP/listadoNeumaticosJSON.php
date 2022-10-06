<?php 

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\AccesoDatos;

$neumaticos = Neumatico::traerJSON("./archivos/neumaticos.json");

foreach($neumaticos as $neumatico)
{
    echo $neumatico->ToJSON() . "\n\n";
}

?>