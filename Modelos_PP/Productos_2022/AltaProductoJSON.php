<?php 

require_once './clases/Producto.php';

use Dacal\Federico\Producto;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : NULL;
$origen = isset($_POST["origen"]) ? $_POST["origen"] : NULL;

if(isset($nombre) && isset($origen))
{
    $producto = new Producto($nombre, $origen);

    if(isset($producto))
    {
        echo $producto->guardarJSON('./archivos/productos.json');
    }
}
else 
{
    echo "Faltan parametros";
}

?>