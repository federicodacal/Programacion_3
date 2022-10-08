<?php
    session_start();

    $img = $_SESSION["img"];

    echo "<img src='".$img."'>";

    echo "<br><a href='./index.php'>Volver</a>";
?>