<?php 

namespace PrimerParcial;

require_once "./clases/ICRUD.php";
require_once "./clases/Usuario.php";

use PDO;

class Empleado extends Usuario implements ICRUD 
{
    public string $foto;
    public float $sueldo;

    public function __construct(string $nombre = "", string $correo = "", string $clave = "", int $id_perfil = 0, string $perfil = "", int $id = 0, string $foto = "", int $sueldo = 0)
	{
		parent::__construct($nombre,$correo,$clave,$id_perfil,$perfil, $id);
		$this->foto = $foto;
        $this->sueldo = $sueldo;
	}

    public static function TraerTodos() : array 
    {
        $empleados = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT e.id, e.nombre, e.correo, e.clave, e.id_perfil, e.foto, e.sueldo, p.descripcion FROM empleados e INNER JOIN perfiles p ON e.id_perfil = p.id");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $id = $fila["id"];
            $nombre = $fila["nombre"];
            $correo = $fila["correo"];
            $clave = $fila["clave"];
            $id_perfil = $fila["id_perfil"];
            $perfil = $fila["descripcion"];
            $foto = $fila["foto"];
            $sueldo = $fila["sueldo"];

            $empleado = new Empleado($nombre, $correo, $clave, $id_perfil, $perfil, $id, $foto, $sueldo);
            array_push($empleados, $empleado);
        }

        return $empleados; 
    }

    public function Agregar(): bool
    {
        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $accesoDatos->retornarConsulta(
            "INSERT INTO empleados (nombre, correo, clave, id_perfil, foto, sueldo) "
            . "VALUES(:nombre, :correo, :clave, :id_perfil, :foto, :sueldo)"
        );

        $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(":correo", $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(":id_perfil", $this->id_perfil, PDO::PARAM_INT);
        $consulta->bindValue(":foto", $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(":sueldo", $this->sueldo, PDO::PARAM_INT);

        $retorno = $consulta->execute();
        
        return $retorno;

    }

    public function Modificar(): bool
    {
        $retorno = false;

        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $cadena = 
        "UPDATE empleados SET nombre = :nombre, correo = :correo, clave = :clave, 
        id_perfil = :id_perfil, foto = :foto, sueldo = :sueldo WHERE id = :id";
        $consulta = $accesoDatos->retornarConsulta($cadena);

        $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
        $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(":correo", $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(":id_perfil", $this->id_perfil, PDO::PARAM_INT);
        $consulta->bindValue(":foto", $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(":sueldo", $this->sueldo, PDO::PARAM_INT);

        $consulta->execute();

        $affectedRows = $consulta->rowCount();
        if($affectedRows == 1) 
        {
            $retorno = true;
        }

        return $retorno;
    }

    public static function Eliminar(int $id): bool
    {
        $retorno = false;
		$accesoDatos = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $accesoDatos->retornarConsulta("DELETE FROM empleados WHERE id = :id");
		$consulta->bindValue(":id", $id, PDO::PARAM_INT);
        $consulta->execute();
        
        $total_borrado = $consulta->rowCount();
        if($total_borrado == 1) {
            $retorno = true;
        }

		return $retorno;
    }

    public static function MostrarTablaBD() : string
    {
        $response = "";

        $empleados = Empleado::TraerTodos();

        if(isset($empleados)) //&& count($empleados) > 0)
        {
            $response = 
            "<table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Perfil</th>
                    <th>Descripcion</th>
                    <th>Sueldo</th>
                    <th>Path Foto</th>
                    <th>Foto</th>
                </tr>";
            
            foreach($empleados as $emp)
            {
                $response .=
                "<tr>
                    <th>{$emp->id}</th>
                    <th>{$emp->nombre}</th>
                    <th>{$emp->correo}</th>
                    <th>{$emp->id_perfil}</th>
                    <th>{$emp->perfil}</th>
                    <th>\${$emp->sueldo}</th>
                    <th>{$emp->foto}</th>
                    <th><img src='." . $emp->foto . "' alt='Nope' width=50px height=50px></th>
                    </tr>";
            }
            $response .= "</table>";
        }
        else 
        {
            $response = "No se obtuvieron empleados";
        }

        return $response;
    }
}

?>