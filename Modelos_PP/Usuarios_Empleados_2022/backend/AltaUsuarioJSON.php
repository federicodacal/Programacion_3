<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";

use PrimerParcial\Usuario;
use PrimerParcial\AccesoDatos;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$clave = isset($_POST["clave"]) ? $_POST["clave"] : NULL;
$correo = isset($_POST["correo"]) ? $_POST["correo"] : NULL;

if(isset($nombre) && isset($clave) && isset($correo))
{
    $usuario = new Usuario($nombre, $correo, $clave);

    if(isset($usuario))
    {
        echo $usuario->GuardarEnArchivo();
    }
}

?>