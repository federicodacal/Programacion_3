<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once 'accesoDatos.php';
require_once 'autentificadora.php';

class Auto 
{ 
    public int $id;
    public string $color;
    public string $marca;
    public float $precio;
    public string $modelo;

    public function traerTodos(Request $request, Response $response, array $args): Response
    {
        $obj_response = new stdclass();
        
        $obj_response->exito = false;
        $obj_response->mensaje = "No hay autos";
        $obj_response->tabla = "{}";
        $obj_response->status = 424;

        $autos = Auto::traerAutos();

        if(isset($autos) && count($autos) > 0)
        {
            $obj_response->exito = true;
            $obj_response->mensaje = "OK";
            $obj_response->tabla = json_encode($autos);
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
        $obj_response->mensaje = "Auto no agregado";
        $obj_response->status = 418;

        if(isset($params['auto']))
        {
            $auto_json = json_decode($params['auto']);

            $auto = new auto();
            $auto->color = $auto_json->color;
            $auto->marca = $auto_json->marca;
            $auto->precio = $auto_json->precio;
            $auto->modelo = $auto_json->modelo;

            if($auto->agregarAuto())
            {
                $obj_response->exito = true;
                $obj_response->mensaje = "Auto agregado";
                $obj_response->status = 200;
            }
        }

        $newResponse = $response->withStatus(($obj_response->status));
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function borrar(Request $request, Response $response, array $args): Response
    {
        $obj_response = new stdclass();

        $obj_response->exito = false;
        $obj_response->mensaje = "No se borro";
        $obj_response->status = 418;

        if (isset($request->getHeader("token")[0]) && isset($request->getHeader("id_auto")[0])) 
        {
            $token = $request->getHeader("token")[0];
            $id = $request->getHeader("id_auto")[0];

            $datos_token = Autentificadora::obtenerPayLoad($token);
            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            if ($perfil_usuario == "propietario") 
            {
                if (Auto::borrarAuto($id)) 
                {
                    $obj_response->exito = true;
                    $obj_response->mensaje = "Auto borrado";
                    $obj_response->status = 200;
                } 
                else 
                {
                    $obj_response->mensaje = "El auto no se encuentra en la base de datos.";
                }
            } 
            else 
            {
                $obj_response->mensaje = "Usuario no autorizado para realizar la accion. {$usuario_token->nombre} - {$usuario_token->apellido} - {$usuario_token->perfil}";
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

        if (isset($request->getHeader("token")[0]) && isset($request->getHeader("id_auto")[0]) && isset($request->getHeader("auto")[0])) 
        {
            $token = $request->getHeader("token")[0];
            $id = $request->getHeader("id_auto")[0];
            $obj_json = json_decode($request->getHeader("auto")[0]);

            $datos_token = Autentificadora::obtenerPayLoad($token);
            $usuario_token = $datos_token->payload->data;
            $perfil_usuario = $usuario_token->perfil;

            if($perfil_usuario == "encargado") 
            {
                $auto = new Auto();
                $auto->id = $id;
                $auto->color = $obj_json->color;
                $auto->marca = $obj_json->marca;
                $auto->precio = $obj_json->precio;
                $auto->modelo = $obj_json->modelo;

                if($auto->modificarAuto()) 
                {
                    $obj_response->exito = true;
                    $obj_response->mensaje = "Auto modificado";
                    $obj_response->status = 200;
                } 
                else 
                {
                    $obj_response->mensaje = "El auto no se encuentra en la base de datos.";
                }
            } 
            else 
            {
                $obj_response->mensaje = "Usuario no autorizado para realizar la accion. {$usuario_token->nombre} - {$usuario_token->apellido} - {$usuario_token->perfil}";
            }
        }

        $newResponse = $response->withStatus($obj_response->status);
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }


    /*****************************************************************************************************/

    private static function traerAutos() : array 
    {
        $autos = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM autos");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $auto = new Auto();

            $auto->id = $fila["id"];
            $auto->color = $fila["color"];
            $auto->marca = $fila["marca"];
            $auto->precio = $fila["precio"];
            $auto->modelo = $fila["modelo"];

            array_push($autos, $auto);
        }

        return $autos;  
    }

    private function agregarAuto() : bool 
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO autos (color, marca, precio, modelo)" . "VALUES(:color, :marca, :precio, :modelo)");
        
        $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
        $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':modelo', $this->modelo, PDO::PARAM_STR);

        $rta = $consulta->execute();
        
        return $rta;
    }

    private static function borrarAuto(int $id)
    {
        $rta = false;
        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $accesoDatos->retornarConsulta("DELETE FROM autos WHERE id = :id");

        $consulta->bindValue(":id", $id, PDO::PARAM_INT);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0) 
        {
            $rta = true;
        }

        return $rta;
    }
    
    public function modificarAuto()
    {
        $rta = false;

        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $accesoDatos->retornarConsulta("UPDATE autos SET color = :color, marca = :marca, precio = :precio, modelo = :modelo WHERE id = :id");

        $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
        $consulta->bindValue(":color", $this->color, PDO::PARAM_STR);
        $consulta->bindValue(":marca", $this->marca, PDO::PARAM_STR);
        $consulta->bindValue(":precio", $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(":modelo", $this->modelo, PDO::PARAM_INT);
        
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