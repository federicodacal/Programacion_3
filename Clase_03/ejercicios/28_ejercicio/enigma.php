<?php 

class Enigma 
{
    public static function Encriptar(string $texto, string $path) : string 
    {
        $encriptado = false;
        if(isset($texto))
        {
            for($i=0; $i < strlen($texto); $i++)
            {
                $char = ord($texto[$i])+200;
                $encriptado .= chr($char);
            }

            $ar = fopen($path, "w"); 

            $cant = fwrite($ar, $encriptado);
            
            if($cant > 0)
            {
                $encriptado = true;
                echo "Escritura EXITOSA <br>";	
            }

		    fclose($ar);
        }
        return $encriptado;
    }

    public static function Desencriptar(string $path) : string 
    {
        $desencriptado = "";
        if(isset($path))
        {
            $ar = fopen($path, "r");

            $texto = fread($ar, filesize($path));
    
            fclose($ar);

            for($i=0; $i < strlen($texto); $i++)
            {
                $char = ord($texto[$i])-200;
                $desencriptado .= chr($char);
            }
        }
        return $desencriptado;
    }
}

?>