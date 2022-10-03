<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";

use PrimerParcial\Usuario;
use PrimerParcial\AccesoDatos;

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($id))
{
    if(Usuario::Eliminar($id))
    {
        $exito = true;
        $mensaje = "Usuario eliminado";
    }
    else 
    {
        $mensaje = "No se modifico";
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);


?>