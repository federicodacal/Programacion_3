<?php 

/*
Aplicación No 9 (Carga aleatoria)
Definir un Array de 5 elementos enteros y asignar a cada uno de ellos un número (utilizar la
función rand). Mediante una estructura condicional, determinar si el promedio de los números
son mayores, menores o iguales que 6. Mostrar un mensaje por pantalla informando el
resultado.
*/

$vector = array(rand(1,10), rand(1,10), rand(1,10), rand(1,10), rand(1,10));
$suma = 0;

foreach($vector as $num)
{
    echo $num . "<br>";
    $suma += $num;
}
 
$promedio = $suma / count($vector);

echo "<br>La suma es $suma y el promedio es $promedio<br>";

if($promedio > 6)
{
    echo "El promedio es mayor a 6";
}
else
{
    echo "El promedio es menor a 6";
}

?>