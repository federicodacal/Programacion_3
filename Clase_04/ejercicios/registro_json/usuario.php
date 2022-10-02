<?php 

class Usuario 
{
    public string $nombre;
    public string $clave;
    public string $mail;
    public int $id;
    public string $fechaRegistro;
    public string $foto; 

    public function __construct(string $nombre, string $clave, string $mail, string $foto = null, string $fechaRegistro = null, int $id = null)
    {
        if($id == null)
        {
            $this->id = rand(1,1000);
        }
        else 
        {
            $this->id = $id;
        }

        $this->nombre = $nombre;
        $this->clave = $clave;
        $this->mail = $mail;

        if($fechaRegistro == null)
        {
            $this->fechaRegistro = date("Y-m-d H:i:s");
        }
        else 
        {
            $this->fechaRegistro = $fechaRegistro;
        }

        if($foto == null)
        {
            $this->foto = "./archivos/fotos/default.png";
        }
        else 
        {
            $this->foto = $foto;
        }
    }

    public function toString() : string 
    {
        return "ID: {$this->id} Nombre: {$this->nombre} - Mail: {$this->mail} - Clave: {$this->clave} - Registro: {$this->fechaRegistro} Foto: {$this->foto}";
    }

    public static function agregar(Usuario $usuario) : bool
    {
        return Usuario::guardarTxt($usuario) && Usuario::guardarJson($usuario);
    }

    private static function guardarTxt(Usuario $usuario) : bool
    {
        $rta = false;

        $ar = fopen("./archivos/usuarios.csv", "a"); //A - append

		$cant = fwrite($ar, "{$usuario->nombre},{$usuario->mail},{$usuario->clave},{$usuario->id},{$usuario->fechaRegistro},{$usuario->foto}\r\n");

		if($cant > 0)
		{
            $rta = true;			
		}

		fclose($ar);

        return $rta;
    }

    private static function guardarJson(Usuario $usuario) : bool
    {
        $rta = false;

        $lista = Usuario::traerUsuariosJson();

        $ar = fopen("./archivos/usuarios.json", "w");

        if(isset($lista))
        {            
            array_push($lista, $usuario);
        }
        else 
        {
            $lista = array();
        }

        $json = json_encode($lista);

        $cant = fwrite($ar, $json);

        if($cant > 0)
        {
            echo "JSON!";
            $rta = true;
        }

        fclose($ar);

        return $rta;
    }

    public static function leer() : string
    {
        $ar = fopen("./archivos/usuarios.csv", "r");

        $mensaje =  "";
        while(!feof($ar))
        {
            $registro = fgets($ar);
            if($registro != "")
            {
                $mensaje .= $registro;	
            }  
        }

        fclose($ar);

        return $mensaje;
    }

    public static function traerUsuariosJson() : array 
    {
        $usuarios = array();

        $ar = fopen("./archivos/usuarios.json", "r");

        $filesize = filesize("./archivos/usuarios.json");

        if($filesize > 0)
        {
            $json = fread($ar, $filesize);

            $usuariosJson = json_decode($json, true);

            if(isset($usuariosJson))
            {
                foreach($usuariosJson as $usuario)
                {
                    array_push($usuarios, new Usuario($usuario["nombre"], $usuario["clave"], $usuario["mail"], $usuario["foto"], $usuario["fechaRegistro"], $usuario["id"]));
                }
        
            }
        }

        fclose($ar);

        return $usuarios;
    }

    public static function leerJson() : string 
    {   
        $usuarios = Usuario::traerUsuariosJson();
        $mensaje = "No hay usuarios";

        if(count($usuarios) > 0)
        {
            $mensaje =  "<ul>";
            foreach($usuarios as $user)
            {
                $mensaje .= "<li>" . $user->toString();
                $mensaje .= "<br><img src=". $user->foto . "width=100 height=80>";
                $mensaje .= "</li>";
                $mensaje .= "<hr>";
            }
            $mensaje .= "</ul>";
        }

        var_dump($usuarios);

        return $mensaje;
    }

    public static function listar() : string
    {
        $mensaje = "";
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
                $id = $arrayRegistro[3];
                $fecha = $arrayRegistro[4];
                $foto = $arrayRegistro[5];

                $usuario = new Usuario($nombre,$clave,$mail,$foto,$fecha,$id);
                array_push($usuarios, $usuario);
            }
        }

        fclose($ar);

        if(count($usuarios) > 0)
        {
            $mensaje =  "<ul>";
            foreach($usuarios as $user)
            {
                $mensaje .= "<li>" . $user->toString();
                $mensaje .= "<br><img src=". $user->foto . "width=100 height=80>";
                $mensaje .= "</li>";
                $mensaje .= "<hr>";
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

            fclose($ar);
        }

        return $rta;
    }
}

?>