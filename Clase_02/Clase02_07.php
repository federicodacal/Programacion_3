<?php
require_once "namespace/varios.php";

use MiNamespace\ {
    Clase,
    //function funcion,
    const CONSTANTE
};

//use function MiNamespace\funcion;

//solo uno
//use MiNamespace\Clase;

$obj_1 = new Clase();

echo Clase::test() . "<br/>";

$valor = CONSTANTE;

echo "<br/>" . $valor . "<br/>";

//echo funcion();

echo MiNamespace\funcion();

//echo funcion();

//Con alias
use MiNamespace\Clase as UnaClase;

echo UnaClase::test();
