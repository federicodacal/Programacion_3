<?php 

class Usuario 
{
    private string $nombre;
    private string $clave;
    private string $mail;

    public function __construct(string $nombre, string $clave, string $mail)
    {
        $this->nombre = $nombre;
        $this->clave = $clave;
        $this->mail = $mail;
    }

    public function toString() : string 
    {
        return "$this->nombre - $this->mail - $this->clave";
    }

    public static function agregar(Usuario $usuario) : bool
    {
        $rta = false;

        $ar = fopen("./archivos/usuarios.csv", "a"); //A - append

		$cant = fwrite($ar, "{$usuario->nombre},{$usuario->mail},{$usuario->clave}\r\n");

		if($cant > 0)
		{
            $rta = true;			
		}

		fclose($ar);

        return $rta;
    }

    public static function leer() : string
    {
        $ar = fopen("./archivos/usuarios.csv", "r");

        $mensaje =  "<ul>";
        while(!feof($ar))
        {
            $registro = fgets($ar);
            if($registro != "")
            {
                $mensaje .= "<li>" . $registro . "</li>";	
            }  
        }
        $mensaje .= "</ul>";

        fclose($ar);

        return $mensaje;
    }

    public static function listar() : string
    {
        $mensaje =  "";
        $usuarios = array();

        $ar = fopen("./archivos/usuarios.csv", "r");

        while(!feof($ar))
        {
            $registro = fgets($ar);
            $arrayRegistro = explode(",", $registro);

            if($arrayRegistro[0] != "")
            {
                $nombre = $arrayRegistro[0];
                $clave = $arrayRegistro[1];
                $mail = $arrayRegistro[2];

                $usuario = new Usuario($nombre,$clave,$mail);
                array_push($usuarios, $usuario);
            }
        }

        fclose($ar);

        if(count($usuarios) > 0)
        {
            $mensaje =  "<ul>";
            foreach($usuarios as $user)
            {
                $mensaje .= "<li>" . $user->toString() . "</li>";
            }
            $mensaje .= "</ul>";
        }

        return $mensaje;
    }

    public static function logear(string $clave, string $mail) : string  
    {
        $rta = "";
        $seEncuentra = false;

        if(isset($clave) && isset($mail))
        {
            $ar = fopen("./archivos/usuarios.csv", "r"); 

            while(!feof($ar))
            {
                $registro = fgets($ar);
                $array_registro = explode(",", $registro);
    
                $array_registro[0] = trim($array_registro[0]);
    
                if($array_registro[0] != "")
                {
                    $nombreRegistro = trim($array_registro[0]);
                    $mailRegistro = trim($array_registro[1]);
                    $claveRegistro = trim($array_registro[2]);
    
                    if ($mail == $mailRegistro) 
                    {
                        $seEncuentra = true;
                        if($clave == $claveRegistro)
                        {
                            $rta = "Verificado";

                        }
                        else 
                        {
                            $rta = "Error en la clave";
                        }
                        break;
                    }
                }

                if(!$seEncuentra)
                {
                    $rta = "Usuario no registrado";
                }
            }
        }
        return $rta;
    }


}

?>