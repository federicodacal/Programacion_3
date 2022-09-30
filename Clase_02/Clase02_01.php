<?php

	saludar();
	echo "<br/>";
		
	saludar2("Juan");
	echo "<br/>";
		
	echo saludar3("Rosa", "Femenino");
	echo "<br/>";

	echo saludar3("Carlos");
	echo "<br/>";

	// TIPADO DÉBIL
	function saludar()
	{
		echo "Hola Mundo, desde una función!!!";
	}
		
	function saludar2($nombre)
	{
		echo "Hola ", $nombre;
	}
		
	function saludar3($nombre, $genero = "Masculino") // valor por defecto
	{
		$retorno = "Hola $nombre Tu g&eacute;nero es $genero";
		return $retorno;
	}
	