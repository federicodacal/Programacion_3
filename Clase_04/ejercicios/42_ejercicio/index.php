<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 42</title>
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
        <input type="file" name="foto[]" multiple  accept="image/*"/> 
        <br/>
        <br/>
        <input type="submit" value="Agregar" />
    </form>


</body>
</html>

<?php
/*
Aplicación No 42 (Galería de Imágenes II)
Amplíe el ejercicio anterior y permita al usuario añadir múltiples fotos en una misma subida.
Para ello agregar el atributo ‘multiple’ en el input (type=”file”).
Del lado del servidor, verificar que cada archivo subido posea la extensión .jpg o .jpeg y que
su tamaño máximo no supere los 900 kb.
Si alguno de los archivos subidos no cumple con los requisitos expuestos anteriormente,
informarlo, caso contrario, agregarlo a la galería de imágenes.
*/
if(isset($_GET["img"])) 
{
    session_start();
    $_SESSION["img"] = $_GET["img"];
    header("location: img.php");
}

if(isset($_FILES["foto"]))
{
    if(subirImagenes())
    {
        header("location: index.php");
    }
}

function subirImagenes() : bool
{
    $ok = FALSE;

	//OBTENGO TODOS LOS NOMBRES DE LOS ARCHIVOS
    $nombres = $_FILES["foto"]["name"];

    //OBTENGO TODOS LOS TAMAÑOS DE LOS ARCHIVOS
    $sizes = $_FILES["foto"]["size"];

    //INDICO CUALES SERAN LOS DESTINOS DE LOS ARCHIVOS SUBIDOS Y SUS TIPOS
    $destinos = array();
    $tiposArchivo = array();
    foreach($nombres as $nombre){
        $destino = "./img/" . $nombre;
        array_push($destinos, $destino);
        array_push($tiposArchivo, pathinfo($destino, PATHINFO_EXTENSION));
    }

    $uploadOk = TRUE;

    //VERIFICO QUE LOS ARCHIVOS NO EXISTAN
    foreach($destinos as $destino){
        if (file_exists($destino)) {
            echo "El archivo {$destino} ya existe. Verifique!!!";
            $uploadOk = FALSE;
            break;
        }
    }
        
    //VERIFICO LOS TAMAÑOS MAXIMOS QUE PERMITO SUBIR
    foreach($sizes as $size){
        if ($size > 900000) {
            echo "Archivo demasiado grande. Verifique!!!";
            $uploadOk = FALSE;
            break;
        }
    }

    //VERIFICO SI ES UNA IMAGEN O NO

    //OBTIENE EL TAMAÑO DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA
    //IMAGEN, RETORNA FALSE
    $tmpsNames = $_FILES["foto"]["tmp_name"];
    $i=0;
    foreach($tmpsNames as $tmpName){
        
        $esImagen = getimagesize($tmpName);

        if($esImagen === FALSE) {//NO ES UNA IMAGEN
                echo "Solo son permitidos archivos de imagen.";
        }
        else {// ES UNA IMAGEN

            //SOLO PERMITO CIERTAS EXTENSIONES
            if($tiposArchivo[$i] != "jpg" && $tiposArchivo[$i] != "jpeg") {
                echo "Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.";
                $uploadOk = FALSE;
                break;
            }
        }
        $i++;
    }

    //VERIFICO SI HUBO ALGUN ERROR, CHEQUEANDO $uploadOk
    if ($uploadOk === FALSE) {

        echo "<br/>NO SE PUDIERON SUBIR LOS ARCHIVOS.";

    } else {
        //MUEVO LOS ARCHIVOS DEL TEMPORAL AL DESTINO FINAL
        for($i=0;$i<count($tmpsNames);$i++){
            if (move_uploaded_file($tmpsNames[$i], $destinos[$i])) {
                echo "<br/>El archivo ". basename($destinos[$i]). " ha sido subido exitosamente.";
                $ok = TRUE;
            } else {
                echo "<br/>Lamentablemente ocurri&oacute; un error y no se pudo subir el archivo ". basename( $tmpsNames[$i]).".";
            }
        }
    }

    return $ok;
}

?>