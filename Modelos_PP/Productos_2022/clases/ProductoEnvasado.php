<?php 

namespace Dacal\Federico;

require_once "./clases/IParte1.php";
require_once "./clases/IParte2.php";
require_once "./clases/IParte3.php";
require_once "./clases/IParte4.php";

use PDO;

class ProductoEnvasado extends Producto implements IParte1, IParte2, IParte3, IParte4 
{
    public int $id;
    public string $codigoBarra;
    public float $precio;
    public string $pathFoto;

    public function __construct(string $nombre="", string $origen="", int $id=0, string $codigoBarra="", float $precio=0, string $pathFoto="")
    {
        parent::__construct($nombre,$origen);
        $this->id = $id;
        $this->codigoBarra = $codigoBarra;
        $this->precio = $precio;
        $this->pathFoto = $pathFoto;
    }

    public function toJson() : string
    {
        return json_encode(array("nombre"=>$this->nombre,"origen"=>$this->origen,"id"=>$this->id,"codigoBarra"=>$this->codigoBarra,"precio"=>$this->precio,"pathFoto"=>$this->pathFoto));
    }

    public function agregar() : bool
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO productos (nombre, origen, codigo_barra, precio, path_foto)" . "VALUES(:nombre, :origen, :codigoBarra, :precio, :pathFoto)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':origen', $this->origen, PDO::PARAM_STR);
        $consulta->bindValue(':codigoBarra', $this->codigoBarra, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':pathFoto', $this->pathFoto, PDO::PARAM_STR);

        $rta = $consulta->execute();
        
        return $rta;
    }

    public static function traer() : array 
    {
        $productos = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM productos");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $nombre = $fila["nombre"];
            $origen = $fila["origen"];
            $id = $fila["id"];
            $codigoBarra = $fila["codigo_barra"];
            $precio = $fila["precio"];
            $pathFoto = $fila["path_foto"];

            $producto = new ProductoEnvasado($nombre, $origen, $id, $codigoBarra, $precio, $pathFoto);

            array_push($productos, $producto);
        }

        return $productos; 
    }

    public static function MostrarTablaBD() : string 
    {
        $response = "";

        $productos = ProductoEnvasado::traer();

        if(isset($productos)) //&& count($productos) > 0)
        {
            $response = 
            "<table border = 1>
                <caption>Listado de productos</caption>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Origen</th>
                    <th>Codigo Barra</th>
                    <th>Precio</th>
                    <th>Path Foto</th>
                    <th>Foto</th>
                </tr>";
            
            foreach($productos as $p)
            {
                $response .=
                "<tr>
                    <td>{$p->id}</td>
                    <td>{$p->nombre}</td>
                    <td>{$p->origen}</td>
                    <td>{$p->codigoBarra}</td>
                    <td>{$p->precio}</td>
                    <td>{$p->pathFoto}</td>
                    <td><img src='" . $p->pathFoto . "' alt='Nope' width='50px' height='50px'></td>
                </tr>";
            }
            $response .= "</table>";
        }
        else 
        {
            $response = "No se obtuvieron productos";
        }

        return $response;
    }

    public function modificar() : bool 
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE productos SET nombre = :nombre, origen = :origen, codigo_barra = :codigo_barra, precio = :precio, path_foto = :path_foto WHERE id = :id");
        
        $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
        $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(":origen", $this->origen, PDO::PARAM_STR);
        $consulta->bindValue(":codigo_barra", $this->codigoBarra, PDO::PARAM_STR);
        $consulta->bindValue(":precio", $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(":path_foto", $this->pathFoto, PDO::PARAM_STR);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0)
        {
            $rta = true;
        }

        return $rta;
    }

    public static function eliminar(int $id) : bool 
    {
        $rta = false;

		$accesoDatos = AccesoDatos::dameUnObjetoAcceso();

		$consulta = $accesoDatos->retornarConsulta("DELETE FROM productos WHERE id = :id");

		$consulta->bindValue(":id", $id, PDO::PARAM_INT);
        
        $ok = $consulta->execute();
        
        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0) 
        {
            $rta = true;
        }

		return $rta;
    }

    public function existe(array $productos) : bool
    {
        $rta = false;

        if(isset($productos))
        {
            foreach($productos as $p)
            {
                if($this->equals($p))
                {
                    $rta = true;
                    break;
                }
            }
        }

        return $rta;
    }

    public function guardarEnArchivo() : string 
    {
        $exito = false;
        $mensaje = "Hubo un problema";

        $path = './archivos/productos_envasados_borrados.txt';

        $extensionFoto = pathinfo($this->pathFoto, PATHINFO_EXTENSION);

        $nuevoPathFoto = './productosBorrados/' . $this->id . "." . $this->nombre . "." . "borrado" . "." . date("His") . "." . $extensionFoto;
                
        if(rename($this->pathFoto, $nuevoPathFoto))
        {
            $this->pathFoto = $nuevoPathFoto;
            $mensaje = "Mover foto OK";
        }
        else 
        {
            $mensaje .= ". Problema moviendo la foto";
        }

        $ar = fopen($path, "a");

        $texto = "{$this->id},{$this->nombre},{$this->origen},{$this->codigoBarra},{$this->precio},{$this->pathFoto}\r\n";

        $cant = fwrite($ar, $texto);

        if($cant > 0)
        {
            $exito = true;
            $mensaje .= ". Archivo txt guardado"; 
        }
        else 
        {
            $mensaje .= ". Problema con archivo txt";
        }

        fclose($ar);

        return json_encode(array("exito"=>$exito,"mensaje"=>$mensaje));
    }

    public static function traerPorId(int $id) : ProductoEnvasado | null
    {
        $producto = null;

        if(isset($id))
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    
            $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM productos WHERE id = :id");
    
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    
            $consulta->execute();
    
            if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
            {
                $id = $fila["id"];
                $nombre = $fila["nombre"];
                $origen = $fila["origen"];
                $codigoBarra = $fila["codigo_barra"];
                $precio = $fila["precio"];
                $pathFoto = $fila["path_foto"];
                
                $producto = new ProductoEnvasado($nombre, $origen, $id, $codigoBarra, $precio, $pathFoto);
            }
        }

        return $producto;
    }

    public static function mostrarBorradosJSON() : string 
    {
        $productos = array();

        $path = './archivos/productos_eliminados.json';

        if(file_exists($path))
        {
            $ar = fopen($path, "r");

            $filesize = filesize($path);

            if($filesize > 0)
            {
                $json = fread($ar, $filesize);

                $productosJson = json_decode($json, true);

                if(isset($productosJson))
                {
                    foreach($productosJson as $p)
                    {
                        array_push($productos, new ProductoEnvasado($p["nombre"], $p["origen"],$p["id"]));
                    } 
                }
            }
            fclose($ar);
        }

        return json_encode($productos);
    }

    public static function mostrarFotosModificados() : string 
    {
        $files = array();
        
        $mensaje = "<table border = 1>";
        $mensaje .= "<caption>Fotos productos modificados</caption>";

        foreach (scandir('./productosModificados') as $file) 
        {
            if ($file !== '.' && $file !== '..') 
            {
                $files[] = $file;
                $path = './productosModificados/';
                $path .= pathinfo($file, PATHINFO_FILENAME) . "." . pathinfo($file, PATHINFO_EXTENSION);

                $mensaje .= "<tr>";
                $mensaje .= "<td>" . $path . "</td>";
                $mensaje .= "<td><img src='" . $path . "' alt='Nope' width='50px' height='50px'></td>";
                $mensaje .= "</tr>"; 
            }
        }
        return $mensaje;
    }

}

?>