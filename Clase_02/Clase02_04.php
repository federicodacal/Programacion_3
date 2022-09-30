<?php 
	require "./funciones.php"; // ruta relativa
	// ./ indica que estoy parado en el mismo directorio, en el mismo nivel.
	// ../ indica que voy a subir un nivel.
	// ../../ indica que voy a subir dos niveles.

	include_once "otroArchivo.php";

	saludar();
	echo "<br/>";

	saludar2("Juan");
	echo "<br/>";

	echo saludar3("Rosa", "Femenino");
	echo "<br/>";

	echo saludar3("Carlos");
	echo "<br/>";