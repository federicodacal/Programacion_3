<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";

use PrimerParcial\Usuario;
use PrimerParcial\AccesoDatos;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$clave = isset($_POST["clave"]) ? $_POST["clave"] : NULL;
$correo = isset($_POST["correo"]) ? $_POST["correo"] : NULL;
$id_perfil = isset($_POST["id_perfil"]) ? (int) $_POST["id_perfil"] : 0;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($nombre) && isset($clave) && isset($correo) && isset($id_perfil))
{
    $usuario = new Usuario($nombre, $correo, $clave, $id_perfil);

    if($usuario->Agregar())
    {
        $exito = true;
        $mensaje = "Agregado";
    }
    else 
    {
        $mensaje = "No se agregó";
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);

?>