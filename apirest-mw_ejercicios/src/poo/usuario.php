<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once 'accesoDatos.php';

class Usuario 
{
    public int $id; 
    public string $nombre;
    public string $apellido;
    public string $correo;
    public string $clave;
    public int $id_perfil;
    public string $foto;

    public function TraerTodos(Request $request, Response $response, array $args) : Response 
    {
        $usuarios = Usuario::traerUsuarios();

        $newResponse = $response->withStatus(200, "OK");
        $newResponse->getBody()->write(json_encode($usuarios));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    private static function traerUsuarios() : array
    {
        $usuarios = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT u.id, u.nombre, u.apellido, u.correo, u.clave, u.foto, u.id_perfil, p.descripcion FROM usuarios u INNER JOIN perfiles p ON u.id_perfil = p.id");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
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

            array_push($usuarios, $usuario);
        }

        return $usuarios;  
    }

}



?>