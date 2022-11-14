<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once 'accesoDatos.php';
require_once 'autentificadora.php';

class Perfil 
{ 
    public int $id;
    public string $descripcion;
    public bool $estado;

    public function traerTodos(Request $request, Response $response, array $args): Response
    {
        $obj_response = new stdclass();
        
        $obj_response->exito = false;
        $obj_response->mensaje = "No hay perfiles";
        $obj_response->tabla = "{}";
        $obj_response->status = 424;

        $perfiles = Perfil::traerPerfiles();

        if(isset($perfiles) && count($perfiles) > 0)
        {
            $obj_response->exito = true;
            $obj_response->mensaje = "OK";
            $obj_response->tabla = json_encode($perfiles);
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
        $obj_response->mensaje = "Perfil no agregado";
        $obj_response->status = 418;

        if(isset($params['perfil']))
        {
            $perfil_json = json_decode($params['perfil']);

            $perfil = new Perfil();
            $perfil->descripcion = $perfil_json->descripcion;
            $perfil->estado = $perfil_json->estado;

            if($perfil->agregarPerfil())
            {
                $obj_response->exito = true;
                $obj_response->mensaje = "Perfil agregado";
                $obj_response->status = 200;
            }
        }

        $newResponse = $response->withStatus(($obj_response->status));
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function borrar(Request $request, Response $response, array $args) : Response
    {
        $obj_response = new stdclass();

        $obj_response->exito = false;
        $obj_response->mensaje = "No se borro";
        $obj_response->status = 418;

        if (isset($request->getHeader("token")[0]) && isset($args["id_perfil"])) 
        {
            $token = $request->getHeader("token")[0];
            $id = $args["id_perfil"];

            $datos_token = Autentificadora::obtenerPayLoad($token);

            if($datos_token->exito)
            {
                if(Perfil::borrarPerfil($id)) 
                {
                    $obj_response->exito = true;
                    $obj_response->mensaje = "Perfil borrado";
                    $obj_response->status = 200;
                } 
                else 
                {
                    $obj_response->mensaje = "El perfil no se encuentra en la base de datos.";
                }
            }
        }

        $newResponse = $response->withStatus($obj_response->status);
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function modificar(Request $request, Response $response, array $args): Response
    {
        $obj_response = new stdclass();

        $obj_response->exito = false;
        $obj_response->mensaje = "No se modifico";
        $obj_response->status = 418;

        if (isset($request->getHeader("token")[0]) && isset($args["id_perfil"]) && isset($args["perfil"])) 
        {
            $token = $request->getHeader("token")[0];
            $id_perfil = $args["id_perfil"];
            $obj_json = json_decode($args["perfil"]);

            $datos_token = Autentificadora::obtenerPayLoad($token);

            $perfil = new Perfil();
            $perfil->id = $id_perfil;
            $perfil->descripcion = $obj_json->descripcion;
            $perfil->estado = $obj_json->estado;

            if($datos_token->exito)
            {
                if($perfil->modificarPerfil()) 
                {
                    $obj_response->exito = true;
                    $obj_response->mensaje = "Perfil modificado";
                    $obj_response->status = 200;
                } 
                else 
                {
                    $obj_response->mensaje = "El perfil no se encuentra en la base de datos.";
                }
            }
        }

        $newResponse = $response->withStatus($obj_response->status);
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }


    /*****************************************************************************************************/

    private static function traerPerfiles() : array 
    {
        $perfiles = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM perfiles");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $perfil = new Perfil();

            $perfil->id = $fila["id"];
            $perfil->descripcion = $fila["descripcion"];
            $perfil->estado = $fila["estado"];

            array_push($perfiles, $perfil);
        }

        return $perfiles;  
    }

    private function agregarPerfil() : bool 
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO perfiles (descripcion, estado)" . "VALUES(:descripcion, :estado)");
        
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_BOOL);

        $rta = $consulta->execute();
        
        return $rta;
    }

    private static function borrarPerfil(int $id)
    {
        $rta = false;
        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $accesoDatos->retornarConsulta("DELETE FROM perfiles WHERE id = :id");

        $consulta->bindValue(":id", $id, PDO::PARAM_INT);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0) 
        {
            $rta = true;
        }

        return $rta;
    }

    public function modificarPerfil()
    {
        $rta = false;

        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $accesoDatos->retornarConsulta("UPDATE perfiles SET descripcion = :descripcion, estado = :estado WHERE id = :id");

        $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
        $consulta->bindValue(":descripcion", $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(":estado", $this->estado, PDO::PARAM_BOOL);
        
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