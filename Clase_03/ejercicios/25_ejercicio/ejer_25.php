<?php 
/*
Aplicación No 25 (Contar letras)
Se quiere realizar una aplicación que lea un archivo (../misArchivos/palabras.txt) y ofrezca
estadísticas sobre cuantas palabras de 1, 2, 3, 4 y más de 4 letras hay en el texto. No tener en
cuenta los espacios en blanco ni saltos de líneas como palabras.
Los resultados mostrarlos en una tabla.
*/

$ar = fopen("../misArchivos/palabras.txt", "r");

$texto = fread($ar, filesize("../misArchivos/palabras.txt"));

echo $texto;
echo "<br><br>";

$arrayPalabras = preg_split('/[:;, .\n\r]/', $texto);

foreach($arrayPalabras as $palabra)
{
    echo $palabra . "<br>";
}
echo "<br><br>";

$contPalabraUnaLetra = 0;
$contPalabraDosLetras = 0;
$contPalabraTresLetras = 0;
$contPalabraCuatroLetras = 0;
$contPalabraMasDeCuatroLetras = 0;

foreach($arrayPalabras as $palabra)
{
    if(strlen($palabra) == 1)
    {
        $contPalabraUnaLetra++;
    }
    else if(strlen($palabra) == 2)
    {
        $contPalabraDosLetras++;
    }
    else if(strlen($palabra) == 3)
    {
        $contPalabraTresLetras++;
    }
    else if(strlen($palabra) == 4)
    {
        $contPalabraCuatroLetras++;
    }
    else if(strlen($palabra > 4))
    {
        $contPalabraMasDeCuatroLetras++;
    }
}

echo "Palabras 1 Letra: $contPalabraUnaLetra<br>";
echo "Palabras 2 Letras: $contPalabraDosLetras<br>";
echo "Palabras 3 Letras: $contPalabraTresLetras<br>";
echo "Palabras 4 Letras: $contPalabraCuatroLetras<br>";
echo "Palabras Más de 4 Letras: $contPalabraMasDeCuatroLetras<br>";

fclose($ar);

?>