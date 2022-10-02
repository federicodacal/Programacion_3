<?php 
/*
Archivo: registro.php
método:POST
Recibe los datos del usuario(nombre, clave, mail) por POST ,
crear un objeto y utilizar sus métodos para poder hacer el alta,
guardando los datos en usuarios.csv.
retorna si se pudo agregar o no.
Cada usuario se agrega en un renglón diferente al anterior.
Hacer los métodos necesarios en la clase usuario

Aplicación Nº 21 (Listado CSV y array de usuarios)
método:GET
Se deben cargar los datos en un array de usuarios.
Retorna los datos que contiene ese array en una lista
<ul>
<li>Coffee</li>
<li>Tea</li>
<li>Milk</li>
</ul>
Hacer los métodos necesarios en la clase usuario

Aplicación Nº 22 (Login)
método:POST
Recibe los datos del usuario(clave,mail )por POST ,
crear un objeto y utilizar sus métodos para poder verificar si es un usuario registrado,
Retorna un :
“Verificado” si el usuario existe y coincide la clave también.
“Error en los datos” si esta mal la clave.
“Usuario no registrado si no coincide el mail“
Hacer los métodos necesarios en la clase usuario
*/

require_once "./usuario.php";

$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"] : NULL;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$clave = isset($_POST["clave"]) ? $_POST["clave"] : NULL;
$mail = isset($_POST["mail"]) ? $_POST["mail"] : NULL;

switch($accion)
{
    case 'agregar':

        if(isset($nombre) && isset($clave) && isset($mail))
        {
            $user = new Usuario($nombre, $clave, $mail);

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
    
    case 'login':
        echo Usuario::logear($clave, $mail);
        break;

    default:
        echo ":(";
        break;
}

?>