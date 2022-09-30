<h1>Clase de Prueba</h1>

<?php

    // Comentario

    /*
    Comentario
    Comentario
    */

    $nombre = "Pepe";
    $edad = 25;
    $sueldo = 5200.65;

    echo "Hola PHP! <br>";

    print("Nombre: $nombre <br>");

    echo "Edad: ", $edad, "<br>";

    printf("Sueldo: $%f<br>", $sueldo); // %f es la máscara para flotante

    // Con el operador '.' (punto) concateno strings.
    echo $sueldo . "<br>";

    $nombre = "Ahora soy Juan Carlos";

    echo "<br>" . $nombre . "<br> <br>";

    $vec = array(1,2,3,4,5,10);
    
    for($i=0; $i < count($vec); $i++)
    {
        echo "<br>", $vec[$i];
    }
    echo "<br>" . "<br>";

    array_push($vec, 11);

    foreach($vec as $valor)
    {
        echo "$valor <br>"; 
    }
    echo "<br>" . "<br>";

    $otroVec = array("uno" => 1, "dos" => 2, "tres" => 3);
    foreach($otroVec as $clave => $valor)
    {
        echo "Clave: $clave" . " | " . "Valor: $valor" . "<br>";
    }
    echo "<br>" . "<br>";

    echo "Array indexado con var_dump <br>";
    var_dump($vec);
    echo "<br>";
    echo "<br>";

    $vec2 = array("Juan"=>22, "Romina"=>20, "Uriel"=>28);
    echo "Array asociativo con var_dump <br>";
    var_dump($vec2);
    echo "<br>";
    
    echo "<br>";
    echo "<br>";
    echo "Comparaciones con ==<br>";
    
    $variable = "8";
    if ($variable == "8")
    {
        echo "Es ocho (string)<br>";
    }
    if ($variable == 8)
    {
        echo "Es ocho (integer)" . " porque compara el contenido y no el tipo de dato<br>";
        // También es una igualdad por más que sean de distinto tipo de dato porque compara el contenido no tipo
    }

    echo "<br/>";
    echo "Comparaciones con ===<br>";
    if ($variable === "8")
    {
        echo "Es ocho (string)<br>";
    }
    if ($variable === 8)
    {
        echo "Es ocho (integer)<br>";
    }

    echo "<br/>";
    $numero = (int)$variable;
    echo "Comparaciones con casteo a int y ===<br>";
    if ($numero === "8")
    {
        echo "Es ocho (string)<br>";
    }
    if ($numero === 8)
    {
        echo "Es ocho (integer)<br>";
    }


    echo "<br> isset: <br>";
    $variableInicializada = ".";

    if(isset($variableInicializada))
    {
        echo $variableInicializada . "<br>";
    }
    else
    {
        echo "No está inicializada";
    }

    echo "<br> isset: <br>";
    $variableNoInicializada;

    if(isset($variableNoInicializada))
    {
        echo $variableNoInicializada . "<br>";
    }
    else
    {
        echo "No está inicializada";
    }
?>