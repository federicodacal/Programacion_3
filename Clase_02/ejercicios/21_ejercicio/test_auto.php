<?php 

/*
Crear dos objetos “Auto” de la misma marca y distinto color.
Crear dos objetos “Auto” de la misma marca, mismo color y distinto precio.
Crear un objeto “Auto” utilizando la sobrecarga restante.
Utilizar el método “AgregarImpuesto” en los últimos tres objetos, agregando $ 1500 al
atributo precio.
Obtener el importe sumado del primer objeto “Auto” más el segundo y mostrar el resultado
obtenido.
Comparar el primer “Auto” con el segundo y quinto objeto e informar si son iguales o no.
Utilizar el método de clase “MostrarAuto” para mostrar cada los objetos 
*/

require_once "auto.php";

$a1 = new Auto("Fiat", "Rojo");
$a2 = new Auto("Fiat", "Blanco", 50);

$a3 = new Auto("Peugeot", "Gris", 100);
$a4 = new Auto("Peugeot", "Gris", 165.5);

$a5 = new Auto("Toyota", "Negro", 200, new DateTime('2020-10-10'));

$a3->agregarImpuestos(1500);
$a4->agregarImpuestos(1500);
$a5->agregarImpuestos(1500);

echo Auto::add($a1, $a2) . "<br>";
echo Auto::add($a3, $a4) . "<br>";

echo $a1->equals($a2) ? "True" : "False";
echo "<br>";
echo $a1->equals($a5) ? "True" : "False";
echo "<br>";
echo "<br>";

echo Auto::mostrarAuto($a1) . "<br>";
echo Auto::mostrarAuto($a2) . "<br>";
echo Auto::mostrarAuto($a3) . "<br>";
echo Auto::mostrarAuto($a4) . "<br>";
echo Auto::mostrarAuto($a5) . "<br>";

?>