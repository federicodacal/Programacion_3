<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

class Verificadora 
{
    public function VerificarUsuario(Request $request, RequestHandler $handler) : ResponseMW 
    {
        $exito = false;

        $arrayParams = $request->getParsedBody();
    
        $obj_json = json_decode($arrayParams['obj_json']);
    
        $contendioAPI = "";

        $usuario = Verificadora::existeUsuario($obj_json);
    
        if(isset($usuario))
        {
            $response = $handler->handle($request);

            $contenidoAPI = (string) $response->getBody();

            $exito = true;
        }
        else 
        {
            $obj = new stdclass();
            $obj-> mensaje = "ERROR. Correo o clave incorrectas";

            $contenidoAPI = json_encode($obj);
        }

        $newResponse = new ResponseMW();

        if(!$exito)
        {
            $newResponse = $newResponse->withStatus(403);
        }

        $newResponse->getBody()->write($contenidoAPI);

        return $newResponse;
    }

    public static function existeUsuario($obj)
    {
        $usuario = null;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("SELECT u.id, u.nombre, u.apellido, u.correo, u.clave, u.id_perfil, u.foto, p.descripcion FROM usuarios u INNER JOIN perfiles p ON u.id_perfil = p.id WHERE u.correo = :correo AND u.clave = :clave");

        $consulta->bindValue(":correo", $obj->correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $obj->clave, PDO::PARAM_STR);

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

    public function VerificarJson(Request $request, RequestHandler $handler) : ResponseMW
    {
        $metodo = $request->getMethod();
        $contenidoAPI = "";
        $obj = new stdClass();
        $status = 403;

        if ($metodo === "GET") 
        {
            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();
            $status = $response->getStatusCode();
        } 
        else if ($metodo === "POST") 
        {
            $arrayDeParametros = $request->getParsedBody();

            if (isset($arrayDeParametros["obj_json"])) 
            {
                $response = $handler->handle($request);
                $contenidoAPI = (string) $response->getBody();
                $status = $response->getStatusCode();
            } 
            else 
            {        
                $obj->mensaje = "Falta parametro obj_json!!";
                $contenidoAPI = json_encode($obj);
            }
        }

        $response = new ResponseMW();
        $response = $response->withStatus($status);
        $response->getBody()->write($contenidoAPI);
        
        return $response;
    }
    
    public function VerificarCorreoYClave(Request $request, RequestHandler $handler) : ResponseMW 
    {
        $obj = new stdClass();
        $contenidoAPI = "";
        $status = 403;

        $arrayDeParametros = $request->getParsedBody();
        $obj_json = json_decode($arrayDeParametros["obj_json"]);

        if (isset($obj_json->correo) && isset($obj_json->clave)) 
        {
            $response = $handler->handle($request);
            $contenidoAPI = (string) $response->getBody();
            $status = $response->getStatusCode();
        } 
        else 
        {
            if(isset($obj_json->correo)) 
            {
                $obj->mensaje = "Falta atributo Clave";

            } 
            else if(isset($obj_json->clave)) 
            {
                $obj->mensaje = "Falta atributo Correo";
            } 
            else 
            {
                $obj->mensaje = "Faltan atributos Correo y Clave";
            }

            $obj->status = 403;
            $contenidoAPI = json_encode($obj);
        }

        $response = new ResponseMW();
        $response = $response->withStatus($status);
        $response->getBody()->write($contenidoAPI);

        return $response;
    }
}



?>