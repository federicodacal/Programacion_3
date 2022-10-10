<?php 

namespace Productos_PDO;

require_once "./accesoDatos.php";

use PDO;

class Usuario 
{
    public string $nombre;
    public string $apellido;
    public string $mail;
    public string $clave;
    public int $id;
    public string $fechaRegistro;
    public string $localidad;

    public function __construct(string $nombre, string $apellido, string $mail, string $clave, string $localidad, string $fechaRegistro = "", int $id = 0)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->mail = $mail;
        $this->clave = $clave;
        $this->id = $id;
        $this->localidad = $localidad;
        $this->setFechaRegistro($fechaRegistro);
    }

    private function setFechaRegistro(string $fecha)
    {
        if($fecha == "")
        {
            $this->fechaRegistro = date("Y-m-d");
        }
        else 
        {
            $this->fechaRegistro = $fecha;
        }
    }

    public function toString() : string 
    {
        return "ID: {$this->id} Nombre: {$this->nombre} - Mail: {$this->mail} - Clave: {$this->clave} - Registro: {$this->fechaRegistro}";
    }

    public function agregar() : bool
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta(
        "INSERT INTO usuarios (nombre, apellido, clave, mail, fecha_de_registro, localidad) VALUES(:nombre, :apellido, :clave, :mail, :fecha_de_registro, :localidad)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_de_registro', $this->fechaRegistro, PDO::PARAM_STR);
        $consulta->bindValue(':localidad', $this->localidad, PDO::PARAM_STR);

        $rta = $consulta->execute();
        
        return $rta;
    }

    public static function traerTodos() : array 
    {
        $usuarios = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM usuarios");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $nombre = $fila["nombre"];
            $apellido = $fila["apellido"];
            $mail = $fila["mail"];
            $clave = $fila["clave"];
            $fecha_de_registro = $fila["fecha_de_registro"];
            $localidad = $fila["localidad"];
            $id = $fila["id"];

            $usuario = new Usuario($nombre, $apellido, $mail, $clave, $localidad, $fecha_de_registro, $id);

            array_push($usuarios, $usuario);
        }

        return $usuarios; 
    }
    
    public static function mostrarTodos() : string 
    {
        $response = "";

        $usuarios = Usuario::traerTodos();

        if(isset($usuarios)) //&& count($usuarios) > 0)
        {
            $response = 
            "<table border = 1>
                <caption>Listado de usuarios</caption>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Mail</th>
                    <th>Clave</th>
                    <th>Localidad</th>
                    <th>Registro</th>
                </tr>";
            
            foreach($usuarios as $u)
            {
                $response .=
                "<tr>
                    <td>{$u->id}</td>
                    <td>{$u->nombre}</td>
                    <td>{$u->apellido}</td>
                    <td>{$u->mail}</td>
                    <td>{$u->clave}</td>
                    <td>{$u->localidad}</td>
                    <td>{$u->fechaRegistro}</td>
                </tr>";
            }
            $response .= "</table>";
        }
        else 
        {
            $response = "No se obtuvieron usuarios";
        }

        return $response;
    }

    public function logear() : Usuario | null
    {
        $usuario = null;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM usuarios WHERE mail = :mail AND clave = :clave");

        $consulta->bindValue(":mail", $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $this->clave, PDO::PARAM_STR);

        $consulta->execute();

        if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
        {
            $nombre = $fila["nombre"];
            $apellido = $fila["apellido"];
            $mail = $fila["mail"];
            $clave = $fila["clave"];
            $fecha_de_registro = $fila["fecha_de_registro"];
            $localidad = $fila["localidad"];
            $id = $fila["id"];
            
            $usuario = new Usuario($nombre, $apellido, $mail, $clave, $localidad, $fecha_de_registro, $id);
        }
        
        return $usuario;
    }

    public static function verificarPorId(int $id) : bool
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM usuarios WHERE id = :id");

        $consulta->bindValue(":id", $id, PDO::PARAM_INT);

        $consulta->execute();

        if($consulta->fetch(PDO::FETCH_ASSOC)) 
        {
            $rta = true;
        }
        
        return $rta;
    }

    public function cambiarClave(string $claveNueva) : bool
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE usuarios SET clave = :claveNueva WHERE mail = :mail AND clave = :clave");
        
        $consulta->bindValue(":claveNueva", $claveNueva, PDO::PARAM_STR);
        $consulta->bindValue(":mail", $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $this->clave, PDO::PARAM_STR);

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