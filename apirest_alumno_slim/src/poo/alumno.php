<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once 'accesoDatos.php';
require_once 'islimeable.php';

class Alumno implements ISlimeable
{
    public string $nombre;
    public string $apellido;
    public int $legajo; 
    public string $foto;

    public function toString() : string 
    {
        return "$this->nombre - $this->apellido - $this->legajo - $this->foto";
    }

//*********************************************************************************************//
/* IMPLEMENTO LAS FUNCIONES PARA SLIM */
//*********************************************************************************************//

    public function traerTodos(Request $request, Response $response, array $args) : Response 
    {
        $alumnos = Alumno::traerAlumnos();

        $newResponse = $response->withStatus(200, "OK");
        $newResponse->getBody()->write(json_encode($alumnos));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function verificar(Request $request, Response $response, array $args) : Response
    {
        $legajo = $args['legajo'];

        $alumno = Alumno::obtenerAlumno($legajo);
        
		$newResponse = $response->withStatus(200, "OK");
		$newResponse->getBody()->write(json_encode($alumno));	

		return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function agregar(Request $request, Response $response, array $args) : Response
    {
        $fotoOk = "Problema guardando foto.";
        $mensaje = "Problema en agregar.";

        $arrayParams = $request->getParsedBody();
        
        $nombre = $arrayParams['nombre'];
        $apellido = $arrayParams['apellido'];

        $this->nombre = $nombre;
        $this->apellido = $apellido;

        $archivos = $request->getUploadedFiles();

        $pathFoto = Alumno::getPathFoto($archivos, $nombre);

        $this->foto = $pathFoto;

        if($this->agregarAlumno())
        {
            $mensaje = "Alumno agregado!";

            if(Alumno::guardarFoto($pathFoto))
            {
                $fotoOk = "Foto OK.";
            }
        }

        $response->getBody()->write($mensaje . " " . $fotoOk);

        return $response;
    }

    public function modificar(Request $request, Response $response, array $args) : Response
    {
        $alumo_json = json_decode($args['alumno_json']);

        $alumno = new Alumno();

        $alumno->legajo = $alumo_json->legajo;
        $alumno->nombre = $alumo_json->nombre;
        $alumno->apellido = $alumo_json->apellido;
        $alumno->foto = $alumo_json->foto;

        $obj_response = new stdclass();
        $obj_response->exito = false;
        $obj_response->mensaje = "No modificado.";

        if($alumno->modificarAlumno())
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
        $legajo = $args['legajo'];

        $alumno = new Alumno();

        $alumno->legajo = $legajo;

        $obj_response = new stdclass();
        $obj_response->exito = false;
        $obj_response->mensaje = "No borrado.";

        if($alumno->borrarAlumno())
        {
            $obj_response->exito = true;
            $obj_response->mensaje = "Borrado.";
        }

        $newResponse = $response->withStatus(200, "OK");
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');;
    }

    public function logear(Request $request, Response $response, array $args) : Response
    {
        $alumo_json = json_decode($args['alumno_json']);

        $alumno = new Alumno();

        $alumno->legajo = $alumo_json->legajo;
        $alumno->apellido = $alumo_json->apellido;

        $alumnoLogin = $alumno->obtenerLogin();

        $obj_response = new stdclass();
        $obj_response->exito = false;
        $obj_response->mensaje = "No logeado.";
        $obj_response->alumno = null;

        if(isset($alumnoLogin))
        {
            $obj_response->exito = true;
            $obj_response->mensaje = "Logeado.";
            $obj_response->alumno = $alumnoLogin;
        }
        
		$newResponse = $response->withStatus(200, "OK");
		$newResponse->getBody()->write(json_encode($obj_response));	

		return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function mostrar(Request $request, Response $response, array $args) : Response
    {
        $tabla = Alumno::generarTabla();
  
		$newResponse = $response->withStatus(200, "OK");
		$newResponse->getBody()->write($tabla);

		return $newResponse->withHeader('Content-Type', 'application/json');	
    }

//*********************************************************************************************//
/* FUNCIONES PARA SLIM */
//*********************************************************************************************//

    private static function traerAlumnos() : array
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM alumnos");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Alumno');
    }

    private static function obtenerAlumno(int $legajo) : Alumno | null
    {
        $alumno = null; 
        
        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $accesoDatos->retornarConsulta("SELECT * FROM alumnos WHERE legajo = :legajo");
		
        $consulta->bindValue(":legajo", $legajo, PDO::PARAM_INT);
		
        $consulta->execute();

        if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
        {
            $legajo = $fila["legajo"];
            $nombre = $fila["nombre"];
            $apellido = $fila["apellido"];
            $pathFoto = $fila["foto"];
            
            $alumno = new Alumno();

            $alumno->legajo = $legajo;
            $alumno->nombre = $nombre;
            $alumno->apellido = $apellido;
            $alumno->foto = $pathFoto;
        }

        return $alumno;
    }

    private function agregarAlumno() : bool 
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO alumnos (nombre, apellido, foto)" . "VALUES(:nombre, :apellido, :foto)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);

        $rta = $consulta->execute();
        
        return $rta;
    }

    public function borrarAlumno() : bool 
    {
        $rta = false;

        if(isset($this->legajo))
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->retornarConsulta("DELETE FROM alumnos WHERE legajo = :legajo");
    
            $consulta->bindValue(':legajo', $this->legajo, PDO::PARAM_INT);
    
            $ok = $consulta->execute();
    
            $affectedRows = $consulta->rowCount();
    
            if($ok && $affectedRows > 0)
            {
                $rta = true;
            }
        }

        return $rta;
    }

    private function modificarAlumno() : bool
    {
        $rta = false;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("UPDATE alumnos SET nombre = :nombre, apellido = :apellido, foto = :foto WHERE legajo = :legajo");
        
        $consulta->bindValue(":legajo", $this->legajo, PDO::PARAM_INT);
        $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(":apellido", $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(":foto", $this->foto, PDO::PARAM_STR);

        $ok = $consulta->execute();

        $affectedRows = $consulta->rowCount();

        if($ok && $affectedRows > 0)
        {
            $rta = true;
        }

        return $rta;
    }

    public function obtenerLogin() : Alumno | null
    {
        $alumno = null; 
        
        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $accesoDatos->retornarConsulta("SELECT * FROM alumnos WHERE legajo = :legajo AND apellido = :apellido");
		
        $consulta->bindValue(":legajo", $this->legajo, PDO::PARAM_INT);
        $consulta->bindValue(":apellido", $this->apellido, PDO::PARAM_STR);
		
        $consulta->execute();

        if($fila = $consulta->fetch(PDO::FETCH_ASSOC)) 
        {
            $legajo = $fila["legajo"];
            $nombre = $fila["nombre"];
            $apellido = $fila["apellido"];
            $pathFoto = $fila["foto"];
            
            $alumno = new Alumno();

            $alumno->legajo = $legajo;
            $alumno->nombre = $nombre;
            $alumno->apellido = $apellido;
            $alumno->foto = $pathFoto;
        }

        return $alumno;
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

    public static function generarTabla() : string
    {
        $response = "";

        $alumnos = Alumno::traerAlumnos();

        if(isset($alumnos)) //&& count($alumnos) > 0)
        {
            $response = 
            "<table>
                <caption>Listado de Alumnos</caption>
                <tr>
                    <th>Legajo</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Path Foto</th>
                    <th>Foto</th>
                </tr>";
            
            foreach($alumnos as $a)
            {
                $response .=
                "<tr>
                    <td>{$a->legajo}</td>
                    <td>{$a->nombre}</td>
                    <td>{$a->apellido}</td>
                    <td>{$a->foto}</td>
                    <td><img src='" . $a->foto . "' alt='Nope' width='50px' height='50px'></td>
                </tr>";
            }
            $response .= "</table>";
        }
        else 
        {
            $response = "No se obtuvieron alumnos";
        }

        return $response;
    }
    
}

?>