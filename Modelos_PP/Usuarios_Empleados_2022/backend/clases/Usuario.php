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
        return json_encode($this);
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
            $mensaje = "Usuario {$this->id} guardado con éxito";
        }

        fclose($ar);

        return json_encode(array("exito"=>$exito, "mensaje"=>$mensaje));
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
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO usuarios (nombre, correo, clave, id_perfil)" . "VALUES(:nombre, :correo, :clave, :id_perfil)");
        
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
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM usuarios");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $id = $fila["id"];
            $nombre = $fila["nombre"];
            $correo = $fila["correo"];
            $clave = $fila["clave"];
            $id_perfil = $fila["id_perfil"];
            //$descripcionPerfil = $fila["descripcion"];

            $usuario = new Usuario($nombre, $correo, $clave, $id_perfil); //$descripcionPerfil, $id);

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
    
            $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM usuarios WHERE correo = :correo AND  clave = :clave");
    
            $consulta->bindValue(":correo", $obj["correo"], PDO::PARAM_STR);
            $consulta->bindValue(":clave", $obj["clave"], PDO::PARAM_STR);
    
            $consulta->execute();
    
            while($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
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

}

?>