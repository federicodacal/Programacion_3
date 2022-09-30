<?php 

require_once "./alumno.php";

use Apellido\Alumno;

$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"] : "";

$legajo = isset($_POST["legajo"]) ? (int) $_POST["legajo"] : 0;
$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "...";
$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "...";
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : NULL;

$pathFoto = "";

switch($accion)
{
	case "agregar":

        if(isset($foto))
        {
            $pathFoto = subirFoto($foto, $legajo);
        }

        $alumno = new Alumno($nombre, $apellido, $legajo, $pathFoto);

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

        if(isset($foto))
        {
            $pathFoto = subirFoto($foto, $legajo);
        }
        
        $alumno = new Alumno($nombre, $apellido, $legajo, $pathFoto);

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

    case "obtener":

        $alumno = Alumno::verificar($legajo);
        if(isset($alumno))
        {
            var_dump($alumno);
        }
        else 
        {
            echo "No encontrado alumno con legajo {$legajo}";
        }

        break;
    
    case "redirigir":

        $alumno = Alumno::verificar($legajo);
        if(isset($alumno))
        {
            header("Location: ../principal.php");
        }
        else 
        {
            echo "No encontrado alumno con legajo {$legajo}";
        }

        break;
}

function subirFoto(array $foto, int $legajo) : string 
{
    if(isset($foto))
    {
        $upload = false;
        
        $nombre = $_FILES["foto"]["name"];
        $extension = pathinfo($nombre, PATHINFO_EXTENSION);
        $path = "../fotos/" . $legajo . "." . $extension;

        $esImagen = getimagesize($_FILES["foto"]["tmp_name"]);
        if($esImagen)
        {
            if($extension == "jpg" || $extension == "jpeg" || $extension == "png")
            {
                $upload = true;
            }
        }

        if($upload === false)
        {
            $path = "Sin foto";
        }
        else 
        {
            move_uploaded_file($_FILES["foto"]["tmp_name"], $path);
        }
    }
    return $path;
}


?>