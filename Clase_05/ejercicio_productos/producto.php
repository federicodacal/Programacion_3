<?php 

namespace Productos_PDO;

require_once './accesoDatos.php';
require_once './usuario.php';

use PDO;
use Productos_PDO\Usuario;

class Producto 
{
    public string $codigoDeBarra;
    public string $tipo;
    public string $nombre;
    public int $stock;
    public float $precio;
    public int $id;
    public string $fecha_de_creacion;
    public string $fecha_de_modificacion;

    public function __construct(string $codigoDeBarra, string $tipo, string $nombre, int $stock, float $precio, string $fecha_de_creacion, string $fecha_de_modificacion, int $id = 0)
    {
        $this->codigoDeBarra = $codigoDeBarra;
        $this->tipo = $tipo;
        $this->stock = $stock;
        $this->precio = $precio;
        $this->nombre = $nombre;
        $this->fecha_de_creacion = $fecha_de_creacion;
        $this->fecha_de_modificacion = $fecha_de_modificacion;
        $this->$id = $id;
    }

    public function toString() : string 
    {
        return "Tipo: {$this->tipo}, Nombre: {$this->nombre}, Codigo: {$this->codigoDeBarra}, Stock: {$this->stock}, Precio: \${$this->precio}, ID: {$this->id}";
    }

    public function equals(Producto $p) : bool 
    {
        return $this->tipo === $p->tipo && $this->nombre === $p->nombre;
    }

    public static function agregar(Producto $prod) : bool
    {
        $rta = false;

        $existe = false;

        $productos = Producto::traerTodos();

        if(isset($productos))
        {
            foreach($productos as $p)
            {
                if($prod->equals($p))
                {
                    $existe = true;
                    $prod->stock += $p->stock;
                    break;
                }
            }
        }

        if($existe)
        {
            $rta = $prod->modificarStock();
        }
        else 
        {
            $rta = $prod->alta();
        }

        return $rta;
    }

    public function alta() : bool 
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta(
        "INSERT INTO productos (codigo_de_barra, nombre, tipo, stock, precio, fecha_de_creacion, fecha_de_modificacion) VALUES(:codigo_de_barra, :nombre, :tipo, :stock, :precio, :fecha_de_creacion, :fecha_de_modificacion)");
        
        $consulta->bindValue(':codigo_de_barra', $this->codigoDeBarra, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_de_creacion', $this->fecha_de_creacion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_de_modificacion', $this->fecha_de_modificacion, PDO::PARAM_STR);

        $rta = $consulta->execute();
        
        return $rta;
    }

    public function modificarStock() : bool 
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE productos SET stock = :stock WHERE tipo = :tipo AND nombre = :nombre");
        
        $consulta->bindValue(":stock", $this->stock, PDO::PARAM_STR);
        $consulta->bindValue(":tipo", $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0)
        {
            $rta = true;
        }

        return $rta;
    }

    public static function traerTodos() : array 
    {
        $productos = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM productos");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $codigoDeBarra = $fila["codigo_de_barra"];
            $nombre = $fila["nombre"];
            $tipo = $fila["tipo"];
            $stock = $fila["stock"];
            $precio = $fila["precio"];
            $fecha_de_creacion = $fila["fecha_de_creacion"];
            $fecha_de_modificacion = $fila["fecha_de_modificacion"];
            $id = $fila["id"];

            $producto = new Producto($codigoDeBarra, $tipo, $nombre, $stock, $precio, $fecha_de_creacion, $fecha_de_modificacion, $id);

            array_push($productos, $producto);
        }

        return $productos; 
    }

    public static function verificarPorCodigo(string $codigoDeBarra) : string
    {
        $exito = false;
        $rta = "Hubo un problema";
        $stock = 0;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM productos WHERE codigo_de_barra = :codigo_de_barra");

        $consulta->bindValue(":codigo_de_barra", $codigoDeBarra, PDO::PARAM_INT);

        $consulta->execute();

        if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
        {
            $stock = $fila["stock"];
            if($stock > 0)
            {
                $rta = "Encontrado y queda stock: $stock unidades";
                $exito = true;
            }
            else 
            {
                $rta = "Encontrado pero no queda stock";
            }
        }
        else 
        {
            $rta = "No se encontro";
        }
        
        return json_encode(array("exito"=>$exito,"rta"=>$rta,"stock"=>$stock));
    }

    public static function realizarVenta(int $idUsuario, string $codigoDeBarra, int $cantidad) : bool 
    {
        $rta = false;

        $okUsuario = Usuario::verificarPorId($idUsuario);

        $okProducto = json_decode(Producto::verificarPorCodigo($codigoDeBarra), true);

        if($okUsuario && $okProducto["exito"])
        {
            if($okProducto["stock"] >= $cantidad)
            {
                $rta = true;
            }
        }

        return $rta;
    }

    public function modificar() : bool 
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE productos SET nombre = :nombre, tipo = :tipo, stock = :stock, precio = :precio, fecha_de_creacion = :fecha_de_creacion, fecha_de_modificacion = :fecha_de_modificacion WHERE codigo_de_barra = :codigo_de_barra");
        
        $consulta->bindValue(":codigo_de_barra", $this->codigoDeBarra, PDO::PARAM_STR);

        $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(":tipo", $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(":stock", $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(":precio", $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(":fecha_de_creacion", $this->fecha_de_creacion, PDO::PARAM_STR);
        $consulta->bindValue(":fecha_de_modificacion", $this->fecha_de_modificacion, PDO::PARAM_STR);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0)
        {
            $rta = true;
        }

        return $rta;
    }
}

?>