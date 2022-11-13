<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once 'accesoDatos.php';

class Usuario
{
    public int $id; 
    public string $nombre;
    public string $apellido;
    public string $foto;
    public string $correo;
    public string $clave;
    public int $id_perfil;
    public string $perfil;

    public function obtenerLogin() : Usuario | null
    {
        $usuario = null;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("SELECT u.id, u.nombre, u.apellido, u.correo, u.clave, u.id_perfil, u.foto, p.descripcion FROM usuarios u INNER JOIN perfiles p ON u.id_perfil = p.id WHERE u.correo = :correo AND u.clave = :clave");

        $consulta->bindValue(":correo", $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $this->clave, PDO::PARAM_STR);

        $consulta->execute();

        if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
        {

            $id = $fila["id"];
            $nombre = $fila["nombre"];
            $apellido = $fila["apellido"];
            $correo = $fila["correo"];
            $clave = $fila["clave"];
            $foto = $fila["foto"];
            $id_perfil = $fila["id_perfil"];
            $descripcion = $fila["descripcion"];

            $usuario = new Usuario();
            $usuario->id = $id;
            $usuario->nombre = $nombre;
            $usuario->apellido = $apellido;
            $usuario->correo = $correo;
            $usuario->clave = $clave;
            $usuario->foto = $foto;
            $usuario->id_perfil = $id_perfil;
            $usuario->perfil = $descripcion;
        }
        
        return $usuario;
    }

}