<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";
require_once "./clases/Empleado.php";

use PrimerParcial\Usuario;
use PrimerParcial\Empleado;
use PrimerParcial\AccesoDatos;

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($id))
{
    if(Empleado::Eliminar($id))
    {
        $exito = true;
        $mensaje = "Empleado eliminado";
    }
    else 
    {
        $mensaje = "No se elimino";
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);


?>