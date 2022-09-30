<?php 

namespace Apellido;

class Alumno 
{
    public string $nombre;
    public string $apellido;
    public int $legajo;
	public string $foto;

    public function __construct(string $nombre, string $apellido, int $legajo, string $foto)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->legajo = $legajo;
		$this->foto = $foto;
    }

    public function toString() : string 
    {
        return "$this->nombre - $this->apellido - $this->legajo - $this->foto";
    }	 

    public static function agregar(Alumno $alumno) : bool
    {
        $rta = false;

        $ar = fopen("../archivos/alumnos.txt", "a"); //A - append

		$cant = fwrite($ar, "{$alumno->legajo} - {$alumno->nombre} - {$alumno->apellido} - {$alumno->foto}\r\n");

		if($cant > 0)
		{
            $rta = true;			
		}

		fclose($ar);

        return $rta;
    }

    public static function listar() : string
    {
        $ar = fopen("../archivos/alumnos.txt", "r");

        $mensaje =  "legajo - nombre - apellido - path foto<br>";
        $mensaje .= "***************************** <br>";
        while(!feof($ar))
        {
            $mensaje .= fgets($ar) . "<br>";	
        }

        fclose($ar);

        return $mensaje;
    }

    public static function verificar(int $legajo) :  Alumno | NULL
    {
        $alumno = NULL;

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
				$fotoAlumno = trim($array_linea[3]);

				if ($legajoAlumno == $legajo) 
                {
                    $alumno = new Alumno($nombreAlumno, $apellidoAlumno, $legajoAlumno, $fotoAlumno);
					break;
				}

			}
		}
        return $alumno;
    }
    
    public static function modificar(Alumno $alumno) : bool 
    {
        $rta = false;

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
				$fotoAlumno = trim($array_linea[3]);

				if ($legajoAlumno == $alumno->legajo) {
					
					array_push($elementos, "{$legajoAlumno} - {$alumno->nombre} - {$alumno->apellido} - {$alumno->foto}\r\n");
				}
				else{

					array_push($elementos, "{$legajoAlumno} - {$nombreAlumno} - {$apellidoAlumno} - {$fotoAlumno}\r\n");
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
			$rta = true;			
		}

		fclose($ar);

        return $rta;
    }

    public static function borrar(int $legajo) : bool
    {
        $rta = false;

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
				$fotoArchivo = trim($array_linea[3]);

				if ($legajoArchivo == $legajo) 
				{	
					continue; 
				}

				array_push($elementos, "{$legajoArchivo} - {$nombreArchivo} - {$apellidoArchivo} - {$fotoArchivo}\r\n");
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
			$rta = true;		
		}

		fclose($ar);

        return $rta;
    }

}

?>