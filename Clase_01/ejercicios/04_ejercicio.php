<?php 

/*
Aplicación No 4 (Sumar números)
Confeccionar un programa que sume todos los números enteros desde 1 mientras la suma no
supere a 1000. Mostrar los números sumados y al finalizar el proceso indicar cuantos números
se sumaron.
*/

$suma = 0;
$cantidadNumeros;

for($i = 1; $suma+$i < 1000; $i++)
{
    echo "$suma + $i = ";
    $suma += $i;
    echo $suma . "<br>";
    $cantidadNumeros = $i;
}

echo "Se sumaron $cantidadNumeros números";

?>