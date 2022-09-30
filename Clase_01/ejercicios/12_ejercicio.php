<?php 
/*
Aplicación No 12 (Arrays asociativos)
Realizar las líneas de código necesarias para generar un Array asociativo $lapicera, que
contenga como elementos: ‘color’, ‘marca’, ‘trazo’ y ‘precio’. Crear, cargar y mostrar tres
lapiceras.
*/

$lapicera1 = array("color" => "azul", "marca" => "bic", "trazo" => "grueso", "precio" => 5); 
$lapicera2 = array("color" => "rojo", "marca" => "faber", "trazo" => "fino", "precio" => 15); 
$lapicera3 = array("color" => "negro", "marca" => "paper", "trazo" => "ultrafino", "precio" => 7);

$lapiceras = array($lapicera1, $lapicera2, $lapicera3);

var_dump($lapiceras);

foreach($lapiceras as $item)
{
    foreach($item as $key => $value)
    {
        echo "$key: $value<br>";
    }
    echo "<br>";
}

?>