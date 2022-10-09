<?php 

namespace Dacal\Federico;

require_once "./clases/neumatico.php";

use PDO;

class Neumatico 
{
    protected string $marca;
    protected string $medidas;
    protected float $precio;

    public function __construct(string $marca = "", string $medidas = "", float $precio = 0)
    {
        $this->marca = $marca;
        $this->medidas = $medidas;
        $this->precio = $precio;
    }

    public function getMarca() : string 
    {
        return $this->marca;
    }

    public function getMedidas() : string 
    {
        return $this->medidas;
    }

    public function getPrecio() : float 
    {
        return $this->precio;
    }

    public function toJSON()
    {
        return json_encode(array("marca"=>$this->getMarca(), "medidas"=>$this->getMedidas(), "precio"=>$this->getPrecio()));
    }

    public function equals(Neumatico $n)
    {
        return $this->medidas === $n->medidas && $this->marca === $n->marca;
    }

    public function guardarJSON(string $path) : string
    {
        $exito = false;
        $mensaje = "No guardado";

        $lista = Neumatico::traerJSON($path);

        $ar = fopen($path, "w");

        if(isset($lista))
        {            
            array_push($lista, $this);
        }
        else 
        {
            $lista = array();
        }
        
        if(count($lista) > 0)
        {
            $json = "[";
            for($i = 0; $i < count($lista); $i++)
                {
                    $json .= $lista[$i]->toJson();

                    if($i < count($lista)-1)
                    {
                        $json .= ",\r\n";
                    }
                }
            $json .= "]";

            $cant = fwrite($ar, $json);
        }
        
        if($cant > 0)
        {
            $exito = true;
            $mensaje = "Neumatico {$this->marca} guardado con éxito";
        }

        fclose($ar);

        return json_encode(array("exito"=>$exito, "mensaje"=>$mensaje));
    }
    
    public static function traerJSON(string $path) : array
    {
        $neumaticos = array();

        if(file_exists($path))
        {
            $ar = fopen($path, "r");

            $filesize = filesize($path);

            if($filesize > 0)
            {
                $json = fread($ar, $filesize);

                $neumaticosJson = json_decode($json, true);

                if(isset($neumaticosJson))
                {
                    foreach($neumaticosJson as $usuario)
                    {
                        array_push($neumaticos, new Neumatico($usuario["marca"], $usuario["medidas"], $usuario["precio"]));
                    } 
                }
            }
    
            fclose($ar);
        }

        return $neumaticos;
    }
    

    public static function calcularPrecioPorMarcaYMedida(string $marca, string $medidas, array $lista) : float
    {
        $total = 0;

        foreach($lista as $neumatico)
        {
            if($neumatico->marca === $marca && $neumatico->medidas === $medidas)
            {
                $total += $neumatico->precio;
            }
        }

        return $total;
    }

    public static function verificarNeumaticoJSON(Neumatico $neumatico) : string
    {
        $exito = false;
        $mensaje = "Hubo un problema";

        $filePath = "./archivos/neumaticos.json";  

        if(file_exists($filePath))
        {
            $lista = Neumatico::traerJSON($filePath);

            if(isset($lista))
            {            
                foreach($lista as $item)
                {
                    if($neumatico->equals($item))
                    {
                        
                        $sumatoria = Neumatico::calcularPrecioPorMarcaYMedida($neumatico->marca, $neumatico->medidas, $lista);
                        
                        $mensaje = "Marca: $neumatico->marca - Medida: $neumatico->medidas - Sumatoria: \$$sumatoria";

                        $exito = true;
                        break;
                    }
                }

                if(!$exito)
                {
                    $mensaje = "No hubo coincidencia";
                }
            }
            else 
            {
                $mensaje = "No hay neumaticos cargados";
            }
        }
        else 
        {
            $mensaje = "No se encontro el archivo: $filePath";
        }      

        return json_encode(array("exito"=>$exito, "mensaje"=>$mensaje));
    }

}

?>