<?php

/*
Aplicación Nº 23 (Registro JSON)
Archivo: registro.php
método:POST
Recibe los datos del usuario (nombre, clave, mail) por POST,
Crear un ID autoincremental (emulado, puede ser un random de 1 a 10.000). Crear un dato
con la fecha de registro, toma todos los datos y utilizar sus métodos para poder hacer
el alta, guardando los datos en usuarios.json y subir la imagen al servidor en la carpeta
Usuario/Fotos/.
retorna si se pudo agregar o no.
Cada usuario se agrega en un renglón diferente al anterior.
Hacer los métodos necesarios en la clase usuario.

Aplicación Nº 24 (Listado JSON y array de usuarios)
método:GET
En el caso de usuarios carga los datos del archivo usuarios.json.
se deben cargar los datos en un array de usuarios.
Retorna los datos que contiene ese array en una lista
<ul>
<li>apellido, nombre,foto</li>
<li>apellido, nombre,foto</li>
</ul>
Hacer los métodos necesarios en la clase usuario

*/

require_once "./usuario.php";

$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"] : NULL;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$clave = isset($_POST["clave"]) ? $_POST["clave"] : NULL;
$mail = isset($_POST["mail"]) ? $_POST["mail"] : NULL;
$foto = isset($_FILES["foto"]) ? $_FILES["foto"] : NULL;

switch($accion)
{
    case 'agregar':

        if(isset($nombre) && isset($clave) && isset($mail))
        {
            $pathFoto = subirFoto($foto, $id=rand(1,1000));          
            $user = new Usuario($nombre, $clave, $mail, $pathFoto);

            if(Usuario::agregar($user))
            {
                echo "Agregado";
            }
            else 
            {
                echo "Hubo un problema";
            }
        }
        else 
        {
            echo "Parametros incompletos";
        }

        break;
    
    case 'leer':
        echo Usuario::leer();
        break;
    
    case 'listar':
        echo Usuario::listar();
        break;

    case 'leerJson':
        echo Usuario::leerJson();
        break;

    case 'login':
        echo Usuario::logear($clave, $mail);
        break;

    default:
        echo ":(";
        break;
}


function subirFoto(array $foto, int $id) : string 
{
    if(isset($foto))
    {
        $upload = false;
        
        $nombre = $_FILES["foto"]["name"];
        $extension = pathinfo($nombre, PATHINFO_EXTENSION);
        $path = "./archivos/fotos/" . $id . "." . $extension;

        $esImagen = getimagesize($_FILES["foto"]["tmp_name"]);
        if($esImagen)
        {
            if($extension == "jpg" || $extension == "jpeg" || $extension == "png")
            {
                $upload = true;
            }
        }

        if($upload === false)
        {
            $path = "./archivos/fotos/default.png";
        }
        else 
        {
            move_uploaded_file($_FILES["foto"]["tmp_name"], $path);
        }
    }
    return $path;
}

?>
