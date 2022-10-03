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
    $obj = json_decode($usuario_json, true);

    $usuario = new Usuario($obj["nombre"], $obj["correo"], $obj["clave"], $obj["id_perfil"], "", $obj["id"]);

    if($usuario->Modificar())
    {
        $exito = true;
        $mensaje = "Usuario modificado";
    }
    else 
    {
        $mensaje = "No se modifico";
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);

?>