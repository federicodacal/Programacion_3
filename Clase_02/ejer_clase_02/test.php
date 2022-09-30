<?php 

require_once "clases/guarderia.php";

use Animalitos\Mascota;
use Negocios\Guarderia;

$m1 = new Mascota("Pepe", "Perro");
$m2 = new Mascota("Gatito", "Gato", 4);
$m3 = new Mascota("Un Perro", "Perro", 2);
$m4 = new Mascota("Un Perro", "Perro", 3);

echo $m1->toString();
echo "<br>";
echo $m2->toString();
echo "<br>";
echo $m3->toString();
echo "<br>";
echo Mascota::mostrar($m4);
echo "<br>";

if($m1->equals($m2))
    echo "True";
else 
    echo "False";

echo "<br>";

if($m2->equals($m3))
    echo "True";
else 
    echo "False";

echo "<br>";

if($m3->equals($m4))
    echo "True";
else 
    echo "False";

echo "<br>";
echo "<br>";
echo "<br>";

$guarderia = new Guarderia("La guardería de Pancho");

$guarderia->add($m1);
$guarderia->add($m2);
$guarderia->add($m3);
$guarderia->add($m4);

echo $guarderia->toString();
?>