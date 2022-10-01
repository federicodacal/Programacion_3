<?php 
/*
Aplicación No 27 (Copiar archivos invirtiendo su contenido)
Modificar el ejercicio anterior para que el contenido del archivo se copie de manera invertida,
es decir, si el archivo origen posee ‘Hola mundo’ en el archivo destino quede ‘odnum aloH’.
*/

$archivo = isset($_FILES["archivo"]) ? $_FILES["archivo"] : NULL;

$texto = leerTxt($archivo);
if($texto != null)
{
    $textoInvertido = strrev($texto);

    if(subirTxt($archivo, $textoInvertido) != false)
    {
        echo $textoInvertido . "<br>";
    }
    else 
    {
        echo "false <br>";
    }
}


function leerTxt(array $archivo) : string
{
    $texto = "";
    if(isset($archivo))
    {
        $filename = $_FILES["archivo"]["tmp_name"];
		//$extension = pathinfo($filename, PATHINFO_EXTENSION);
        $ar = fopen($filename, "r");
    
        $texto = fread($ar, filesize($filename));
    
        fclose($ar);
    }
    return $texto;
}

function subirTxt(array $archivo, string $texto) : bool 
{
    $ok = false;
    if(isset($archivo))
    {
		$ar = fopen("../misArchivos/" . date("Y_m_d_h_i_s"). ".txt", "w"); 

		$cant = fwrite($ar, $texto);
		
		if($cant > 0)
		{
			echo "Escritura EXITOSA <br>";
            $ok = true;			
		}
		fclose($ar);
    }
    return $ok;
}

?>