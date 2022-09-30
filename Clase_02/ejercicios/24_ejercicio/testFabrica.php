<?php 

require_once "fabrica.php";

$fabrica = new Fabrica("Coca-Cola");

$op1 = new Operario(1000, "Jimenez", "Hector", 2000);
$op2 = new Operario(1000, "Jimenez", "Hector", 3000);
$op3 = new Operario(1001, "Gomez", "Omar", 4000);
$op4 = new Operario(1002, "Oca", "Pepe", 1500);
$op5 = new Operario(1003, "Juez", "Franco", 1750);
$op6 = new Operario(1004, "Rodriguez", "Luis", 2000);
$op7 = new Operario(1005, "Muñoz", "Juan", 2200);

$fabrica->add($op1);
$fabrica->add($op2);
$fabrica->add($op3);
$fabrica->add($op4);
$fabrica->add($op5);
$fabrica->add($op6);
$fabrica->add($op7);

echo $fabrica->mostrar();

echo "<br><br>";

$op1->setAumentarSalario(20);
$op6->setAumentarSalario(100);

echo $fabrica->mostrar();

echo "<br><br>";

$fabrica->remove($op1);
$fabrica->remove($op3);
$fabrica->remove($op4);

echo $fabrica->mostrar();

?>