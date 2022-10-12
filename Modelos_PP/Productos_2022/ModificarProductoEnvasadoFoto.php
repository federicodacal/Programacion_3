<?php 

require_once './clases/AccesoDatos.php';
require_once './clases/Producto.php';
require_once './clases/ProductoEnvasado.php';

use Dacal\Federico\Producto;
use Dacal\Federico\ProductoEnvasado;
use Dacal\Federico\AccesoDatos;

$producto_json = isset($_POST["producto_json"]) ? $_POST["producto_json"] : NULL;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : NULL;

$exito = false;
$mensaje = "Hubo un problema";

$pathFoto = "";

if(isset($producto_json) && isset($foto))
{
    $obj = json_decode($producto_json, true);

    $pathFoto = getPath($foto, $obj["nombre"], $obj["origen"]);

    $productoBD = ProductoEnvasado::traerPorId($obj["id"]);

    $pathViejo = $productoBD->pathFoto;

    $producto = new ProductoEnvasado($obj["nombre"],$obj["origen"], $obj["id"], $obj["codigoBarra"], $obj["precio"], $pathFoto);

    if($producto->modificar())
    {
        $exito = true;
        $mensaje = "Modificado. ";

        if(guardarImagen($pathFoto))
        {
            $mensaje .= "Foto nueva OK. ";
        }

        $extensionFoto = pathinfo($producto->pathFoto, PATHINFO_EXTENSION);

        $nuevoPathFoto = './productosModificados/' . $producto->nombre . "." . $producto->origen . "." . "modificado" . "." . date('His') . "." . $extensionFoto;

        if(rename($pathViejo, $nuevoPathFoto))
        {
            $mensaje .= "Se movio foto vieja OK.";
        }
    }
    else 
    {
        $mensaje = "No se modifico";
    }
}

$response = array("exito"=>$exito,"mensaje"=>$mensaje);

echo json_encode($response);


function getPath(array $foto, string $nombre, string $origen): string
{
	if ($foto != NULL) {
		$foto_nombre = $_FILES["foto"]["name"];
		$extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
		$path = "./productos/imagenes/" . $nombre . "." . $origen ."." . date("His") . "." . $extension;
		$uploadOk = TRUE;

		$array_extensiones = array("jpg", "jpeg", "gif", "png");

		for ($i = 0; $i < count($array_extensiones); $i++) {
			$nombre_archivo = "./productos/imagenes/" . $nombre . "." . $origen . "." . date("His") . "." . $array_extensiones[$i];
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