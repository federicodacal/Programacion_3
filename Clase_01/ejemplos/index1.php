<?php

$variable = "cadena";
echo $variable  . "<br>";

$variable = 91218;
echo $variable  . "<br>";

$bool = true;
echo boolval($variable)  . "<br>"; // El navegador lo interpreta como 1

$variable = false;
echo boolval($variable)  . "<br>"; // El navegador lo interpreta como 'nada'

$variable = NULL;

if($variable == false){
    echo "es false"  . "<br>";
}
if($variable == ""){
    echo "es ''"  . "<br>";
}
if($variable == 0){
    echo "es 0"  . "<br>";
}

if($variable === false){
    echo "es false"  . "<br>";
}
else{
    echo "no es false idéntico"  . "<br>";
}
if($variable === ""){
    echo "es ''"  . "<br>";
}
else{
    echo "no es '' idéntico"  . "<br>";
}
if($variable === 0){
    echo "es 0"  . "<br>";
}
else{
    echo "no es 0 idéntico"  . "<br>";
}


/*
$vec = array(1,2,"3",4);

//echo $vec; //NO SE PUEDE!!!
//var_dump($vec);
//var_dump($variable);

$vec[4] = "valor cadena";
$vec[5] = false; // booleano
$vec[6]=8;
var_dump($vec);


for ($i=0; $i < count($vec); $i++) { 
   
    echo $vec[$i] . "<br>";
}

foreach ($vec as $item ) {
    echo $item . "<br>";
}
*/


/*
$vec_asoc = array("uno" => 1, "dos" => 2);
$vec_asoc["tres"] = 3;

foreach ($vec_asoc as $item ) {
    echo $item . "<br>";
}

echo $vec_asoc["dos"];

array_push($vec, 88);

foreach ($vec as $item ) {
    echo $item . "<br>";
}

var_dump($vec);

*/

echo "<br>";

enum Enumerado{
    case Uno;
    case Dos;
    case Tres;
}

$mi_enum = Enumerado::Uno;

if ($mi_enum === Enumerado::Uno) {
    echo "es Enumerado::Uno idéntico";
}

