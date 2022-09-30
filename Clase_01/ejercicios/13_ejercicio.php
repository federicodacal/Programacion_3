<?php 

/*
Aplicación No 13 (Arrays asociativos II)
Cargar los tres arrays con los siguientes valores y luego ‘juntarlos’ en uno. Luego mostrarlo por
pantalla.
“Perro”, “Gato”, “Ratón”, “Araña”, “Mosca”
“1986”, “1996”, “2015”, “78”, “86”
“php”, “mysql”, “html5”, “typescript”, “ajax”
Para cargar los arrays utilizar la función array_push. Para juntarlos, utilizar la función
array_merge.
*/

$vec1 = array();
$vec2 = array();
$vec3 = array();
$vec4 = array();

array_push($vec1, "Perro", "Gato", "Araña", "Mosca");
array_push($vec2, "1986", "1996", "2015", "78", "86");
array_push($vec3, "php", "mysql", "html5", "typescript", "ajax");

$vec4 = array_merge($vec1, $vec2, $vec3);

foreach($vec4 as $item)
{
    echo $item . "<br>";
}

?>