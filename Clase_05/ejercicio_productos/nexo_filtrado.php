<?php 
/*
Funciones de filtrado:
Se deben realizar la funciones que reciban datos por parámetros y puedan
ejecutar la consulta para responder a los siguientes requerimientos
A. Obtener los detalles completos de todos los usuarios y poder ordenarlos
alfabéticamente de forma ascendente o descendente.
B. Obtener los detalles completos de todos los productos y poder ordenarlos
alfabéticamente de forma ascendente y descendente.
C. Obtener todas las compras filtradas entre dos cantidades.
D. Obtener la cantidad total de todos los productos vendidos entre dos fechas.
E. Mostrar los primeros “N” números de productos que se han enviado.
F. Mostrar los nombres del usuario y los nombres de los productos de cada venta.
G. Indicar el monto (cantidad * precio) por cada una de las ventas.
H. Obtener la cantidad total de un producto (ejemplo:1003) vendido por un usuario
(ejemplo: 104).
I. Obtener todos los números de los productos vendidos por algún usuario filtrado por
localidad (ejemplo: ‘Avellaneda’).
J. Obtener los datos completos de los usuarios filtrando por letras en su nombre o
apellido.
*/

require_once "./producto.php";
require_once "./usuario.php";
require_once "./venta.php";

USE Productos_PDO\Producto;
use Productos_PDO\Usuario;
use Productos_PDO\Venta;

$op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : NULL;

// C
$min = isset($_GET["min"]) ? (int) $_GET["min"] : 0;
$max = isset($_GET["max"]) ? (int) $_GET["max"] : 0;

// D
$desde = isset($_GET["desde"]) ? $_GET["desde"] : NULL;
$hasta = isset($_GET["hasta"]) ? $_GET["hasta"] : NULL;

// E
$limit = isset($_GET["limit"]) ? (int) $_GET["limit"] : 0;

// H
$id_usuario = isset($_GET["id_usuario"]) ? (int) $_GET["id_usuario"] : 0;
$id_producto = isset($_GET["id_producto"]) ? (int) $_GET["id_producto"] : 0;

// I
$localidad = isset($_GET["localidad"]) ? $_GET["localidad"] : NULL;

// J
$cadena = isset($_GET["cadena"]) ? $_GET["cadena"] : NULL;

switch($op)
{
    case 'A':

        echo "ASC:<br>";
        $array_asc = Usuario::traerTodosAlfabeticamente();
        foreach($array_asc as $u)
        {
            echo $u->toString() . "<br>";
        }
        echo "<br>DESC:<br>";
        $array_desc = Usuario::traerTodosAlfabeticamente(false);
        foreach($array_desc as $u)
        {
            echo $u->toString() . "<br>";
        }
        break;

    case 'B':

        echo "ASC:<br>";
        $array_asc = Producto::traerTodosAlfabeticamente();
        foreach($array_asc as $p)
        {
            echo $p->toString() . "<br>";
        }
        echo "<br>DESC:<br>";
        $array_desc = Producto::traerTodosAlfabeticamente(false);
        foreach($array_desc as $p)
        {
            echo $p->toString() . "<br>";
        }
        break;

    case 'C':

        echo "Ventas entre $min y $max:<br>";
        $array = Venta::traerTodosPorRangoCantidad($min,$max);
        foreach($array as $v)
        {
            echo $v->toString() . "<br>";
        }

        break;

    case 'D':

        echo "Ventas entre $desde y $hasta:<br>";
        $array = Venta::traerTodosPorRangoFechas($desde,$hasta);
        foreach($array as $v)
        {
            echo $v->toString() . "<br>";
        }

        break;

    case 'E':

        echo "Ventas primeras $limit:<br>";
        $array = Venta::traerPrimerosN($limit);
        foreach($array as $v)
        {
            echo $v->toString() . "<br>";
        }

        break;

    case 'F':

        echo "Ventas:<br>";
        $array = Venta::traerTodosPorRangoCantidad(1,1000);
        foreach($array as $v)
        {
            echo $v->toString() . "<br>";
        }

        break;

    case 'G':

        echo "Ventas:<br>";
        $array = Venta::traerTodosConMonto();
        foreach($array as $v)
        {
            echo $v->toString() . " - ";
            echo "Monto: $" . $v->monto . "<br>";
        }

        break;

    case 'H':

        echo "Cantidad: " . Venta::obtenerCantidadProductoVendidoPorUsuario($id_usuario, $id_producto);

        break;

    case 'I':

        echo "Numeros productos vendidos por localidad $localidad:<br>";
        $array = Venta::obtenerIdsProductosVendidosPorLocalidadUsuario($localidad);
        foreach($array as $v)
        {
            echo $v . "<br>";
        }

        break;

    case 'J':

        echo "Usuarios filtrados por cadena '$cadena':<br>";
        $array = Usuario::traerTodosFiltradoPorCadena($cadena);
        foreach($array as $u)
        {
            echo $u->toString() . "<br>";
        }

        break;  

    default:
        echo 'params';
        break;
}

?>