<?php
	require_once "funciones.php"; // Uso require_once para evitar Fatal error: Cannot redeclare saludar() debido a que tambien hago require de funciones.php en Clase02_04

	$variable = "Mensaje desde otro archivo .PHP mostrado en: " . date("d-m-Y H:i:s");
	
	echo "<br>" . $variable . "<br><br>";

	saludar();