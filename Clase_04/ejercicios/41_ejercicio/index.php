<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 41</title>
</head>
<body>

    <?php 

    $tabla = "<table>";

    $files = array();

    foreach (scandir('./img') as $file) 
    {
        if ($file !== '.' && $file !== '..') 
        {
            $files[] = $file;
            $path = './img/';
            $path .= pathinfo($file, PATHINFO_FILENAME) . "." . pathinfo($file, PATHINFO_EXTENSION);

            $tabla .= "<tr>
                        <td>";
            $tabla .= "<img src='" . $path . "' alt='sin foto' width='100px' height='100px'>";
            $tabla .= "</td>
            </tr>";
        }
    }

    $tabla .= "</table>";

    echo $tabla;
    ?>
    
    <hr>
    <form action="./index.php" method="post" enctype="multipart/form-data" >
        <input type="file" name="foto" /> 
        <br/>
        <br/>
        <input type="submit" value="Agregar" />
    </form>


</body>
</html>

<?php
/*
Aplicación No 41 (Galería de Imágenes)
Amplíe el ejercicio de la galería de fotos realizada anteriormente y permita al usuario añadir
nuevas fotos. Para ello hay que poner el atributo enc_type=”multipart/form-data” en el FORM y
usar la variable $_FILES['foto'].
*/
if(isset($_GET["img"])) 
{
    session_start();
    $_SESSION["img"] = $_GET["img"];
    header("location: img.php");
}

if(isset($_FILES["foto"]))
{
    $pathFoto = getPath($_FILES["foto"]);

    if(isset($pathFoto))
    {
        guardarImagen($pathFoto);
        header("location: index.php");
    }
}

function getPath(array $foto): string
{
	if ($foto != NULL) {
		$foto_nombre = $_FILES["foto"]["name"];
		$extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
		$path = "./img/" . $foto_nombre . "." . $extension;
		$uploadOk = TRUE;

		$array_extensiones = array("jpg", "jpeg", "gif", "png");

		for ($i = 0; $i < count($array_extensiones); $i++) {
			$nombre_archivo = $path . "." . $array_extensiones[$i];
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