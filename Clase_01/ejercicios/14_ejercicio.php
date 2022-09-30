<?php 

/*
Aplicación No 14 (Arrays de Arrays)
Realizar las líneas de código necesarias para generar un Array asociativo y otro indexado que
contengan como elementos tres Arrays del punto anterior cada uno. Crear, cargar y mostrar los
Arrays de Arrays.
*/

$lapicera1 = array("color" => "azul", "marca" => "bic", "trazo" => "grueso", "precio" => 5); 
$lapicera2 = array("color" => "rojo", "marca" => "faber", "trazo" => "fino", "precio" => 15); 
$lapicera3 = array("color" => "negro", "marca" => "paper", "trazo" => "ultrafino", "precio" => 7);

$lapiceras = array($lapicera1, $lapicera2, $lapicera3);

foreach($lapiceras as $item)
{
    var_dump($item);
    foreach($item as $key => $value)
    {
        echo "$key: $value<br>";
    }
    echo "<br>";
}



?>