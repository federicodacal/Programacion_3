<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";
require_once "./clases/Empleado.php";

use PrimerParcial\Usuario;
use PrimerParcial\Empleado;
use PrimerParcial\AccesoDatos;

echo Empleado::MostrarTablaBD();


?>