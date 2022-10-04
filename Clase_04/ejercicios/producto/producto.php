<?php 

class Producto 
{
    public string $codigoDeBarra;
    public string $tipo;
    public string $nombre;
    public int $stock;
    public float $precio;
    public int $id;

    public function __construct(string $codigoDeBarra, string $tipo, int $stock, float $precio, string $nombre, int $id = 0)
    {
        $this->codigoDeBarra = $codigoDeBarra;
        $this->tipo = $tipo;
        $this->stock = $stock;
        $this->precio = $precio;
        $this->nombre = $nombre;
        $this->setId($id);
    }

    private function setId(int $id) : void
    {
        if($id == 0)
        {
            $this->id = rand(1,1000);
        }
        else 
        {
            $this->id = $id;
        }
    }

    public function toString() : string 
    {
        return "Tipo: {$this->tipo}, Nombre: {$this->nombre}, Codigo: {$this->codigoDeBarra}, Stock: {$this->stock}, Precio: \${$this->precio}, ID: {$this->id}";
    }

    public static function agregar(Producto $prod) : bool
    {
        return Producto::guardarJson($prod);
    }

    public static function listar() : string 
    {
        $mensaje = "";

        $lista = Producto::traerProductos();

        if(isset($lista) && count($lista) > 0)
        {
            foreach($lista as $prod)
            {
                $mensaje .= $prod->toString() . "\n<br>";
            }
        }
        else 
        {
            $mensaje = "No hay productos";
        }

        return $mensaje;
    }

    public static function guardarJson(Producto $prod) : bool 
    {
        $rta = false;
        $yaSeEncuentra = false;

        $lista = Producto::traerProductos();

        $ar = fopen("./productos.json", "w");

        if(isset($lista))
        {
            foreach($lista as $item)
            {
                if($item->tipo == $prod->tipo && $item->nombre == $prod->nombre)
                {
                    $item->stock += $prod->stock;
                    $item->precio = $prod->precio;
                    $yaSeEncuentra = true;
                }
            }
        
            if(!$yaSeEncuentra) 
            {
                array_push($lista, $prod);
                echo "Ingresado<br>";
            }
            else 
            {
                "Actualizado";
            }
        }
        else 
        {
            $lista = array();
        }

        $json = json_encode($lista);

        $cant = fwrite($ar, $json);

        if($cant > 0)
        {
            $rta = true;
        }

        fclose($ar);

        return $rta;
    }

    public static function traerProductos() : array 
    {
        $productos = array();

        $file = "./productos.json";
        
        if(!file_exists($file))
        {
            $newFile = fopen("./productos.json", "w");

            fclose($newFile);
        }
        
        $ar = fopen("./productos.json", "r");

        $filesize = filesize("./productos.json");

        if($filesize > 0)
        {
            $json = fread($ar, $filesize);

            $productosJson = json_decode($json, true);

            if(isset($productosJson))
            {
                foreach($productosJson as $prod)
                {
                    //public function __construct(string $codigoDeBarra, string $tipo, int $stock, float $precio, string $nombre, int $id = 0)
                    array_push($productos, new Producto($prod["codigoDeBarra"], $prod["tipo"], $prod["stock"], $prod["precio"], $prod["nombre"], $prod["id"]));
                }        
            }
        }
        fclose($ar);

        return $productos;
    }
}

?>