<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/empleado.php";
require_once "./clases/Empleado.php";

use PrimerParcial\Usuario;
use PrimerParcial\Empleado;
use PrimerParcial\AccesoDatos;

$empleado_json = isset($_POST["empleado_json"]) ? $_POST["empleado_json"] : NULL;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

$pathFoto = "";

if(isset($empleado_json) && isset($foto))
{
    $obj = json_decode($empleado_json, true);

    $pathFoto = getPath($foto, $obj["nombre"]);

    $empleado = new Empleado($obj["nombre"], $obj["correo"], $obj["clave"], $obj["id_perfil"], "", $obj["id"], $obj["path_foto"], $obj["sueldo"]);

    if($empleado->Modificar())
    {
        $exito = true;
        $mensaje = "Empleado modificado";
        guardarImagen($pathFoto);
    }
    else 
    {
        $mensaje = "No se modifico";
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);

function getPath(array $foto, string $nombre): string
{
	if ($foto != NULL) {

		$foto_nombre = $_FILES["foto"]["name"];
		$extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
        $nombreArchivo = $nombre . "." . date("G").date("i").date("s");    
		$path = "./backend/empleados/fotos/" . $nombreArchivo . "." . $extension;
		$uploadOk = TRUE;

		$array_extensiones = array("jpg", "jpeg", "gif", "png");
		for ($i = 0; $i < count($array_extensiones); $i++) {
			$nombre_archivo = "./backend/empleados/fotos/" . $nombreArchivo . "." . $array_extensiones[$i];
			if (file_exists($nombre_archivo)) {
				unlink($nombre_archivo);
				break;
			}
		}

		if ($_FILES["foto"]["size"] > 10000000) {
			$uploadOk = FALSE;
		}

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

		if ($uploadOk === FALSE) {
			echo "<br/>NO SE PUDO SUBIR EL ARCHIVO.";
			$path = "";
		}
	}
	return $path;
}

function guardarImagen(string $path): bool
{
	if(! isset($_FILES["foto"])){
		return false;
	}
	return move_uploaded_file($_FILES["foto"]["tmp_name"], "." . $path);
}

?>