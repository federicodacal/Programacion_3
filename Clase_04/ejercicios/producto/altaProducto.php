<?php 
/*
Aplicación Nº 25 ( AltaProducto)
Archivo: altaProducto.php
método:POST
Recibe los datos del producto(código de barra (6 cifras), nombre, tipo, stock, precio) por POST,
crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). 
Crear un objeto y utilizar sus métodos para poder verificar si es un producto existente, si ya existe
el producto se le suma el stock , de lo contrario se agrega al documento en un nuevo renglón
Retorna un:
“Ingresado” si es un producto nuevo
“Actualizado” si ya existía y se actualiza el stock.
“no se pudo hacer“si no se pudo hacer
Hacer los métodos necesarios en la clase
*/

require_once "./producto.php";

$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"] : NULL;

$codigoDeBarra = isset($_POST["codigoDeBarra"]) ? $_POST["codigoDeBarra"] : NULL;
$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : NULL;
$stock = isset($_POST["stock"]) ? $_POST["stock"] : NULL;
$precio = isset($_POST["precio"]) ? $_POST["precio"] : NULL;

if($accion == 'alta')
{
    if(isset($codigoDeBarra) && isset($tipo) && isset($stock) && isset($precio) && isset($nombre))
        {       
            $prod = new Producto($codigoDeBarra, $tipo, $stock, $precio, $nombre);

            if(Producto::agregar($prod))
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
}
else if($accion == 'listar')
{
    echo Producto::listar();
}
else 
{
    echo ":(";
}
?>