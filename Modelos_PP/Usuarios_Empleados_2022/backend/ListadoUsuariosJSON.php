<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";

use PrimerParcial\Usuario;
use PrimerParcial\AccesoDatos;

echo Usuario::UsuariosToJSON();

/*
$usuarios = Usuario::TraerTodosJSON();

foreach($usuarios as $usuario)
{
    echo $usuario->ToJSON() . "\n\n";
}
*/

?>