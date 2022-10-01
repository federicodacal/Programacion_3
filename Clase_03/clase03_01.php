<?php

/*
echo $_POST["tipoEjemplo"];
die();
*/


/*
if(isset($_POST["tipoEjemplo"]))
{
	$tipoEjemplo = (int) $_POST["tipoEjemplo"];

}
else
{
	$tipoEjemplo = 0;

}

//echo $tipoEjemplo; die();
*/

//$tipoEjemplo = isset($_POST["tipoEjemplo"]) ? (int) $_POST["tipoEjemplo"] : 0;

$tipoEjemplo = 1;

switch($tipoEjemplo)
{
	case 1:
		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/miArchivo.txt", "r");

		//LEO EL ARCHIVO
		echo "<h2>" . fread($ar, filesize("./archivos/miArchivo.txt")) . "</h2>";

		//CIERRO EL ARCHIVO
		fclose($ar);

		break;

	case 2:
		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/miArchivo.txt", "r");

		//LEO 5 BYTES DEL ARCHIVO (5 LETRAS)
		echo "<h2>" . fread($ar, 5) . "</h2>";

		//CIERRO EL ARCHIVO
		fclose($ar);

		break;

	case 3:
		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/miArchivo.txt", "r");

		//LEO 1 LINEA DEL ARCHIVO
		echo "<h2>" . fgets($ar) . "</h2>";

		//CIERRO EL ARCHIVO
		fclose($ar);

		break;

	case 4:
		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/miArchivo.txt", "r");

		//LEO LINEA X LINEA DEL ARCHIVO 
		while(!feof($ar))
		{
			echo "<h2>" . fgets($ar) . "</h2>";
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		break;

	case 5:
		//ABRO EL ARCHIVO
		$ar = fopen("./archivos/miArchivo.txt", "a"); //A - append
		
		//ESCRIBO EN EL ARCHIVO
		$cant = fwrite($ar, "\n-Escribo en el archivo.");
		
		if($cant > 0)
		{
			echo "<h2> escritura EXITOSA </h2><br/>";			
		}

		//CIERRO EL ARCHIVO
		fclose($ar);

		break;

	case 6:
		
		$path_origen = "./archivos/miArchivo.txt";
		$path_destino = "./archivos/miArchivo2.txt";
		
		//COPIO EN EL ARCHIVO
		$copio = copy($path_origen, $path_destino);
		
		if($copio)
		{
			echo "<h2> copia EXITOSA </h2><br/>";			
		}
		else
		{
			echo "<h2> no se pudo COPIAR </h2>";
		}

		break;

	case 7:
		
		$path = "./archivos/miArchivo2.txt";
		
		//ELIMINO EL ARCHIVO
		$elimino = unlink($path);
		
		if($elimino)
		{
			echo "<h2> eliminó EXITOSAMENTE </h2><br/>";			
		}
		else
		{
			echo "<h2> no se pudo ELIMINAR </h2>";
		}

		break;	
					
	default:
		echo "<h2> Sin ejemplo </h2>";
}