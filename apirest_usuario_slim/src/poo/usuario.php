<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once 'accesoDatos.php';
require_once 'islimeable.php';

class Usuario implements ISlimeable
{
    public int $id; 
    public string $nombre;
    public string $apellido;
    public string $foto;
    public string $correo;
    public string $clave;
    public int $id_perfil;
    public string $perfil;

//*********************************************************************************************//
/* IMPLEMENTO LAS FUNCIONES PARA SLIM */
//*********************************************************************************************//

    public function traerTodos(Request $request, Response $response, array $args) : Response 
    {
        $usuarios = Usuario::traerUsuarios();

        $newResponse = $response->withStatus(200, "OK");
        $newResponse->getBody()->write(json_encode($usuarios));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function traerUno(Request $request, Response $response, array $args) : Response
    {
        $id = $args['id'];

        $usuario = Usuario::obtenerUsuario($id);
        
		$newResponse = $response->withStatus(200, "OK");
		$newResponse->getBody()->write(json_encode($usuario));	

		return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function agregar(Request $request, Response $response, array $args) : Response
    {
        $obj_response = new stdclass();
        $obj_response->mensaje = "Problema en agregar.";
        $obj_response->fotoOk = "Problema guardando foto.";

        $arrayParams = $request->getParsedBody();
        
        $nombre = $arrayParams['nombre'];
        $apellido = $arrayParams['apellido'];
        $correo = $arrayParams['correo'];
        $clave = $arrayParams['clave'];
        $id_perfil = $arrayParams['id_perfil'];

        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->id_perfil = $id_perfil;

        $archivos = $request->getUploadedFiles();

        $pathFoto = Usuario::getPathFoto($archivos, $apellido);

        $this->foto = $pathFoto;

        if($this->agregarUsuario())
        {
            $obj_response->mensaje = "Usuario agregado!";

            if(Usuario::guardarFoto($pathFoto))
            {
                $obj_response->fotoOk = "Foto OK.";
            }
        }

        $newResponse = $response->withStatus(200, "OK");
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function modificar(Request $request, Response $response, array $args) : Response
    {
        
        $usuario_json = json_decode($args['usuario_json']);

        $usuario = new Usuario();

        $usuario->id = $usuario_json->id;
        $usuario->nombre = $usuario_json->nombre;
        $usuario->apellido = $usuario_json->apellido;
        $usuario->correo = $usuario_json->correo;
        $usuario->clave = $usuario_json->clave;
        $usuario->foto = $usuario_json->foto;
        $usuario->id_perfil = $usuario_json->id_perfil;

        $obj_response = new stdclass();
        $obj_response->exito = false;
        $obj_response->mensaje = "No modificado.";

        if($usuario->modificarUsuario())
        {
            $obj_response->exito = true;
            $obj_response->mensaje = "Modificado.";
        }

        $newResponse = $response->withStatus(200, "OK");
		$newResponse->getBody()->write(json_encode($obj_response));

		return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function borrar(Request $request, Response $response, array $args) : Response
    {
        $id = $args['id'];

        $usuario = new Usuario();
        $usuario->id = $id;
        
        $usuarioBD = Usuario::obtenerUsuario($id);
        
        $obj_response = new stdclass();
        $obj_response->exito = false;
        $obj_response->mensaje = "No borrado.";
        
        if($usuario->borrarUsuario())
        {
            unlink($usuarioBD->foto);

            $obj_response->exito = true;
            $obj_response->mensaje = "Borrado.";
        }

        $newResponse = $response->withStatus(200, "OK");
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');;
    }

    public function logear(Request $request, Response $response, array $args) : Response
    {
        $obj_response = new stdclass();
        $obj_response->mensaje = "Problema en login.";
        $obj_response->exito = false;
        $obj_response->usuario = null;

        $arrayParams = $request->getParsedBody();
        
        $correo = $arrayParams['correo'];
        $clave = $arrayParams['clave'];

        $this->correo = $correo;
        $this->clave = $clave;

        $usuario = $this->obtenerLogin();
        if(isset($usuario))
        {
            $obj_response->mensaje = "Usuario logeado!";
            $obj_response->exito = true;
            $obj_response->usuario = $usuario;
        }

        $newResponse = $response->withStatus(200, "OK");
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }


//*********************************************************************************************//
/* FUNCIONES PARA SLIM */
//*********************************************************************************************//

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

    private static function obtenerUsuario(int $id) : Usuario | null
    {
        $usuario = null;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("SELECT u.id, u.nombre, u.apellido, u.correo, u.clave, u.id_perfil, u.foto, p.descripcion FROM usuarios u INNER JOIN perfiles p ON u.id_perfil = p.id WHERE u.id = :id");

        $consulta->bindValue(":id", $id, PDO::PARAM_INT);

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

    private function agregarUsuario() : bool 
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO usuarios (nombre, apellido, correo, clave, foto, id_perfil)" . "VALUES(:nombre, :apellido, :correo, :clave, :foto, :id_perfil)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':correo', $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);

        $rta = $consulta->execute();
        
        return $rta;
    }

    private function borrarUsuario() : bool 
    {
        $rta = false;
            
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("DELETE FROM usuarios WHERE id = :id");

        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0)
        {
            $rta = true;
        }
        
        return $rta;
    }

    private function modificarUsuario() : bool
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE usuarios SET nombre = :nombre, apellido = :apellido, foto = :foto, id_perfil = :id_perfil, clave = :clave, correo = :correo WHERE id = :id");
        
        $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
        $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(":apellido", $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(":correo", $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(":foto", $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(":id_perfil", $this->id_perfil, PDO::PARAM_INT);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0)
        {
            $rta = true;
        }

        return $rta;
    }

    private function obtenerLogin() : Usuario | null
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

    private static function getPathFoto(array $foto, string $nombre) : string 
    {
        if ($foto != NULL) {
            $foto_nombre = $_FILES["foto"]["name"];
            $extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
            $path = __DIR__ . '/../fotos/' . $nombre . "." . date("His") . "." . $extension;
            $uploadOk = TRUE;
    
            $array_extensiones = array("jpg", "jpeg", "png");
    
            for ($i = 0; $i < count($array_extensiones); $i++) {
                $nombre_archivo = __DIR__ . '/../fotos/' . $nombre . "." . date("His") . "." . $array_extensiones[$i];
                if (file_exists($nombre_archivo)) {
                    unlink($nombre_archivo);
                    break;
                }
            }
    
            if ($_FILES["foto"]["size"] > 250000) {
                $uploadOk = FALSE;
            }
    
            $esImagen = getimagesize($_FILES["foto"]["tmp_name"]);
    
            if ($esImagen) {
                if (
                    $extension != "jpg" && $extension != "jpeg" && $extension != "gif"
                    && $extension != "png"
                ) {
                    echo "Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.";
                    $uploadOk = FALSE;
                }
            }
    
            if ($uploadOk === FALSE) {
                echo "<br/>NO SE PUDO SUBIR EL ARCHIVO.";
                $path = "";
            }
        }
        return $path;
    }

    private static function guardarFoto(string $path) : bool 
    {
        if(!isset($_FILES["foto"])){
            return false;
        }
        return move_uploaded_file($_FILES["foto"]["tmp_name"], $path);
    }
    
}

?>