<?php 

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";
require_once "./clases/neumaticoBD.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\NeumaticoBD;
use Dacal\Federico\AccesoDatos;

$marca = isset($_POST["marca"]) ? $_POST["marca"] : NULL;
$medidas = isset($_POST["medidas"]) ? $_POST["medidas"] : NULL;
$precio = isset($_POST["precio"]) ? (float) $_POST["precio"] : 0;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

$pathFoto = "";

if(isset($marca) && isset($medidas) && isset($precio) && isset($foto))
{
    $neumatico = new NeumaticoBD($marca, $medidas, $precio, $foto);

    $pathFoto = getPath($foto, $marca);

    if($neumatico->agregar())
    {
        $exito = true;
        $mensaje = "Agregado";
    }
    else 
    {
        $mensaje = "No se agregó";
    }
}
else 
{
    $mensaje = "Faltan parametros";
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);


function getPath(array $foto, string $marca): string
{
	if ($foto != NULL) {
		//INDICO CUAL SERA EL DESTINO DE LA FOTO SUBIDA
		$foto_nombre = $_FILES["foto"]["name"];
		$extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
		$path = "./neumaticos/imagenes/" . $marca . "." . $extension;
		$uploadOk = TRUE;

		$array_extensiones = array("jpg", "jpeg", "gif", "png");
		for ($i = 0; $i < count($array_extensiones); $i++) {
			$nombre_archivo = "./neumaticos/imagenes/" . $marca . "." . date("Y_m_d_H_i_s") . $array_extensiones[$i];
			if (file_exists($nombre_archivo)) {
				unlink($nombre_archivo);
				break;
			}
		}

		if ($_FILES["foto"]["size"] > 100000) {
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
	if(!isset($_FILES["foto"])){
		return false;
	}
	return move_uploaded_file($_FILES["foto"]["tmp_name"], $path);
}

?>