
<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once "accesoDatosCd.php";

class Cd
{
	public int $id;
	public string $titulo;
	public string $cantante;
	public int $año;

	//*********************************************************************************************//
	/* IMPLEMENTO LAS FUNCIONES PARA SLIM */
	//*********************************************************************************************//

	public function traerTodos(Request $request, Response $response, array $args): Response
	{
		$todosLosCds = Cd::traerTodoLosCds();

		$newResponse = $response->withStatus(200, "OK");
		$newResponse->getBody()->write(json_encode($todosLosCds));

		return $newResponse->withHeader('Content-Type', 'application/json');
	}

	public function traerUno(Request $request, Response $response, array $args): Response
	{
		$id = $args['id'];
		$elCd = Cd::traerUnCd($id);

		$newResponse = $response->withStatus(200, "OK");
		$newResponse->getBody()->write(json_encode($elCd));

		return $newResponse->withHeader('Content-Type', 'application/json');
	}
	
	public function agregar(Request $request, Response $response, array $args): Response
	{
		$obj_rta = new stdClass();
		$obj_rta->mensaje = "No se pudo agregar el cd";
		$obj_rta->id_agregado = null;

		$arrayDeParametros = $request->getParsedBody();

		if (
			isset($arrayDeParametros['titulo']) &&
			isset($arrayDeParametros['cantante']) &&
			isset($arrayDeParametros['anio'])
		) {
			$titulo = $arrayDeParametros['titulo'];
			$cantante = $arrayDeParametros['cantante'];
			$año = $arrayDeParametros['anio'];

			$micd = new Cd();
			$micd->titulo = $titulo;
			$micd->cantante = $cantante;
			$micd->año = $año;

			$id_agregado = $micd->insertarCd();

			if ($id_agregado) {
				$obj_rta->mensaje = "Cd agregado!";
				$obj_rta->id_agregado = $id_agregado;
			}
		}

		$response->getBody()->write(json_encode($obj_rta));

		return $response->withHeader('Content-Type', 'application/json');
	}

	public function modificar(Request $request, Response $response, array $args): Response
	{
		$json = $request->getBody();
      
		$obj = json_decode($json);

		$micd = new Cd();
		$micd->id = $obj->id;
		$micd->titulo = $obj->titulo;
		$micd->cantante = $obj->cantante;
		$micd->año = $obj->anio;

		$resultado = $micd->modificarCd();

		$objDelaRespuesta = new stdclass();
		$objDelaRespuesta->resultado = $resultado;
		$objDelaRespuesta->mensaje = "No se pudo modificar el Cd";
		if ($resultado) {
			$objDelaRespuesta->mensaje = "Cd Modificado!";
		}

		$newResponse = $response->withStatus(200, "OK");
		$newResponse->getBody()->write(json_encode($objDelaRespuesta));

		return $newResponse->withHeader('Content-Type', 'application/json');
	}

	public function eliminar(Request $request, Response $response, array $args): Response
	{
		$json = $request->getBody();
      
		$obj = json_decode($json);

		$id = $obj->id;

		$cd = new Cd();
		$cd->id = $id;

		$cantidadDeBorrados = $cd->borrarCd();

		$objDeLaRespuesta = new stdclass();
		$objDeLaRespuesta->cantidad = $cantidadDeBorrados;

		if ($cantidadDeBorrados > 0) {
			$objDeLaRespuesta->resultado = "...algo borró!!!";
		} else {
			$objDeLaRespuesta->resultado = "...no borró nada!!!";
		}

		$newResponse = $response->withStatus(200, "OK");
		$newResponse->getBody()->write(json_encode($objDeLaRespuesta));

		return $newResponse->withHeader('Content-Type', 'application/json');
	}

	//*********************************************************************************************//
	/* FIN - AGREGO FUNCIONES PARA SLIM */
	//*********************************************************************************************//

	public static function traerTodoLosCds()
	{
		$objetoAccesoDato = AccesoDatosCD::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->retornarConsulta("select id, titel as titulo, interpret as cantante, jahr as año from cds");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "Cd");
	}

	public static function traerUnCd($id)
	{
		$objetoAccesoDato = AccesoDatosCD::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->retornarConsulta("select id, titel as titulo, interpret as cantante, jahr as año from cds where id = $id");
		$consulta->execute();
		$cdBuscado = $consulta->fetchObject('cd');
		return $cdBuscado;
	}

	public function insertarCd()
	{
		$objetoAccesoDato = AccesoDatosCD::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->retornarConsulta("INSERT into cds (titel,interpret,jahr)values(:titulo,:cantante,:anio)");
		$consulta->bindValue(':titulo', $this->titulo, PDO::PARAM_INT);
		$consulta->bindValue(':anio', $this->año, PDO::PARAM_STR);
		$consulta->bindValue(':cantante', $this->cantante, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->retornarUltimoIdInsertado();
	}

	public function modificarCd()
	{
		$retorno = false;

		$objetoAccesoDato = AccesoDatosCD::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->retornarConsulta("
				update cds 
				set titel=:titulo,
				interpret=:cantante,
				jahr=:anio
				WHERE id=:id");
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
		$consulta->bindValue(':titulo', $this->titulo, PDO::PARAM_STR);
		$consulta->bindValue(':anio', $this->año, PDO::PARAM_INT);
		$consulta->bindValue(':cantante', $this->cantante, PDO::PARAM_STR);
		$consulta->execute();

		$total_modificado = $consulta->rowCount(); // verifico las filas afectadas por la consulta
		if ($total_modificado == 1) {
			$retorno = true;
		}

		return $retorno;
	}

	public function borrarCd()
	{
		$objetoAccesoDato = AccesoDatosCD::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("delete from cds	WHERE id=:id");
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->rowCount();
	}
}