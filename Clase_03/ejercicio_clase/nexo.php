<?php   

//$accion = isset($_POST["accion"]) ? $_POST["accion"] : "";
//$accion = isset($_GET["accion"]) ? $_GET["accion"] : "";

$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"] : "";

$legajo = isset($_POST["legajo"]) ? (int) $_POST["legajo"] : 0;
$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "...";
$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "...";

//var_dump($_POST);
//var_dump($_GET);
//var_dump($_REQUEST);

switch($accion)
{
	case "agregar":

		$ar = fopen("../archivos/alumnos.txt", "a"); //A - append

		$cant = fwrite($ar, "{$legajo} - {$nombre} - {$apellido}\r\n");

		if($cant > 0)
		{
			echo "Registro AGREGADO<br/>";			
		}
        else
        {
            echo "No agregado <br/>";
        }

		fclose($ar);
		break;

    case "listar":

        $ar = fopen("../archivos/alumnos.txt", "r");

        echo "legajo - nombre - apellido \n";
        echo "***************************** \n";
        while(!feof($ar))
        {
            $linea = fgets($ar);
            echo $linea;		
        }

        fclose($ar);
        break;

    case "verificar":

		$ar = fopen("../archivos/alumnos.txt", "r");

		while(!feof($ar))
		{
            $alumnoEncontrado = false;
			$linea = fgets($ar);
			$array_linea = explode("-", $linea);

			$array_linea[0] = trim($array_linea[0]);

			if($array_linea[0] != "")
            {
				$legajoAlumno = trim($array_linea[0]);
				$nombreAlumno = trim($array_linea[1]);
				$apellidoAlumno = trim($array_linea[2]);

				if ($legajoAlumno == $legajo) 
                {
                    $alumnoEncontrado = true;
					break;
				}

			}
		}

        if($alumnoEncontrado)
        {
            echo "El alumno con legajo {$legajo} se encuentra en el listado: {$apellidoAlumno}, {$nombreAlumno}\n";	
        }
        else
        {
            echo "El alumno con legajo {$legajo} no se encuentra en el listado\n";
        }
        break;
    
    case "modificar":
	
        $elementos = array();

		$ar = fopen("../archivos/alumnos.txt", "r");

		while(!feof($ar))
		{
			$linea = fgets($ar);
			$array_linea = explode("-", $linea);

			$array_linea[0] = trim($array_linea[0]);

			if($array_linea[0] != "")
            {
                $legajoAlumno = trim($array_linea[0]);
				$nombreAlumno = trim($array_linea[1]);
				$apellidoAlumno = trim($array_linea[2]);

				if ($legajoAlumno == $legajo) {
					
					array_push($elementos, "{$legajo}-{$nombre}-{$apellido}\r\n");
				}
				else{

					array_push($elementos, "{$legajoAlumno} - {$nombreAlumno} - {$apellidoAlumno}\r\n");
				}
			}
		}

		fclose($ar);

		$ar = fopen("../archivos/alumnos.txt", "w");

		$cant = 0;

		foreach($elementos AS $item)
		{
			$cant = fwrite($ar, $item);
		}

		if($cant > 0)
		{
			echo "Registro MODIFICADO<br/>";			
		}

		fclose($ar);

        break;
	
	case "borrar":
		
		$elementos = array();

		$ar = fopen("../archivos/alumnos.txt", "r");

		while(!feof($ar))
		{
			$linea = fgets($ar);

			$array_linea = explode("-", $linea);

			$array_linea[0] = trim($array_linea[0]);

			if($array_linea[0] != "")
			{

				$legajoArchivo = trim($array_linea[0]);
				$nombreArchivo = trim($array_linea[1]);
				$apellidoArchivo = trim($array_linea[2]);

				if ($legajoArchivo == $legajo) 
				{	
					continue; 
				}

				array_push($elementos, "{$legajoArchivo} - {$nombreArchivo} - {$apellidoArchivo}\r\n");
			}
		}

		fclose($ar);

		$cant = 0;

		$ar = fopen("../archivos/alumnos.txt", "w");

		foreach($elementos AS $item){

			$cant = fwrite($ar, $item);
		}

		if($cant > 0)
		{
			echo "<h2> registro BORRADO </h2><br/>";			
		}

		fclose($ar);

		break;
		
}


?>