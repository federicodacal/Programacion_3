<?php 

require_once "vuelo.php";

$v1 = new Vuelo("Turkish Airlines", 1500, 2);
$v2 = new Vuelo("Lufthansa", 2500, 5);

$p1 = new Pasajero("Perez", "Juan", 55444111, false);
$p2 = new Pasajero("Fulanito", "Cosme", 55444111, false);
$p3 = new Pasajero("Gomez", "Raul", 22331111, false);
$p4 = new Pasajero("Fernandez", "Daniel", 44555111, true);
$p5 = new Pasajero("Rescaldani", "Lucas", 67677677, true);
$p6 = new Pasajero("Rossini", "Marta", 42420420, false);
$p7 = new Pasajero("Del Rio", "Ana", 42420420, false);

$v2->agregarPasajero($p1);
$v2->agregarPasajero($p2);
$v2->agregarPasajero($p3);
$v2->agregarPasajero($p4);
$v2->agregarPasajero($p5);
$v2->agregarPasajero($p6);

$v2->mostrarVuelo();

echo "<br><br>";

$v1->agregarPasajero($p1);
$v1->agregarPasajero($p6);
$v1->agregarPasajero($p7);

$v1->mostrarVuelo();

echo "<br><br>";

$v1 = Vuelo::remove($v1, $p1);

$v1->mostrarVuelo();

echo "<br><br>";

echo "Suma de vuelos: $ " . Vuelo::add($v1, $v2);

?>