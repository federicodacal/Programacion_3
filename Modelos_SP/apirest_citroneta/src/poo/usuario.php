<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once 'accesoDatos.php';
require_once 'autentificadora.php';

class Usuario 
{ 
    public int $id;
    public string $nombre;
    public string $apellido;
    public string $correo;
    public string $clave;
    public string $perfil;
    public string $foto;

    public function traerTodos(Request $request, Response $response, array $args): Response
    {
        $obj_response = new stdclass();
        
        $obj_response->exito = false;
        $obj_response->mensaje = "No hay usuarios";
        $obj_response->tabla = "{}";
        $obj_response->status = 424;

        $usuarios = Usuario::traerUsuarios();

        if(isset($usuarios) && count($usuarios) > 0)
        {
            $obj_response->exito = true;
            $obj_response->mensaje = "OK";
            $obj_response->tabla = json_encode($usuarios);
            $obj_response->status = 200;
        }

        $newResponse = $response->withStatus($obj_response->status);
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function agregar(Request $request, Response $response, array $args): Response
    {
        $params = $request->getParsedBody();

        $obj_response = new stdclass();
        $obj_response->exito = false;
        $obj_response->mensaje = "Usuario no agregado";
        $obj_response->status = 418;
        $fotoOk = "Problema guardando foto";

        if(isset($params['usuario']))
        {
            $usuario_json = json_decode($params['usuario']);

            $usuario = new Usuario();
            $usuario->correo = $usuario_json->correo;
            $usuario->clave = $usuario_json->clave;
            $usuario->nombre = $usuario_json->nombre;
            $usuario->apellido = $usuario_json->apellido;
            $usuario->perfil = $usuario_json->perfil;
            $usuario->foto = "";

            $id_registro = $usuario->agregarUsuario();

            if($id_registro != -1)
            {
                $obj_response->exito = true;
                $obj_response->mensaje = "Usuario agregado. ";
                $obj_response->status = 200;

                $usuario->id = $id_registro;

                $archivos = $request->getUploadedFiles();

                if(count($archivos))
                {
                    $pathFoto = Usuario::getPathFoto($archivos, $usuario->correo, $usuario->id);

                    if(Usuario::guardarFoto($pathFoto))
                    {
                        $fotoOk = "Foto guardada. ";

                        $usuario->foto = $pathFoto;

                        if($usuario->modificarUsuario())
                        {
                            $fotoOk .= "Base actualizada con foto.";
                        }
                    }
                    else 
                    {
                        $fotoOk = "Foto no guardada.";
                    }

                    $obj_response->mensaje .= $fotoOk;
                }
                else 
                {
                    $obj_response->mensaje .= "Sin foto.";
                }
            }
        }

        $newResponse = $response->withStatus(($obj_response->status));
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function logear(Request $request, Response $response, array $args): Response
    {
        $params = $request->getParsedBody();

        $obj_response = new stdClass();
        $obj_response->exito = false;
        $obj_response->jwt = null;
        $obj_response->status = 403;

        if(isset($params['user']))
        {
            $user_json = json_decode($params['user']);

            $user = new Usuario();
            $user->correo = $user_json->correo;
            $user->clave = $user_json->clave;

            $userBD = $user->loginUsuario();

            if(isset($userBD))
            {
                $obj_response->exito = true;
                $obj_response->jwt = Autentificadora::crearJWT($userBD, 60*10);
                $obj_response->status = 200;
            }
        }

        $response = $response->withStatus($obj_response->status);
        $response->getBody()->write(json_encode($obj_response));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function chequearJWT(Request $request, Response $response, array $args): Response
    {
        $contenidoAPI = "";

        $obj_response = new stdClass();
        $obj_response->mensaje = "Token Invalido";
        $obj_response->status = 403;

        if (isset($request->getHeader("token")[0])) 
        {
            $token = $request->getHeader("token")[0];

            $datos_token = Autentificadora::verificarJWT($token);

            if ($datos_token->verificado)
            {
                $obj_response->mensaje = $datos_token->mensaje;
                $obj_response->status = 200;
            }
        }

        $response = $response->withStatus($obj_response->status);
        $response->getBody()->write(json_encode($obj_response));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /*****************************************************************************************************/

    private static function traerUsuarios() : array 
    {
        $usuarios = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM usuarios");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $usuario = new Usuario();

            $usuario->id = $fila["id"];
            $usuario->nombre = $fila["nombre"];
            $usuario->apellido = $fila["apellido"];
            $usuario->correo = $fila["correo"];
            $usuario->clave = $fila["clave"];
            $usuario->foto = $fila["foto"];
            $usuario->perfil = $fila["perfil"];

            array_push($usuarios, $usuario);
        }

        return $usuarios;  
    }

    private function agregarUsuario() : int 
    {
        $rta = -1;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO usuarios (nombre, apellido, correo, clave, foto, perfil)" . "VALUES(:nombre, :apellido, :correo, :clave, :foto, :perfil)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':correo', $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_INT);

        if($consulta->execute() != false)
        {
            $rta = $objetoAccesoDato->retornarUltimoIdInsertado();
        }
        
        return $rta;
    }

    private function modificarUsuario() : bool
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE usuarios SET nombre = :nombre, apellido = :apellido, foto = :foto, perfil = :perfil, clave = :clave, correo = :correo WHERE id = :id");
        
        $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
        $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(":apellido", $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(":correo", $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(":foto", $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(":perfil", $this->perfil, PDO::PARAM_INT);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0)
        {
            $rta = true;
        }

        return $rta;
    }

    public function loginUsuario() : Usuario | null 
    {
        $usuario = null;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM usuarios WHERE correo = :correo AND clave = :clave");

        $consulta->bindValue(":correo", $this->correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $this->clave, PDO::PARAM_STR);

        $consulta->execute();

        if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
        {
            $usuario = new Usuario();

            $usuario->id = $fila["id"];
            $usuario->nombre = $fila["nombre"];
            $usuario->apellido = $fila["apellido"];
            $usuario->correo = $fila["correo"];
            $usuario->clave = $fila["clave"];
            $usuario->foto = $fila["foto"];
            $usuario->perfil = $fila["perfil"];
        }
        
        return $usuario;
    }

    public function traerPorCorreo() : Usuario | null 
    {
        $usuario = null;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM usuarios WHERE correo = :correo");

        $consulta->bindValue(":correo", $this->correo, PDO::PARAM_STR);

        $consulta->execute();

        if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
        {
            $usuario = new Usuario();

            $usuario->id = $fila["id"];
            $usuario->nombre = $fila["nombre"];
            $usuario->apellido = $fila["apellido"];
            $usuario->correo = $fila["correo"];
            $usuario->clave = $fila["clave"];
            $usuario->foto = $fila["foto"];
            $usuario->perfil = $fila["perfil"];
        }
        
        return $usuario;
    }

    private static function getPathFoto(array $foto, string $correo, int $id) : string 
    {
        if ($foto != NULL) {
            $foto_nombre = $_FILES["foto"]["name"];
            $extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
            $path = '../src/fotos/' . $correo . "_" . $id . "." . $extension;
            $uploadOk = TRUE;

            $array_extensiones = array("jpg", "jpeg", "png");
    
            for ($i = 0; $i < count($array_extensiones); $i++) {
                $nombre_archivo = '../src/fotos/' . $correo . "_" . $id . "." . $array_extensiones[$i];
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