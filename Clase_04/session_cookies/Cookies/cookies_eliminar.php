<?php

setcookie("TestCookie1", "",time()-1);
setcookie("cookieAsoc[uno]", "",time()-1);

echo "<br/>Despu&eacute;s de eliminar...<br/>";

var_dump($_COOKIE);

?>

<a href="../index.html" >Volver al Inicio</a>