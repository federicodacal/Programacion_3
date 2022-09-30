<?php 

/*
Aplicación No 10 (Mostrar impares)
Generar una aplicación que permita cargar los primeros 10 números impares en un Array.
Luego imprimir (utilizando la estructura for) cada uno en una línea distinta (recordar que el
salto de línea en HTML es la etiqueta <br/>). Repetir la impresión de los números utilizando
las estructuras while y foreach.
*/

$cont = 0;
$num = 0;
$arrayNums = array();

do
{
    if($num % 2 != 0)
    {
        $cont++;
        array_push($arrayNums, $num);
    }
    $num++;

}while($cont < 10);

echo "Con for <br>";
for($i = 0; $i < count($arrayNums); $i++)
{
    echo $arrayNums[$i] . "<br>";
}

echo "Con foreach <br>";
foreach($arrayNums as $numero)
{
    echo $numero . "<br>";
}

echo "Con while <br>";

$j=0;
while($j < count($arrayNums))
{
    echo $arrayNums[$j] . "<br>";
    $j++;
}


?>