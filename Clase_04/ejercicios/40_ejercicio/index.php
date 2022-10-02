<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table>
        <tr>
            <td>
                <a href="./index.php?img=img/1.jpg"><img width="100px" src="./img/1.jpg"></a>
            </td>
        </tr>
        <tr>
            <td>
                <a href="./index.php?img=img/2.jpg"><img width="100px" src="./img/2.jpg"></a>
            </td>
        </tr>
        <tr>
            <td>
                <a href="./index.php?img=img/3.jpg"><img width="100px" src="./img/3.jpg"></a>
            </td>
        </tr>
    </table>
</body>
</html>

<?php
/*
Aplicación No 40 (Generar Tabla de Imágnes)
Generar una tabla que posea fotos en un tamaño de 100x100 píxeles y que al pulsar se
muestre la foto en su tamaño original en una página distinta (agregarle un link para poder
volver a la página de inicio).
*/
if (isset($_GET["img"])) 
{
    session_start();
    $_SESSION["img"] = $_GET["img"];
    header("location: img.php");
}

?>