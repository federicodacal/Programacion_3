<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";

use PrimerParcial\Usuario;
use PrimerParcial\AccesoDatos;

$usuarios = Usuario::TraerTodos();

if(isset($usuarios) && count($usuarios) > 0)
{
    echo "<ul>";
    foreach($usuarios as $usuario)
    {
        echo "<li>" . $usuario->ToJSON() . "</li>";
    }
    echo "<ul>";
}
else 
{
    echo "No hay usuarios";
}


?>