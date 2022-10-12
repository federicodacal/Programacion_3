<?php 

namespace Dacal\Federico;

class Producto 
{
    public string $nombre;
    public string $origen;

    public function __construct(string $nombre, string $origen)
    {
        $this->nombre = $nombre;
        $this->origen = $origen;
    }

    public function toJson() : string 
    {
        return json_encode(array("nombre"=>$this->nombre,"origen"=>$this->origen));
    }

    public function guardarJSON(string $path) : string
    {
        $exito = false;
        $mensaje = "Hubo un problema";

        $lista = Producto::traerJSON($path);

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
            $mensaje = "Producto {$this->nombre} guardado con exito";
        }

        fclose($ar);

        return json_encode(array("exito"=>$exito, "mensaje"=>$mensaje));
    }

    public static function traerJSON(string $path) : array
    {
        $productos = array();

        if(file_exists($path))
        {
            $ar = fopen($path, "r");

            $filesize = filesize($path);

            if($filesize > 0)
            {
                $json = fread($ar, $filesize);

                $productosJson = json_decode($json, true);

                if(isset($productosJson))
                {
                    foreach($productosJson as $p)
                    {
                        array_push($productos, new Producto($p["nombre"], $p["origen"]));
                    } 
                }
            }
    
            fclose($ar);
        }

        return $productos;
    }

    public function equals(Producto $producto) : bool 
    {
        return $this->nombre == $producto->nombre && $this->origen == $producto->origen;
    }

    private static function verificarCantidadPorOrigen(string $origen, array $lista) : int 
    {
        $total = 0;
        foreach($lista as $producto)
        {
            if($producto->origen == $origen)
            {
                $total++;
            }
        }
        return $total;
    }

    private static function verificarRepeticionesPorNombre(array $lista) : string 
    {
        $nombres = array();
        $productoMasRepetido = "";
        $cantidadRepeticiones = 0;
        $max = 0;

        foreach($lista as $producto)
        {
            array_push($nombres, $producto->nombre);
        }

        $nombres_repeticiones = array_count_values($nombres);

        foreach($nombres_repeticiones as $key => $value)
        {
            $cantidadRepeticiones = $value;

            if($cantidadRepeticiones > $max)
            {
                $max = $cantidadRepeticiones;
                $productoMasRepetido = $key;
            }
        }

        return "El producto mas repetido es $productoMasRepetido con $max unidades";
    }

    public static function verificarProductoJSON(Producto $producto) : string
    {
        $exito = false;
        $mensaje = "Hubo un problema";

        $file = './archivos/productos.json';

        if(file_exists($file))
        {
            $lista = Producto::traerJSON($file);
        
            if(isset($lista))
            {
                foreach($lista as $item)
                {
                    if($producto->equals($item))
                    {
                        $exito = true;                       
                        break;
                    }
                }
            }

            if($exito)
            {
                $cantidad = Producto::verificarCantidadPorOrigen($producto->origen, $lista);
                $mensaje = "Hay $cantidad productos registrados con el origen $producto->origen";
            }
            else 
            {
                $mensaje = Producto::verificarRepeticionesPorNombre($lista);
            }
        }

        return json_encode(array("exito"=>$exito, "mensaje"=>$mensaje));
    }
}

?>