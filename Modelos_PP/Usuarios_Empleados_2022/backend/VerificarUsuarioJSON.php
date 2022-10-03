<?php  

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";

use PrimerParcial\Usuario;
use PrimerParcial\AccesoDatos;

$usuario_json = isset($_POST["usuario_json"]) ? $_POST["usuario_json"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($usuario_json))
{
    $usuario = Usuario::TraerUno($usuario_json);

    if(isset($usuario))
    {
        $exito = true;
        $mensaje = "Usuario encontrado: {$usuario->nombre}";
    }
    else 
    {
        $mensaje = "No se encontró";
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);


?>