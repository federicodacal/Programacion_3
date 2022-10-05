<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";

use PrimerParcial\Usuario;
use PrimerParcial\AccesoDatos;

$tabla = isset($_GET["tabla"]) ? $_GET["tabla"] : NULL;

if(isset($tabla))
{
    if($tabla == 'mostrar')
    {
        echo Usuario::MostrarTablaBD();
    }
}

?>