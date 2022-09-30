<?php 

require_once "./alumno.php";

use Apellido\Alumno;

$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"] : "";

$legajo = isset($_POST["legajo"]) ? (int) $_POST["legajo"] : 0;
$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "...";
$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "...";

switch($accion)
{
	case "agregar":

        $alumno = new Alumno($nombre, $apellido, $legajo);

        if(Alumno::agregar($alumno))
        {
            echo "Alumno agregado<br>";
        }
        else 
        {
            echo "Ocurrio un problema";
        }

		break;

    case "listar":

        echo Alumno::listar();

        break;

    case "verificar":

        $alumno = Alumno::verificar($legajo);
        if(isset($alumno))
        {
            echo "Alumno encontrado <br>";
            echo $alumno->toString();
        }
        else
        {
            echo "Alumno no encontrado";
        }

        break;

    case "modificar":
        
        $alumno = new Alumno($nombre, $apellido, $legajo);

        if(Alumno::modificar($alumno))
        {
            echo "Alumno modificado<br>";
        }
        else 
        {
            echo "No encontrado";
        }

        break;

	case "borrar":				

        if(Alumno::borrar($legajo))
        {
            echo "Alumno borrado";
        }
        else
        {
            echo "No encontrado";
        }

		break;	
}

?>