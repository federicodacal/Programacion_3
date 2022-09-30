<?php 

/*
Aplicación No 17 (Palabra)
Crear una función que reciba como parámetro un string ($palabra) y un entero ($max). La función
validará que la cantidad de caracteres que tiene $palabra no supere a $max y además deberá
determinar si ese valor se encuentra dentro del siguiente listado de palabras válidas:
“Recuperatorio”, “Parcial” y “Programacion”. Los valores de retorno serán:
1 si la palabra pertenece a algún elemento del listado.
0 en caso contrario.
*/

echo buscarPalabra("Programacion", 30);
echo "<br>";
echo buscarPalabra("Programacion", 8);
echo "<br>";
echo buscarPalabra("Laboratorio", 8);
echo "<br>";
echo buscarPalabra("Parcial", 9);
echo "<br>";

function buscarPalabra(string $palabra, int $max) : int
{
    $retorno = 0;
    $palabras = array("Programacion", "Parcial", "Recuperatorio");

    if(isset($palabra))
    {
        $palabra = ucfirst(trim($palabra));
        if(strlen($palabra) <= $max)
        {
            foreach($palabras as $item)
            {
                if($palabra == $item)
                {
                    $retorno = 1;
                    echo $palabra . "<br>";
                    break;
                }
            }
        }
    }
    return $retorno;
}

?>