<?php 

namespace Poo;

use PDO;

class AlumnoPDO 
{
    public string $nombre;
    public string $apellido;
    public int $legajo;
	public string $foto;

    public function __construct(string $nombre="", string $apellido="", int $legajo=0, string $foto="")
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->legajo = $legajo;
		$this->foto = $foto;
    }

    public function toString() : string 
    {
        return "$this->nombre - $this->apellido - $this->legajo - $this->foto";
    }
    
    public static function traerTodos()
    {    
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT legajo, nombre, apellido, foto FROM alumnos");        
        
        $consulta->execute();
        
        $consulta->setFetchMode(PDO::FETCH_INTO, new AlumnoPDO);                                                

        return $consulta; 
    }

    public static function agregar(AlumnoPDO $alumno) : bool
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO alumnos (legajo, nombre, apellido, foto)"
                                                    . "VALUES(:legajo, :nombre, :apellido, :foto)");
        
        $consulta->bindValue(':legajo', $alumno->legajo, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $alumno->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $alumno->foto, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $alumno->apellido, PDO::PARAM_STR);

        $rta = $consulta->execute();
        
        return $rta;
    }

    public static function obtener(int $legajo) : AlumnoPDO | NULL 
    {
        $alumno = null;

        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $accesoDatos->retornarConsulta("SELECT legajo, nombre, apellido, foto FROM alumnos WHERE legajo = :legajo");
		$consulta->bindValue(":legajo", $legajo, PDO::PARAM_INT);
		$consulta->execute();

		$array_alumno = $consulta->fetch();

        if($array_alumno != false && count($array_alumno) > 0)
        {
            $legajo = $array_alumno[0];
            $nombre = $array_alumno[1];
            $apellido = $array_alumno[2];
            $foto = $array_alumno[3];
            
            $alumno = new AlumnoPDO($nombre, $apellido, $legajo, $foto);
        }


		return $alumno;
    }

    public static function modificar(AlumnoPDO $alumno) : bool
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE alumnos SET nombre = :nombre, apellido = :apellido, 
                                                        foto = :foto WHERE legajo = :legajo");
        
        $consulta->bindValue(':legajo', $alumno->legajo, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $alumno->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $alumno->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $alumno->foto, PDO::PARAM_STR);

        $ok = $consulta->execute();

        if($ok && AlumnoPDO::obtener($alumno->legajo) != null)
        {
            $rta = true;
        }

        return $rta;
    }

    public static function borrar(int $legajo) : bool
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("DELETE FROM alumnos WHERE legajo = :legajo");
        
        $consulta->bindValue(':legajo', $legajo, PDO::PARAM_INT);

        $alumno = AlumnoPDO::obtener($legajo);
        if($alumno != null)
        {
            $pathFoto = $alumno->foto;

            $ok = $consulta->execute();
            if($ok && AlumnoPDO::obtener($legajo) == null)
            {
                if(file_exists($pathFoto))
                {
                    unlink($pathFoto);
                }
                $rta = true;
            }
        }
        return $rta;
    }

}

?>