<?php 

/*
Aplicación Nº 26 (RealizarVenta)
Archivo: RealizarVenta.php
método:POST
Recibe los datos del producto (código de barra), del usuario (el id) y la cantidad de ítems, por POST.
Verificar que el usuario y el producto exista y tenga stock.
crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000).
carga los datos necesarios para guardar la venta en un nuevo renglón.
Retorna un :
“venta realizada”Se hizo una venta
“no se pudo hacer“si no se pudo hacer
Hacer los métodos necesaris en las clases
*/

require_once "./venta.php";
require_once "./producto.php";
require_once "../registro_json/usuario.php";

$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"] : NULL;

$codigoDeBarra = isset($_POST["codigoDeBarra"]) ? $_POST["codigoDeBarra"] : NULL;
$idUsuario = isset($_POST["idUsuario"]) ? (int) $_POST["idUsuario"] : 0;
$stock = isset($_POST["stock"]) ? $_POST["stock"] : 0;

if($accion == 'venta')
{
    if(isset($codigoDeBarra) && isset($idUsuario) && isset($stock))
    {
        $venta = new Venta($idUsuario, $codigoDeBarra, $stock);
    }
}
else 
{
    echo ":(";
}

?>