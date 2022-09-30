<?php
require_once "namespace/varios.php";

$obj = new MiNamespace\Clase();

echo MiNamespace\Clase::test() . "<br/>";

echo MiNamespace\funcion();

$valor = MiNamespace\CONSTANTE;

echo "<br/>" . $valor;

echo "<br/>" . "namespace actual: " . __NAMESPACE__; // Indica en que namespace estoy parado


//ERROR

//$otroValor = CONSTANTE; // Undefined

//echo funcion(); // Undefined

//$obj_1 = new Clase(); // Undefined
 