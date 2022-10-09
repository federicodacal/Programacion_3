<?php 

require_once "./clases/AccesoDatos.php";
require_once "./clases/Usuario.php";
require_once "./clases/Empleado.php";

use PrimerParcial\Usuario;
use PrimerParcial\Empleado;
use PrimerParcial\AccesoDatos;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$correo = isset($_POST["correo"]) ? $_POST["correo"] : NULL;
$clave = isset($_POST["clave"]) ? $_POST["clave"] : NULL;
$id_perfil = isset($_POST["id_perfil"]) ? $_POST["id_perfil"] : NULL;
$sueldo = isset($_POST["sueldo"]) ? $_POST["sueldo"] : NULL;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($nombre) && isset($correo) && isset($clave) && isset($id_perfil) && isset($sueldo) && isset($foto))
{
    $path = getPath($foto, $nombre);

    $empleado = new Empleado($nombre, $correo, $clave, $id_perfil, "", 0, $path, $sueldo);

    if($empleado->Agregar())
    {
        $exito = true;
        $mensaje = "Se agregó empleado. ";
        
		if(guardarImagen($path))
		{
			$mensaje .= "Foto OK";
		}
		else 
		{
			$mensaje .= "No Foto";
		}
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

$json = json_encode($response);

echo $json;

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