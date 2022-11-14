<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once 'accesoDatos.php';
require_once 'autentificadora.php';

class Juguete 
{ 
    public int $id;
    public string $marca;
    public float $precio;
    public string $path_foto;

    public function traerTodos(Request $request, Response $response, array $args): Response
    {
        $obj_response = new stdclass();
        
        $obj_response->exito = false;
        $obj_response->mensaje = "No hay juguetes";
        $obj_response->tabla = "{}";
        $obj_response->status = 424;

        $juguetes = Juguete::traerJuguetes();

        if(isset($juguetes) && count($juguetes) > 0)
        {
            $obj_response->exito = true;
            $obj_response->mensaje = "OK";
            $obj_response->tabla = json_encode($juguetes);
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
        $obj_response->mensaje = "Juguete no agregado";
        $obj_response->status = 418;
        $fotoOk = "";

        if(isset($params['juguete_json']))
        {
            $juguete_json = json_decode($params['juguete_json']);

            $juguete = new Juguete();
            $juguete->marca = $juguete_json->marca;
            $juguete->precio = $juguete_json->precio;
            $juguete->path_foto = "";

            $archivos = $request->getUploadedFiles();

            if(count($archivos))
            {
                $pathFoto = Juguete::getPathFoto($archivos, $juguete->marca);

                if(Juguete::guardarFoto($pathFoto))
                {
                    $fotoOk = "Foto guardada.";

                    $juguete->path_foto = $pathFoto;
                }
                else 
                {
                    $fotoOk = "Foto no guardada.";
                }
            }
            else 
            {
                $fotoOk = "Sin foto.";
            }

            if($juguete->agregarJuguete())
            {
                $obj_response->exito = true;
                $obj_response->mensaje = "Juguete agregado. " . $fotoOk;
                $obj_response->status = 200;
            }
        }

        $newResponse = $response->withStatus(($obj_response->status));
        $newResponse->getBody()->write(json_encode($obj_response));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    /**************************************************************************************/

    private static function traerJuguetes() : array 
    {
        $juguetes = array();

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM juguetes");        
        
        $consulta->execute();
                
        while($fila = $consulta->fetch(PDO::FETCH_ASSOC))
        {
            $juguete = new Juguete();

            $juguete->id = $fila["id"];
            $juguete->marca = $fila["marca"];
            $juguete->precio = $fila["precio"];
            $juguete->path_foto = $fila["path_foto"];

            array_push($juguetes, $juguete);
        }

        return $juguetes;  
    }

    private function agregarJuguete() : bool 
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO juguetes (marca, precio, path_foto)" . "VALUES( :marca, :precio, :path_foto)");
        
        $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':path_foto', $this->path_foto, PDO::PARAM_STR);

        $rta = $consulta->execute();
        
        return $rta;
    }

    private static function getPathFoto(array $foto, string $marca) : string 
    {
        if ($foto != NULL) {
            $foto_nombre = $_FILES["foto"]["name"];
            $extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
            $path = '../src/fotos/' . $marca . "." . $extension;
            $uploadOk = TRUE;

            $array_extensiones = array("jpg", "jpeg", "png");
    
            for ($i = 0; $i < count($array_extensiones); $i++) {
                $nombre_archivo = '../src/fotos/' . $marca . "." . $array_extensiones[$i];
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