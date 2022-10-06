<?php 

namespace Dacal\Federico;

require_once "./clases/IParte1.php";
require_once "./clases/IParte2.php";
require_once "./clases/IParte3.php";
require_once "./clases/IParte4.php";
require_once "./clases/Neumatico.php";

use PDO;

class NeumaticoBD extends Neumatico implements IParte1, IParte2, IParte3, IParte4
{
    protected int $id;
    protected string $pathFoto;

    public function __construct(string $marca = "", string $medidas = "", float $precio = 0, string $pathFoto = "", int $id = 0)
    {
        parent::__construct($marca, $medidas, $precio);
        $this->pathFoto = $pathFoto;
        $this->id = $id;
    }

    public function GetId() : int
    {
        return $this->id;
    }

    public function GetPathFoto() : string 
    {
        return $this->pathFoto;
    }

    public function toJSON() : string
    {
        return json_encode(array("id"=>$this->id,"marca"=>$this->marca, "medidas"=>$this->medidas, "precio"=>$this->precio, "pathFoto"=>$this->pathFoto));
    }

    public function agregar() : bool
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO neumaticos (marca, medidas, precio, foto)" . "VALUES(:marca, :medidas, :precio, :pathFoto)");
        
        $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
        $consulta->bindValue(':medidas', $this->medidas, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':pathFoto', $this->pathFoto, PDO::PARAM_STR);

        $rta = $consulta->execute();
        
        return $rta;
    }

    public static function traer() : array 
    {
        $neumaticos = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM neumaticos");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $marca = $fila["marca"];
            $medidas = $fila["medidas"];
            $precio = $fila["precio"];
            $foto = $fila["foto"];
            $id = $fila["id"];

            $neumatico = new NeumaticoBD($marca, $medidas, $precio, $foto, $id);

            array_push($neumaticos, $neumatico);
        }

        return $neumaticos; 
    }

    public static function mostrarTablaBD()
    {
        $response = "";

        $neumaticos = NeumaticoBD::traer();

        if(isset($neumaticos)) //&& count($neumaticos) > 0)
        {
            $response = 
            "<table>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Medidas</th>
                    <th>Precio</th>
                    <th>Path Foto</th>
                    <th>Foto</th>
                </tr>";
            
            foreach($neumaticos as $n)
            {
                $response .=
                "<tr>
                    <th>{$n->id}</th>
                    <th>{$n->marca}</th>
                    <th>{$n->medidas}</th>
                    <th>{$n->precio}</th>
                    <th>{$n->pathFoto}</th>
                    <th><img src='" . $n->pathFoto . "' alt='Sin Foto' width='50px' height='50px'></th>
                </tr>";
            }
            $response .= "</table>";
        }
        else 
        {
            $response = "No se obtuvieron neumaticos";
        }

        return $response;
    }

    public static function eliminar(int $id) : bool
    {
        $rta = false;

		$accesoDatos = AccesoDatos::dameUnObjetoAcceso();

		$consulta = $accesoDatos->retornarConsulta("DELETE FROM neumaticos WHERE id = :id");

		$consulta->bindValue(":id", $id, PDO::PARAM_INT);
        
        $ok = $consulta->execute();
        
        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0) 
        {
            $rta = true;
        }

		return $rta;
    }

    public function modificar() : bool
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE neumaticos SET marca = :marca, medidas = :medidas, precio = :precio, foto = :foto WHERE id = :id");
        
        $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
        $consulta->bindValue(":marca", $this->marca, PDO::PARAM_STR);
        $consulta->bindValue(":medidas", $this->medidas, PDO::PARAM_STR);
        $consulta->bindValue(":precio", $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(":foto", $this->pathFoto, PDO::PARAM_STR);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0)
        {
            $rta = true;
        }

        return $rta;
    }

    public function existe(array $neumaticos) : bool 
    {
        $rta = false;

        if(isset($neumaticos))
        {
            foreach($neumaticos as $n)
            {
                if($this->equals($n))
                {
                    $rta = true;
                    break;
                }
            }
        }

        return $rta; 
    }

    public function guardarEnArchivo() : bool 
    {
        $rta = false;

        $path = './archivos/neumaticosbd_borrados.txt';

        $extensionFoto = pathinfo($this->pathFoto, PATHINFO_EXTENSION);

        $nuevoPathFoto = './neumaticosBorrados/' . $this->id . "." . $this->marca . "." . "borrado" . "." . date("His") . "." . $extensionFoto;
                
        if(rename($this->pathFoto, $nuevoPathFoto))
        {
            $this->pathFoto = $nuevoPathFoto;
        }

        $ar = fopen($path, "a");

        $texto = "{$this->id},{$this->marca},{$this->medidas},{$this->precio},{$this->pathFoto}\r\n";

        $cant = fwrite($ar, $texto);

        if($cant > 0)
        {
            $rta = true;
        }

        fclose($ar);

        return $rta;
    }

    public static function traerPorId(int $id) : NeumaticoBD | null
    {
        $neumatico = null;

        if(isset($id))
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    
            $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM neumaticos WHERE id = :id");
    
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    
            $consulta->execute();
    
            if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
            {
                $id = $fila["id"];
                $marca = $fila["marca"];
                $medidas = $fila["medidas"];
                $precio = $fila["precio"];
                $pathFoto = $fila["foto"];
                
                $neumatico = new NeumaticoBD($marca, $medidas, $precio, $pathFoto, $id);
            }
        }

        return $neumatico;
    }

    public static function traerBorrados() : array
    {
        $neumaticos = array();

        $file = './archivos/neumaticosbd_borrados.txt';

        if(file_exists($file))
        {
            $ar = fopen($file, "r");

            while(!feof($ar))
            {
                $registro = fgets($ar);
                $arrayRegistro = explode(",", $registro);
    
                if($arrayRegistro[0] != "")
                {
                    $id = $arrayRegistro[0];
                    $marca = $arrayRegistro[1];
                    $medidas = $arrayRegistro[2];
                    $precio = $arrayRegistro[3];
                    $pathFoto = $arrayRegistro[4];
    
                    $neumatico = new NeumaticoBD($marca,$medidas,$precio,$pathFoto,$id);
                    array_push($neumaticos, $neumatico);
                }
            }
            fclose($ar);
        }
        return $neumaticos;
    }

    public static function mostrarTablaBorrados() : string 
    {
        $response = "No hay neumaticos";

        $neumaticosBorrados = NeumaticoBD::traerBorrados();

        if(isset($neumaticosBorrados))
        {
            $response = 
            "<table>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Medidas</th>
                    <th>Precio</th>
                    <th>Path Foto</th>
                    <th>Foto</th>
                </tr>";
            
            foreach($neumaticosBorrados as $n)
            {
                $response .=
                "<tr>
                    <th>{$n->id}</th>
                    <th>{$n->marca}</th>
                    <th>{$n->medidas}</th>
                    <th>{$n->precio}</th>
                    <th>{$n->pathFoto}</th>
                    <th><img src='" . $n->pathFoto . "' alt='Sin Foto' width=50px height=50px></th>
                </tr>";
            }
            $response .= "</table>";
        }

        return $response;
    }

    public static function mostrarBorradosJSON() : string 
    {
        $mensaje = "";
        $neumaticosBorrados = NeumaticoBD::traerJSON("./archivos/neumaticos_eliminados.json");

        if(isset($neumaticosBorrados))
        {
            foreach($neumaticosBorrados as $n)
            {
                $mensaje .= $n->toJSON();
                $mensaje .= "\n";
            }
        }

        return $mensaje;
    }

    public static function mostrarFotosModificados() : string 
    {
        $mensaje = "";
        $files = array();
        foreach (scandir('./neumaticosModificados') as $file) 
        {
            if ($file !== '.' && $file !== '..') 
            {
                $files[] = $file;
                $path = './neumaticosModificados/';
                $path .= pathinfo($file, PATHINFO_FILENAME) . "." . pathinfo($file, PATHINFO_EXTENSION);
                
                echo "path: " . $path . "<br>";

                $mensaje .= "<img src='" . $path . "' alt='sin foto' width='50px' height='50px'>";
            }
        }
        return $mensaje;
    }

}