<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 43</title>
</head>
<body>
    
    <?php 
        echo mostrarArchivos();
    ?>
    
    <hr>
    <br>

    <form action="./index.php" method="post" enctype="multipart/form-data" >
        <input type="file" name="archivo" /> 
        <br/>
        <br/>
        <input type="submit" value="Agregar" />
    </form>
</body>
</html>

<?php 
/*
Se necesita crear una página que le permita al usuario subir al servidor web cualquier tipo de
archivo. Sólo se restringirá el tamaño de cada archivo según el tipo de extensión que posea.
Para archivos con extensión .doc o .docx el tamaño máximo será de 60 Kb.
Archivos con extensión .jpg, jpeg o gif el valor máximo será de 300 kb.
Para el resto de las extensiones el máximo permitido será de 500 kb.
Dichos archivos se almacenarán en una carpeta llamada ‘Uploads’ que se ubicará en el
directorio raíz del servidor web.
Se deberá informar si se logró subir el archivo o no. Si se pudo, informar el nombre del archivo,
su extensión y que tamaño posee.
*/

if(isset($_FILES["archivo"]))
{
    $filepath = getPath($_FILES["archivo"]);

    if(isset($filepath))
    {
        guardarArchivo($filepath);
        header("location: index.php");
    }
}

function mostrarArchivos() : string 
{
    $tabla = "<table>";

    $files = array();

    foreach (scandir('./uploads') as $file) 
    {
        if ($file !== '.' && $file !== '..') 
        {
            $files[] = $file;
            $path = './uploads/';
            $path .= pathinfo($file, PATHINFO_FILENAME) . "." . pathinfo($file, PATHINFO_EXTENSION);

            $tabla .= 
            "<tr>
                    <td>";
            $tabla .= $path;
            $tabla .= "</td>
            </tr>";
        }
    }

    $tabla .= "</table>";

    return $tabla;
}

function getPath(array $archivo): string
{
	if ($archivo != NULL) {
		$archivo_nombre = $_FILES["archivo"]["name"];
		$extension = pathinfo($archivo_nombre, PATHINFO_EXTENSION);
		$path = "./uploads/" . $archivo_nombre . "." . $extension;
		$uploadOk = TRUE;

		$array_extensiones = array("jpg", "jpeg", "gif", "png", "doc", "docx", "txt", "csv", "pdf");

		for ($i = 0; $i < count($array_extensiones); $i++) {
			$nombre_archivo = $path . "." . $array_extensiones[$i];
			if (file_exists($nombre_archivo)) {
				unlink($nombre_archivo);
				break;
			}
		}

        $size = $_FILES["archivo"]["size"];

        if($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "png")
        {
            if($size > 300000)
            {
                $uploadOk = FALSE;
            }
        }
        else if($extension == "doc" || $extension == "docx")
        {
            if($size > 60000)
            {
                $uploadOk = FALSE;
            }
        }
        else 
        {
            if($size > 500000)
            {
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

function guardarArchivo(string $path): bool
{
	if(!isset($_FILES["archivo"])){
		return false;
	}
	return move_uploaded_file($_FILES["archivo"]["tmp_name"], $path);
}
?>