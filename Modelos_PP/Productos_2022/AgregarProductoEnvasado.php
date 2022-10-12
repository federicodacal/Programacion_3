<?php 

require_once './clases/AccesoDatos.php';
require_once './clases/Producto.php';
require_once './clases/ProductoEnvasado.php';

use Dacal\Federico\Producto;
use Dacal\Federico\ProductoEnvasado;
use Dacal\Federico\AccesoDatos;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$origen = isset($_POST["origen"]) ? $_POST["origen"] : NULL;
$codigoBarra = isset($_POST["codigoBarra"]) ? $_POST["codigoBarra"] : NULL;
$precio = isset($_POST["precio"]) ? (float) $_POST["precio"] : 0;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : NULL;

$exito = false;
$mensaje = "Ocurrio un problema";

$pathFoto = "";

if(isset($nombre) && isset($origen) && isset($codigoBarra) && isset($precio) && isset($foto))
{
    $productos = ProductoEnvasado::traer();

    $pathFoto = getPath($foto, $nombre, $origen);

    $producto = new ProductoEnvasado($nombre, $origen, 0, $codigoBarra, $precio, $pathFoto);

    if($producto->existe($productos))
    {
        $mensaje = "El producto ya existe en la base de datos";
    }
    else 
    {
        if($producto->agregar())
        {
            $exito = true;
            $mensaje = "Agregado. ";

            if(guardarImagen($pathFoto))
            {
                $mensaje .= "Foto OK";
            }
            else 
            {
                $mensaje .= "Problema guardando foto";
            }
        }
    }
}
else 
{
    $mensaje = "Faltan parametros";
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

		if ($_FILES["foto"]["size"] > 150000) {
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