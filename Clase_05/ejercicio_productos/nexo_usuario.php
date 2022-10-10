<?php 
/*
Aplicación Nº 27 (Registro BD)
Archivo: registro.php
método:POST
Recibe los datos del usuario (nombre,apellido,clave,mail,localidad) por POST ,
crear un objeto con la fecha de registro y utilizar sus métodos para poder hacer el alta,
guardando los datos la base de datos
retorna si se pudo agregar o no.

Aplicación Nº 28 (Listado BD)
Archivo: listado.php
método:GET
Recibe qué listado va a retornar(ej:usuarios,productos,ventas)
cada objeto o clase tendrán los métodos para responder a la petición
devolviendo una tabla de html <table>

Aplicación Nº 30 (AltaProducto BD)
Archivo: altaProducto.php
método:POST
Recibe los datos del producto (código de barra (6 cifras),nombre,tipo, stock, precio) por POST
Carga la fecha de creación y crear un objeto, se debe utilizar sus métodos para poder
verificar si es un producto existente, si ya existe el producto se le suma el stock, de lo contrario se agrega.
Retorna un:
“Ingresado” si es un producto nuevo
“Actualizado” si ya existía y se actualiza el stock.
“No se pudo hacer” si no se pudo hacer
Hacer los métodos necesarios en la clase

Aplicación Nº 29 (Login con BD)
Archivo: Login.php
método:POST
Recibe los datos del usuario (clave,mail) por POST,
crear un objeto y utilizar sus métodos para poder verificar si es un usuario registrado en la
base de datos,
Retorna un:
“Verificado” si el usuario existe y coincide la clave también.
“Error en los datos” si esta mal la clave.
“Usuario no registrado si no coincide el mail“
Hacer los métodos necesarios en la clase usuario.

Aplicación Nº 32(Modificacion BD)
Archivo: ModificacionUsuario.php
método:POST
Recibe los datos del usuario(nombre, clavenueva, clavevieja,mail )por POST ,
crear un objeto y utilizar sus métodos para poder hacer la modificación,
guardando los datos la base de datos
retorna si se pudo agregar o no.
Solo pueden cambiar la clave
*/

require_once "./usuario.php";

USE Productos_PDO\Usuario;

$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"] : NULL;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : NULL;
$mail = isset($_POST["mail"]) ? $_POST["mail"] : NULL;
$clave = isset($_POST["clave"]) ? $_POST["clave"] : NULL;
$localidad = isset($_POST["localidad"]) ? $_POST["localidad"] : NULL;

$id = isset($_POST["id"]) ? $_POST["id"] : NULL;

$claveNueva = isset($_POST["claveNueva"]) ? $_POST["claveNueva"] : NULL;


switch($accion)
{
    case 'registro':
        
        if(isset($accion) && isset($nombre) && isset($apellido) && isset($mail) && isset($clave) && isset($localidad))
        {
            $usuario = new Usuario($nombre, $apellido, $mail, $clave, $localidad);

            if($usuario->agregar())
            {
                echo "Usuario $nombre agregado!";
            }
            else 
            {
                echo "No se agregó.";
            }
        }

        break;
    
        case 'listado':

            echo Usuario::mostrarTodos();

            break;

        case 'login':
            
            $usuario = new Usuario($nombre, $apellido, $mail, $clave, $localidad);

            $usuarioBD = $usuario->logear();

            if(isset($usuarioBD))
            {
                echo "Logeado. ";
                echo $usuarioBD->toString();
            }
           
            break;
        
        case 'verificar':

            if(Usuario::verificarPorId($id))
            {
                echo "Verificado";
            }
            else 
            {
                echo "No encontrado";
            }

            break;

        case 'clave':

            $usuario = new Usuario($nombre, $apellido, $mail, $clave, $localidad);

            if($usuario->cambiarClave($claveNueva))
            {
                echo "Se actualizó";
            }
            else 
            {
                echo "No se actualizó clave";
            }

            break;
}



?>