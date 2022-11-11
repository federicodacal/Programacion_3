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
        $arrayParams = $request->getParsedBody();

        $nombre = $arrayParams['nombre'];
        $apellido = $arrayParams['apellido'];

        $this->nombre = $nombre;
        $this->apellido = $apellido;

        // SUBIDA DE FOTO
        $archivo = $request->getUploadedFiles();
        $destino = __DIR__ . '/../fotos/';

        $nombreAnterior = $archivo['foto']->getClientFilename();
        $extension = explode('.', $nombreAnterior);

        $extension = array_reverse($extension);

        $destino .= $nombre . date('His') . '.' . $extension[0];

        $this->foto = $destino;

        $legajo = $this->agregarAlumno();

        $archivo['foto']->moveTo($destino);

        $response->getBody()->write("Alumno $nombre, legajo: $legajo agregado!");

        return $response;
    }

    public function modificar(Request $request, Response $response, array $args) : Response
    {
        return $response;
    }

    public function borrar(Request $request, Response $response, array $args) : Response
    {
        return $response;
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
        $accesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $accesoDatos->retornarConsulta("SELECT * FROM alumnos WHERE legajo = :legajo");
		
        $consulta->bindValue(":legajo", $legajo, PDO::PARAM_INT);
		
        $consulta->execute();

		$alumno = $consulta->fetchObject('alumno');
        if($alumno == false)
        {
            $alumno = null;
        }

        return $alumno;
    }

    private function agregarAlumno() : int 
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO alumnos (nombre, apellido, foto)" . "VALUES(:nombre, :apellido, :foto)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);

        $consulta->execute();
        
        return $objetoAccesoDato->retornarUltimoIdInsertado();
    }
    
}

?>