<?php
//RECUPERO TODOS LOS VALORES (POST)
$tipoEjemplo = isset($_POST["tipoEjemplo"]) ? (int) $_POST["tipoEjemplo"] : 0;
$clave = isset($_POST["clave"]) ? (int) $_POST["clave"] : 0;
$valor_uno = isset($_POST["valor_uno"]) ? $_POST["valor_uno"] : NULL;
$valor_dos = isset($_POST["valor_dos"]) ? $_POST["valor_dos"] : NULL;

//****************************************** */
//CRUD - SOBRE ARCHIVOS
//****************************************** */

switch($tipoEjemplo)
{
	case 1://Create (Alta)

		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/crud.txt", "a");//A - append

		//ESCRIBO EN EL ARCHIVO CON FORMATO: CLAVE-VALOR_UNO-VALOR_DOS
		$cant = fwrite($ar, "{$clave}-{$valor_uno}-{$valor_dos}\r\n");

		if($cant > 0)
		{
			echo "<h2> registro AGREGADO </h2><br/>";			
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		break;

	case 2://Read (listar)

		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/crud.txt", "r");

		//LEO LINEA X LINEA DEL ARCHIVO 
		while(!feof($ar))
		{
			$linea = fgets($ar);

			echo $linea;		
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		break;

	case 3://Update (Modificar)

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
					
					array_push($elementos, "{$clave_archivo} - {$valor_uno} - {$valor_dos}\r\n");
				}
				else{

					array_push($elementos, "{$clave_archivo} - {$valor_uno_archivo} - {$valor_dos_archivo}\r\n");
				}
			}
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/crud.txt", "w");

		$cant = 0;
		
		//ESCRIBO EN EL ARCHIVO
		foreach($elementos AS $item)
		{
			$cant = fwrite($ar, $item);
		}

		if($cant > 0)
		{
			echo "<h2> registro MODIFICADO </h2><br/>";			
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		break;

	case 4://Delete (Borrar)

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

			if($array_linea[0] != "")
			{

				//RECUPERO LOS CAMPOS
				$clave_archivo = trim($array_linea[0]);
				$valor_uno_archivo = trim($array_linea[1]);
				$valor_dos_archivo = trim($array_linea[2]);

				if ($clave_archivo == $clave) 
				{	
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
			echo "<h2> registro BORRADO </h2><br/>";			
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		break;
				
	default:
		echo "<h2> Sin ejemplo </h2>";
}