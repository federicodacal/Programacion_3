<?php 
/*
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

Aplicación Nº 31 (RealizarVenta BD)
Archivo: RealizarVenta.php
método:POST
Recibe los datos del producto (código de barra), del usuario (el id ) y la cantidad de ítems, por
POST .
Verificar que el usuario y el producto exista y tenga stock.
Retorna un :
“venta realizada”Se hizo una venta
“no se pudo hacer”si no se pudo hacer
Hacer los métodos necesarios en las clases

Aplicación Nº 33 ( ModificacionProducto BD)
Archivo: modificacionproducto.php
método:POST
Recibe los datos del producto (código de barra, nombre, tipo, stock, precio) por POST,
crear un objeto y utilizar sus métodos para poder verificar si es un producto existente,
si ya existe el producto el stock se sobrescribe y se cambian todos los datos excepto:
el código de barra.
Retorna un :
“Actualizado” si ya existía y se actualiza
“no se pudo hacer“si no se pudo hacer
Hacer los métodos necesarios en la clase
*/

require_once "./producto.php";

USE Productos_PDO\Producto;

$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"] : NULL;

$codigoDeBarra = isset($_POST["codigoDeBarra"]) ? $_POST["codigoDeBarra"] : NULL;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : NULL;
$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$stock = isset($_POST["stock"]) ? $_POST["stock"] : NULL;
$precio = isset($_POST["precio"]) ? $_POST["precio"] : NULL;
$fecha_de_creacion = isset($_POST["fecha_de_creacion"]) ? $_POST["fecha_de_creacion"] : NULL;
$fecha_de_modificacion = isset($_POST["fecha_de_modificacion"]) ? $_POST["fecha_de_modificacion"] : NULL;

$idUsuario = isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : NULL;

if($accion == 'altaProducto')
{
    $producto = new Producto($codigoDeBarra, $tipo, $nombre, $stock, $precio, $fecha_de_creacion, $fecha_de_modificacion);

    if(Producto::agregar($producto))
    {
        echo "OK";
    }
}
else if($accion == 'verificar')
{
    $response = Producto::verificarPorCodigo($codigoDeBarra);

    $obj = json_decode($response, true);

    echo $obj["rta"];
}
else if($accion == 'realizarVenta')
{
    $response = Producto::realizarVenta($idUsuario, $codigoDeBarra, $stock);

    $obj = json_decode($response, true);

    if($obj["rta"])
    {
        echo "Venta realizada. ";
        echo $obj["mensaje"];
    }
    else 
    {
        echo "No se pudo. ";
        echo $obj["mensaje"];
    }
}
else if($accion == 'modificar')
{
    $producto = new Producto($codigoDeBarra, $tipo, $nombre, $stock, $precio, $fecha_de_creacion, $fecha_de_modificacion);

    if($producto->modificar())
    {
        echo "Modificado OK";
    }
}

?>