<?php 
/*
Aplicación No 26 (Copiar archivos)
Generar una aplicación que sea capaz de copiar un archivo de texto (su ubicación se ingresará
por la página) hacia otro archivo que será creado y alojado en
./misArchivos/yyyy_mm_dd_hh_ii_ss.txt, dónde yyyy corresponde al año en curso, mm
al mes, dd al día, hh hora, ii minutos y ss segundos.
*/

$archivo = isset($_FILES["archivo"]) ? $_FILES["archivo"] : NULL;

if($archivo != NULL)
{
    echo subirTxt($archivo);
}
else 
{
    echo "NULL";
}

function subirTxt(array $archivo) : string 
{
	if(isset($archivo))
	{
		$upload = false;
		
		$nombre = $_FILES["archivo"]["name"];
		$extension = pathinfo($nombre, PATHINFO_EXTENSION);

		if($extension == "txt")
		{
			$path = "../misArchivos/" . date("Y_m_d_h_i_s") . ".txt";
			$upload = true;
		}

		if($upload === false)
		{
			$path = "Extension no valida";
		}
		else 
		{
			move_uploaded_file($_FILES["archivo"]["tmp_name"], $path);
		}
	}
	return $path;
}


?>