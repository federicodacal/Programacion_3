<?php

require_once "./clases/accesoDatos.php";
require_once "./clases/neumatico.php";
require_once "./clases/neumaticoBD.php";

use Dacal\Federico\Neumatico;
use Dacal\Federico\NeumaticoBD;
use Dacal\Federico\AccesoDatos;

$neumatico_json = isset($_POST["neumatico_json"]) ? $_POST["neumatico_json"] : NULL;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

if(isset($neumatico_json) && isset($foto))
{
    $obj = json_decode($neumatico_json, true);
    
    $pathFoto = getPath($foto, $obj["marca"]);

    $neumaticoEnBd = NeumaticoBD::traerPorId($obj["id"]);

    $pathViejo = $neumaticoEnBd->getPathFoto();

    if(isset($neumaticoEnBd))
    {
        $neumatico = new NeumaticoBD($obj["marca"], $obj["medidas"], $obj["precio"], 
        $pathFoto, $obj["id"]);
        
        if($neumatico->modificar())
        {
            $exito = true;
            $mensaje = "Neumatico modificado. ";
            
            if(guardarImagen($pathFoto))
            {
                $mensaje .= "Se guardó foto nueva OK. ";
            }

            $extensionFoto = pathinfo($neumatico->getPathFoto(), PATHINFO_EXTENSION);

            $nuevoPathFoto = './neumaticosModificados/' . $neumatico->getId() . "." . $neumatico->getMarca() . "." . "modificado" . "." . date("His") . "." . $extensionFoto;

            if(rename($pathViejo, $nuevoPathFoto))
            {
                $mensaje .= "Se movió foto vieja OK.";
            }
        }
        else 
        {
            $mensaje = "No se modificó";
        }
    }
}

$response = array("exito"=>$exito, "mensaje"=>$mensaje);

echo json_encode($response);


function getPath(array $foto, string $marca): string
{
	if ($foto != NULL) {
		$foto_nombre = $_FILES["foto"]["name"];
		$extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
		$path = "./neumaticos/imagenes/" . $marca . "." . date("His") . "." . $extension;
		$uploadOk = TRUE;

		$array_extensiones = array("jpg", "jpeg", "gif", "png");

		for ($i = 0; $i < count($array_extensiones); $i++) {
			$nombre_archivo = "./neumaticos/imagenes/" . $marca . "." . date("His") . "." . $array_extensiones[$i];
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