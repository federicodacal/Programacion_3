<?php

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\AccesoDatos;

$marca = isset($_POST["marca"]) ? $_POST["marca"] : NULL;
$medidas = isset($_POST["medidas"]) ? $_POST["medidas"] : NULL;
$precio = isset($_POST["precio"]) ? (float) $_POST["precio"] : 0;

if(isset($marca) && isset($medidas) && isset($precio))
{
    $neumatico = new Neumatico($marca, $medidas, $precio);

    if($response = $neumatico->guardarJSON('./archivos/neumaticos.json'))
    {
        echo "Neumatico guardado\n";
        echo $response;
    }
    else 
    {
        echo "Hubo un problema";
    }
}
else 
{
    echo "No se recibieron los datos";
}


?>