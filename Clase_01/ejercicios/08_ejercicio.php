<?php 

/*
Aplicación No 8 (Números en letras)
Realizar un programa que en base al valor numérico de la variable $num, pueda mostrarse por
pantalla, el nombre del número que tenga dentro escrito con palabras, para los números entre
el 20 y el 40.
*/

$vecNums = array(20 => "veinte", 21 => "veintiuno", 22 => "veintidós", 23 => "veintitrés", 24 => "veinticuatro", 25 => "veinticuatro", 26 => "veintiseis", 27 => "veintisiete", 28 => "veintiocho", 29 => "veintinueve", 30 => "treinta", 31 => "treinta y uno", 32 => "treinta y dos", 33 => "treinta y tres", 34 => "treinta y cuatro", 35 => "treinta y cinco", 36 => "treinta y seis", 37 => "treinta y siete", 38 => "treinta y ocho", 39 => "treinta y nueve", 40 => "cuarenta");

$num = 31;

if(isset($num))
{
    foreach($vecNums as $key => $value)
    {
        if($num == $key)
        {
            echo $value;
            break;
        }
    }
}



?>