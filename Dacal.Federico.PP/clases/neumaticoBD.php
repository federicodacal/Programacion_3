<?php 

namespace Dacal\Federico;

require_once "./clases/IParte1.php";
require_once "./clases/IParte2.php";
require_once "./clases/IParte3.php";
require_once "./clases/Neumatico.php";

use PDO;

class NeumaticoBD extends Neumatico implements IParte1, IParte2, IParte3
{
    protected int $id;
    protected string $pathFoto;

    public function __construct(string $marca = "", string $medidas = "", float $precio = 0, string $pathFoto = "", int $id = 0)
    {
        parent::__construct($marca, $medidas, $precio);
        $this->pathFoto = $pathFoto;
        $this->id = $id;
    }

    public function GetId()
    {
        return $this->id;
    }

    public function toJSON()
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
                    <th><img src='." . $n->pathFoto . "' alt='Sin Foto' width=50px height=50px></th>
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

    public function existe() : bool 
    {
        $rta = false;

        if(isset($params))
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    
            $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM neumaticos WHERE id = :id");
    
            $consulta->bindValue(":id", $this->id["id"], PDO::PARAM_INT);
    
            $consulta->execute();
    
            while($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $id = $fila["id"];
                $marca = $fila["marca"];
                $medidas = $fila["medidas"];
                $precio = $fila["precio"];
                $foto = $fila["foto"];
                $id = $fila["descripcion"];
                
                $neumatico = new NeumaticoBD($marca, $medidas, $precio, $foto, $id);

                if(isset($neumatico))
                {
                    $rta = true;
                }
            }
        }

        return $rta;
    }

}