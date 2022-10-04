<?php 

require_once "./accesoDatos.php";
require_once "./alumno_pdo.php";

use Poo\AccesoDatos;
use Poo\AlumnoPDO;

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
            $pathFoto = getPath($foto, $legajo);
        }

        $alumno = new AlumnoPDO($nombre, $apellido, $legajo, $pathFoto);

        if(AlumnoPDO::agregar($alumno))
        {
            guardarImagen($pathFoto);
            echo "Alumno agregado<br>";
        }
        else 
        {
            echo "Ocurrio un problema";
        }

		break;

    case "listar":

        $alumnos = AlumnoPDO::traerTodos();

        foreach($alumnos as $alumno)
        {
            echo $alumno->toString();
            echo "\n";
        }

        break;
        case "modificar":
            
            if(isset($foto))
            {
                $pathFoto = getPath($foto, $legajo);
            }
            
            $alumno = new AlumnoPDO($nombre, $apellido, $legajo, $pathFoto);
            
            if(AlumnoPDO::modificar($alumno))
            {
                guardarImagen($pathFoto);
                echo "Alumno modificado<br>";
            }
            else 
            {
                echo "No encontrado";
            }
        
        break;        
        
	case "borrar":				

        if(AlumnoPDO::borrar($legajo))
        {
            echo "Alumno borrado";
        }
        else
        {
            echo "No encontrado";
        }
        
		break;
        
    case "obtener":

        $alumno = AlumnoPDO::obtener($legajo);
        if(isset($alumno))
        {
            //var_dump($alumno);
            echo $alumno->toString();
        }
        else 
        {
            echo "No encontrado alumno con legajo {$legajo}";
        }

        break;

    case "mostrar":

        echo AlumnoPDO::mostrarAlumnos();

        break;

    default:
        echo ":(";
        break;
}


function getPath(array $foto, int $legajo): string
{
	if ($foto != NULL) {
		//INDICO CUAL SERA EL DESTINO DE LA FOTO SUBIDA
		$foto_nombre = $_FILES["foto"]["name"];
		$extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
		$path = "../fotos/" . $legajo . "." . $extension;
		$uploadOk = TRUE;

		//PATHINFO RETORNA UN ARRAY CON INFORMACION DEL PATH
		//RETORNA : NOMBRE DEL DIRECTORIO; NOMBRE DEL ARCHIVO; EXTENSION DEL ARCHIVO

		//PATHINFO_DIRNAME - retorna solo nombre del directorio
		//PATHINFO_BASENAME - retorna solo el nombre del archivo (con la extension)
		//PATHINFO_EXTENSION - retorna solo extension
		//PATHINFO_FILENAME - retorna solo el nombre del archivo (sin la extension)

		//VERIFICO QUE EL ARCHIVO NO EXISTA
		$array_extensiones = array("jpg", "jpeg", "gif", "png");
		for ($i = 0; $i < count($array_extensiones); $i++) {
			$nombre_archivo = "../fotos/" . $legajo . "." . $array_extensiones[$i];
			if (file_exists($nombre_archivo)) {
				unlink($nombre_archivo);
				break;
			}
		}

		//VERIFICO EL TAMAÑO MAXIMO QUE PERMITO SUBIR
		if ($_FILES["foto"]["size"] > 1000000) {
			$uploadOk = FALSE;
		}

		//OBTIENE EL TAMAÑO DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA
		//IMAGEN, RETORNA FALSE
		$esImagen = getimagesize($_FILES["foto"]["tmp_name"]);

		if ($esImagen) {
			if (
				$extension != "jpg" && $extension != "jpeg" && $extension != "gif"
				&& $extension != "png"
			) {
				echo "Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.";
				$uploadOk = FALSE;
			}
		}

		//VERIFICO SI HUBO ALGUN ERROR, CHEQUEANDO $uploadOk
		if ($uploadOk === FALSE) {
			echo "<br/>NO SE PUDO SUBIR EL ARCHIVO.";
			$path = "";
		}
	}
	return $path;
}

function guardarImagen(string $path): bool
{
	if(!isset($_FILES["foto"])){
		return false;
	}
	return move_uploaded_file($_FILES["foto"]["tmp_name"], $path);
}


?>