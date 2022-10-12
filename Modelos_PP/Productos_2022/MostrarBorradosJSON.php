<?php 

require_once './clases/AccesoDatos.php';
require_once './clases/Producto.php';
require_once './clases/ProductoEnvasado.php';

use Dacal\Federico\Producto;
use Dacal\Federico\ProductoEnvasado;
use Dacal\Federico\AccesoDatos;

echo ProductoEnvasado::mostrarBorradosJSON();

?>