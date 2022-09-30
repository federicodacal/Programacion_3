<?php
namespace TestCrud;

class Elemento{

	public int $clave;
	public string $valor_uno;
	public string $valor_dos;

	public function __construct(int $clave, string $valor_uno, string $valor_dos)
	{
		$this->clave = $clave;
		$this->valor_uno = $valor_uno;
		$this->valor_dos = $valor_dos;
	}

	public static function agregar(Elemento $obj) : bool {

		$retorno = false;

		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/crud.txt", "a");//A - append

		//ESCRIBO EN EL ARCHIVO CON FORMATO: CLAVE-VALOR_UNO-VALOR_DOS
		$cant = fwrite($ar, "{$obj->clave}-{$obj->valor_uno}-{$obj->valor_dos}\r\n");

		if($cant > 0)
		{
			$retorno = true;			
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		return $retorno;
	}

	public static function listar() : string {

		$retorno = "";

		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/crud.txt", "r");

		//LEO LINEA X LINEA DEL ARCHIVO 
		while(!feof($ar))
		{
			$retorno .= fgets($ar);		
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		return $retorno;
	}

	public static function modificar(Elemento $obj) : bool {

		$retorno = false;

		$elementos = array();

		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/crud.txt", "r");

		//LEO LINEA X LINEA DEL ARCHIVO 
		while(!feof($ar))
		{
			$linea = fgets($ar);
			//http://www.w3schools.com/php/func_string_explode.asp
			$array_linea = explode("-", $linea);

			$array_linea[0] = trim($array_linea[0]);

			if($array_linea[0] != ""){
				//RECUPERO LOS CAMPOS
				$clave_archivo = trim($array_linea[0]);
				$valor_uno_archivo = trim($array_linea[1]);
				$valor_dos_archivo = trim($array_linea[2]);

				if ($clave_archivo == $obj->clave) {
					
					array_push($elementos, "{$clave_archivo}-{$obj->valor_uno}-{$obj->valor_dos}\r\n");
				}
				else{

					array_push($elementos, "{$clave_archivo}-{$valor_uno_archivo}-{$valor_dos_archivo}\r\n");
				}
			}
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/crud.txt", "w");

		$cant = 0;
		
		//ESCRIBO EN EL ARCHIVO
		foreach($elementos AS $item){

			$cant = fwrite($ar, $item);
		}

		if($cant > 0)
		{
			$retorno = true;			
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		return $retorno;
	}

	public static function borrar(int $clave) : bool {

		$retorno = false;

		$elementos = array();

		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/crud.txt", "r");

		//LEO LINEA X LINEA DEL ARCHIVO 
		while(!feof($ar))
		{
			$linea = fgets($ar);
			//http://www.w3schools.com/php/func_string_explode.asp
			$array_linea = explode("-", $linea);

			$array_linea[0] = trim($array_linea[0]);

			if($array_linea[0] != ""){

				//RECUPERO LOS CAMPOS
				$clave_archivo = trim($array_linea[0]);
				$valor_uno_archivo = trim($array_linea[1]);
				$valor_dos_archivo = trim($array_linea[2]);

				if ($clave_archivo == $clave) {
					
					continue;
				}

				array_push($elementos, "{$clave_archivo}-{$valor_uno_archivo}-{$valor_dos_archivo}\r\n");
			}
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		$cant = 0;

		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/crud.txt", "w");

		//ESCRIBO EN EL ARCHIVO
		foreach($elementos AS $item){

			$cant = fwrite($ar, $item);
		}

		if($cant > 0)
		{
			$retorno = true;			
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		return $retorno;
	}
}