<?php 

namespace PrimerParcial;

require_once "./clases/IBM.php";

use PDO;

class Usuario implements IBM
{
    public int $id;
    public string $nombre;
    public string $correo;
    public string $clave;
    public int $id_perfil;
    public string $perfil;

    public function __construct(string $nombre = "", string $correo = "", string $clave = "", int $id_perfil = 0, string $perfil = "", int $id = 0)
    {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->id_perfil = $id_perfil;
        $this->perfil = $perfil;
        $this->id = $id;
    }

    public function ToJson() 
    {
        return json_encode(array("nombre"=>$this->nombre, "correo"=>$this->correo, "clave"=>$this->clave));
    }

    public function GuardarEnArchivo() : string
    {
        $exito = false;
        $mensaje = "No guardado";

        $lista = Usuario::TraerTodosJSON();

        $ar = fopen("./archivos/usuarios.json", "w");

        if(isset($lista))
        {            
            array_push($lista, $this);
        }
        else 
        {
            $lista = array();
        }

        $json = json_encode($lista);

        $cant = fwrite($ar, $json);

        if($cant > 0)
        {
            $exito = true;
            $mensaje = "Usuario guardado con éxito";
        }

        fclose($ar);

        return json_encode(array("exito"=>$exito, "mensaje"=>$mensaje));
    }

    public static function UsuariosToJSON() : string 
    {
        $response = "";

        $usuarios = Usuario::TraerTodosJSON();

        if(isset($usuarios))
        {
            if(count($usuarios) > 0)
            {
                $response = "[";
                for($i = 0; $i < count($usuarios); $i++)
                {
                    $response .= $usuarios[$i]->ToJson();

                    if($i < count($usuarios)-1)
                    {
                        $response .= ",\r\n";
                    }
                }
                $response .= "]";
            }
            else 
            {
                $response = "No hay usuarios cargados";
            }
        }
        else 
        {
            $response = "No cargo la lista";
        }

        return $response;
    }

    public static function TraerTodosJSON() : array
    {
        $usuarios = array();

        $filePath = "./archivos/usuarios.json";

        if(file_exists($filePath))
        {
            $ar = fopen($filePath, "r");

            $filesize = filesize($filePath);
    
            if($filesize > 0)
            {
                $json = fread($ar, $filesize);
    
                $usuariosJson = json_decode($json, true);
    
                if(isset($usuariosJson))
                {
                    foreach($usuariosJson as $usuario)
                    {
                        array_push($usuarios, new Usuario($usuario["nombre"], $usuario["correo"], $usuario["clave"]));
                    }
            
                }
            }
    
            fclose($ar);
        }
        return $usuarios;
    }

    public function Agregar() : bool 
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT INTO usuarios (nombre, correo, clave, id_perfil)" . "VALUES(:nombre, :correo, :clave, :id_perfil)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':correo', $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);

        $rta = $consulta->execute();
        
        return $rta;
    }

    public static function TraerTodos() : array 
    {
        $usuarios = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT u.id, u.nombre, u.correo, u.clave, u.id_perfil, p.descripcion FROM usuarios u INNER JOIN perfiles p ON u.id_perfil = p.id");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $id = $fila["id"];
            $nombre = $fila["nombre"];
            $correo = $fila["correo"];
            $clave = $fila["clave"];
            $id_perfil = $fila["id_perfil"];
            $descripcion = $fila["descripcion"];

            $usuario = new Usuario($nombre, $correo, $clave, $id_perfil, $descripcion, $id);
            array_push($usuarios, $usuario);
        }

        return $usuarios; 
    }

    public static function TraerUno($params) : Usuario | null 
    {
        $usuario = null;

        if(isset($params))
        {
            $obj = json_decode($params, true);

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    
            $consulta = $objetoAccesoDato->retornarConsulta("SELECT u.id, u.nombre, u.correo, u.clave, u.id_perfil, p.descripcion FROM usuarios u INNER JOIN perfiles p ON u.id_perfil = p.id WHERE u.correo = :correo AND u.clave = :clave");
    
            $consulta->bindValue(":correo", $obj["correo"], PDO::PARAM_STR);
            $consulta->bindValue(":clave", $obj["clave"], PDO::PARAM_STR);
    
            $consulta->execute();
    
            if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
            {
                $id = $fila["id"];
                $nombre = $fila["nombre"];
                $correo = $fila["correo"];
                $clave = $fila["clave"];
                $id_perfil = $fila["id_perfil"];
                $perfil = $fila["descripcion"];
                
                $usuario = new Usuario($nombre, $correo, $clave, $id_perfil, $perfil, $id);
            }
        }

        return $usuario;
    }

    public function Modificar() : bool
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE usuarios SET nombre = :nombre, correo = :correo, clave = :clave, id_perfil = :id_perfil WHERE id = :id");
        
        $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
        $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(":correo", $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(":id_perfil", $this->id_perfil, PDO::PARAM_INT);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0)
        {
            $rta = true;
        }

        return $rta;
    }

    public static function Eliminar(int $id) : bool 
    {
        $rta = false;

		$accesoDatos = AccesoDatos::dameUnObjetoAcceso();

		$consulta = $accesoDatos->retornarConsulta("DELETE FROM usuarios WHERE id = :id");
        
		$consulta->bindValue(":id", $id, PDO::PARAM_INT);
        
        $ok = $consulta->execute();
        
        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0) 
        {
            $rta = true;
        }

		return $rta;
    }

    public static function MostrarTablaBD() : string
    {
        $response = "";

        $usuarios = Usuario::TraerTodos();

        if(isset($usuarios)) //&& count($usuarios) > 0)
        {
            $response = 
            "<table border = 1>
                <caption>Listado de usuarios</caption>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Perfil</th>
                    <th>Descripcion</th>
                </tr>";
            
            foreach($usuarios as $usuario)
            {
                $response .=
                "<tr>
                    <td>{$usuario->id}</td>
                    <td>{$usuario->nombre}</td>
                    <td>{$usuario->correo}</td>
                    <td>{$usuario->id_perfil}</td>
                    <td>{$usuario->perfil}</td>
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

}

?>