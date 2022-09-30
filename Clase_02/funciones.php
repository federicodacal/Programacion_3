<?php
	function saludar()
	{
		echo "Hola Mundo, desde una funci&oacute;n!!!";
	}
	
	function saludar2($nombre)
	{
		echo "Hola ", $nombre;
	}
	
	function saludar3($nombre, $genero = "Masculino")
	{
		$retorno = "Hola $nombre. Tu g&eacute;nero es $genero";
		return $retorno;
	}
