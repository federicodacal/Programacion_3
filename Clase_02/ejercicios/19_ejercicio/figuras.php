<?php 

require_once "rectangulo.php";

use Figuras\{
    Rectangulo
};

$rec1 = new Rectangulo(2,3);
$rec2 = new Rectangulo(5,4);
$rec3 = new Rectangulo(4,12);

echo $rec1->toString();
echo "<br>";
echo $rec2->toString();
echo "<br>";
echo $rec3->toString();
echo "<br>";

?>