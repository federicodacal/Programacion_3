<?php 

/*
Aplicación No 16 (Invertir palabra)
Realizar el desarrollo de una función que reciba un Array de caracteres y que invierta el orden de las
letras del Array.
Ejemplo: Se recibe la palabra “HOLA” y luego queda “ALOH”.
*/

$cadena = array('H','O','L','A');

$retorno = invertirCadena($cadena);

var_dump($cadena);

echo "<br>";

var_dump($retorno);

function invertirCadena(array $cadena) : array
{
    return array_reverse($cadena);
}

?>