<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirigir</title>
</head>
<body>
    <?php

        session_start();

        require_once("./ejercicio_clase/alumno.php");

        use Apellido\Alumno;

        if(isset($_SESSION["legajo"]) && isset($_SESSION["nombre"]) && isset($_SESSION["apellido"]) && isset($_SESSION["foto"])) 
        {
            $legajo = $_SESSION["legajo"];
            $nombre = $_SESSION["nombre"];
            $apellido = $_SESSION["apellido"];
            $foto = $_SESSION["foto"];

            $array = explode(".", $foto);
            $extension = $array[count($array)-1];

            $pathFoto = "./fotos/{$legajo}.{$extension}";

            echo "<h1>Legajo: {$legajo}</h1>";
            echo "<h2>Nombre: {$nombre}</h2>";
            echo "<h2>Apellido: {$apellido}</h1>";
            echo "<h2>Foto: {$pathFoto}</h1>";
            echo "<img src='{$pathFoto}' width=200px<br><hr><br>";
            
            var_dump($_SESSION);

            $alumnos = Alumno::traerAlumnos();
            
            echo "<table>";
            foreach ($alumnos as $alumno) {
                echo "<tr>
                    <td>$alumno->legajo</td>
                    <td>$alumno->nombre</td>
                    <td>$alumno->apellido</td>
                    <td>$alumno->foto</td>
                  </tr>";
              }
              echo" </table>"; 

        }
        else 
        {
            header("Location: ./ejercicio_clase/nexo_poo_foto.php");
        }
        
        session_destroy();
    ?>

</body>
</html>