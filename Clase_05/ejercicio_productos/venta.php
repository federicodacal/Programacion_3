<?php 

namespace Productos_PDO;

require_once './accesoDatos.php';
require_once './usuario.php';
require_once './producto.php';

use PDO;
use Productos_PDO\Usuario;
use Productos_PDO\Producto;

class Venta 
{
    public int $id;
    public int $idProducto;
    public int $idUsuario;
    public int $cantidad;
    public float $monto;
    public string $apellidoUsuario;
    public string $nombreProducto;
    public string $fechaDeVenta;

    public function __construct(int $idProducto, int $idUsuario, int $cantidad, string $apellidoUsuario, string $nombreProducto, string $fechaDeVenta = "", int $id = 0)
    {
        $this->idProducto = $idProducto;
        $this->idUsuario = $idUsuario;
        $this->cantidad = $cantidad;
        $this->apellidoUsuario = $apellidoUsuario;
        $this->nombreProducto = $nombreProducto;
        $this->id = $id;
        $this->setFechaVenta($fechaDeVenta);
    }

    private function setFechaVenta(string $fecha) : void
    {
        if($fecha == "")
        {
            $this->fechaDeVenta = date("Y-m-d");
        }
        else 
        {
            $this->fechaDeVenta = $fecha;
        }
    }

    public function toString() : string 
    {
        return "ID: {$this->id} - ID_Producto: {$this->idProducto} - Producto: {$this->nombreProducto} - ID_Usuario: {$this->idUsuario} - Usuario: {$this->apellidoUsuario} - Cantidad: {$this->cantidad} - Fecha: {$this->fechaDeVenta}";
    }

    public function cargarVenta() : bool
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta(
        "INSERT INTO ventas (id_producto, id_usuario, cantidad, fecha_de_venta) VALUES(:id_producto, :id_usuario, :cantidad, :fecha_de_venta)");
        
        $consulta->bindValue(':id_producto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':id_usuario', $this->idUsuario, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_de_venta', $this->fechaDeVenta, PDO::PARAM_STR);

        $rta = $consulta->execute();
        
        return $rta;
    }

    // C. Obtener todas las compras filtradas entre dos cantidades.
    public static function traerTodosPorRangoCantidad(int $min, int $max) : array
    {
        $ventas = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT v.id, v.id_producto, v.id_usuario, v.cantidad, v.fecha_de_venta, p.nombre, u.apellido FROM ventas v JOIN productos p ON v.id_producto = p.id JOIN usuarios u ON v.id_usuario = u.id WHERE v.cantidad >= :min AND v.cantidad <= :max");

        $consulta->bindValue(':min', $min, PDO::PARAM_INT);
        $consulta->bindValue(':max', $max, PDO::PARAM_INT);        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $id_producto = $fila["id_producto"];
            $id_usuario = $fila["id_usuario"];
            $cantidad = $fila["cantidad"];
            $fecha_de_venta = $fila["fecha_de_venta"];
            $apellido_usuario = $fila["apellido"];
            $nombre_producto = $fila["nombre"];
            $id = $fila["id"];

            $venta = new Venta($id_producto, $id_usuario, $cantidad, $apellido_usuario, $nombre_producto, $fecha_de_venta, $id);

            array_push($ventas, $venta);
        }

        return $ventas;
    }

    // D. Obtener la cantidad total de todos los productos vendidos entre dos fechas.
    public static function traerTodosPorRangoFechas(string $desde, string $hasta) : array
    {
        $ventas = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT v.id, v.id_producto, v.id_usuario, v.cantidad, v.fecha_de_venta, p.nombre, u.apellido FROM ventas v JOIN productos p ON v.id_producto = p.id JOIN usuarios u ON v.id_usuario = u.id WHERE v.fecha_de_venta >= :desde AND v.fecha_de_venta <= :hasta");

        $consulta->bindValue(':desde', $desde, PDO::PARAM_STR);
        $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $id_producto = $fila["id_producto"];
            $id_usuario = $fila["id_usuario"];
            $cantidad = $fila["cantidad"];
            $fecha_de_venta = $fila["fecha_de_venta"];
            $apellido_usuario = $fila["apellido"];
            $nombre_producto = $fila["nombre"];
            $id = $fila["id"];

            $venta = new Venta($id_producto, $id_usuario, $cantidad, $apellido_usuario, $nombre_producto, $fecha_de_venta, $id);

            array_push($ventas, $venta);
        }

        return $ventas;
    }

    // E. Mostrar los primeros “n” números de productos que se han enviado.
    public static function traerPrimerosN(int $n) : array 
    {
        $ventas = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT v.id, v.id_producto, v.id_usuario, v.cantidad, v.fecha_de_venta, p.nombre, u.apellido FROM ventas v JOIN productos p ON v.id_producto = p.id JOIN usuarios u ON v.id_usuario = u.id ORDER BY v.fecha_de_venta LIMIT :n");

        $consulta->bindValue(':n', $n, PDO::PARAM_INT);   
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $id_producto = $fila["id_producto"];
            $id_usuario = $fila["id_usuario"];
            $cantidad = $fila["cantidad"];
            $fecha_de_venta = $fila["fecha_de_venta"];
            $apellido_usuario = $fila["apellido"];
            $nombre_producto = $fila["nombre"];
            $id = $fila["id"];

            $venta = new Venta($id_producto, $id_usuario, $cantidad, $apellido_usuario, $nombre_producto, $fecha_de_venta, $id);

            array_push($ventas, $venta);
        }

        return $ventas;
    }
    
    // G. Indicar el monto (cantidad * precio) por cada una de las ventas
    public static function traerTodosConMonto() : array 
    {
        $ventas = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT v.cantidad * p.precio as monto, v.id, v.id_producto, v.id_usuario, v.cantidad, v.fecha_de_venta, p.nombre, u.apellido FROM ventas v JOIN productos p ON v.id_producto = p.id JOIN usuarios u ON v.id_usuario = u.id");     
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $id_producto = $fila["id_producto"];
            $id_usuario = $fila["id_usuario"];
            $cantidad = $fila["cantidad"];
            $fecha_de_venta = $fila["fecha_de_venta"];
            $apellido_usuario = $fila["apellido"];
            $nombre_producto = $fila["nombre"];
            $id = $fila["id"];
            $monto = $fila["monto"];

            $venta = new Venta($id_producto, $id_usuario, $cantidad, $apellido_usuario, $nombre_producto, $fecha_de_venta, $id);
            $venta->monto = $monto;

            array_push($ventas, $venta);
        }

        return $ventas;
    }

    // H. Obtener la cantidad total de un producto (ejemplo:1003) vendido por un usuario (ejemplo: 104).
    public static function obtenerCantidadProductoVendidoPorUsuario(int $id_usuario, int $id_producto) : int 
    {
        $total = 0;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT SUM(cantidad) as cantidad_total FROM ventas WHERE id_producto = :id_producto AND id_usuario = :id_usuario");

        $consulta->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);        
        
        $consulta->execute();
                
        if($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $total = (int)$fila["cantidad_total"];
        }

        return $total;
    }

    // I. Obtener todos los números de los productos vendidos por algún usuario filtrado por localidad (ejemplo: ‘Avellaneda’).
    public static function obtenerIdsProductosVendidosPorLocalidadUsuario(string $localidad) : array
    {
        $ventas = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT p.id as id_p, u.id as id_u, u.nombre FROM ventas v 
        JOIN productos p ON id_producto = p.id 
        JOIN usuarios u ON id_usuario = u.id 
        WHERE u.localidad = :localidad");

        $consulta->bindValue(':localidad', $localidad, PDO::PARAM_STR);
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $id_producto = $fila["id_p"];

            array_push($ventas, $id_producto);
        }

        return $ventas;
    }
}

?>