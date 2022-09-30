<?php 

require_once "garage.php";

$a2 = new Auto("Fiat", "Blanco", 50);
$a3 = new Auto("Peugeot", "Gris", 100);
$a4 = new Auto("Peugeot", "Gris", 165.5);

$garage = new Garage("Mi Garage", 5);

echo $garage->mostrarGarage();

$garage->add($a2);
$garage->add($a3);
$garage->add($a4);

echo "<br><br>";

echo $garage->mostrarGarage();

echo "<br><br>";

$a5 = new Auto("Toyota", "Blanco", 2000);

if($garage->equals($a5))
{
    echo "Está el auto " . Auto::mostrarAuto($a5);
}
else 
{
    echo "No está el auto " . Auto::mostrarAuto($a5);
}

echo "<br>";

if($garage->equals($a2))
{
    echo "Está el auto " . Auto::mostrarAuto($a2);
}
else 
{
    echo "No está el auto " . Auto::mostrarAuto($a2);
}

echo "<br><br>";

if($garage->remove($a3))
{
    echo "Se removió el auto " . Auto::mostrarAuto($a3);
}

echo "<br><br>";

echo $garage->mostrarGarage();

?>