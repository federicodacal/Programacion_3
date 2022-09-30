<?php 

/*
Aplicación No 18 (Par e impar)
Crear una función llamada esPar que reciba un valor entero como parámetro y devuelva TRUE si
este número es par ó FALSE si es impar.
Reutilizando el código anterior, crear la función EsImpar.
*/

echo "2 es par? " . (int)esPar(2) . "<br>"; 
echo "2 es impar? " . (int)esImpar(2) . "<br>"; 
echo "3 es par? " . (int)esPar(3) . "<br>"; 
echo "3 es impar? " . (int)esImpar(3) . "<br>"; 

function esPar(int $num) : bool
{
    if($num % 2 == 0)
    {
        return true;
    }
    return false;
}

function esImpar(int $num) : bool
{
    return !esPar($num);
}

?>