<?php

require_once "../registro_json/usuario.php";
require_once "./producto.php";

class Venta 
{
    public int $id;
    public int $idUsuario;
    public string $codigoProducto;
    public string $fechaVenta;
    public int $stock;
    public bool $exito;

    public function __construct(int $idUsuario, string $codigoProducto, int $stock, bool $exito = false, int $id = null, string $fechaVenta = null)
    {
        if($id == null)
        {
            $this->id = rand(1,1000);
        }
        else 
        {
            $this->id = $id;
        }

        if($fechaVenta == null)
        {
            $this->fechaVenta = date("Y-m-d H:i:s");
        }
        else 
        {
            $this->fechaVenta = $fechaVenta;
        }

        $this->idUsuario = $idUsuario;
        $this->codigoProducto = $codigoProducto;
        $this->stock = $stock;

        $this->exito = $exito;

        $this->vender();
    }

    public function vender()
    {
        $listaProductos = Producto::traerProductos();
        $listaUsuarios = Usuario::traerUsuariosJson();

        $existeUsuario = false;

        if(isset($listaProductos) && isset($listaUsuarios) && count($listaProductos) > 0 && count($listaUsuarios) > 0)
        {
            foreach($listaUsuarios as $user)
            {
                if($user->id == $this->idUsuario)
                {
                    $existeUsuario = true;
                    break;
                }
            }

            foreach($listaProductos as $prod)
            {
                if($existeUsuario && $prod->codigoDeBarra == $this->codigoProducto && $prod->stock > 0)
                {
                    if($prod->stock -= $this->stock >= 0)
                    {
                        $prod->stock -= $this->stock;
                        Producto::agregar($prod);
                        $this->exito = true;
                        if(Venta::guardarJson($this))
                        {
                            echo "Vendido!";
                        }
                    }
                    break;
                }
            }
        }
    }

    private static function guardarJson(Venta $venta) : bool
    {
        $rta = false;

        $lista = venta::traerVentasJson();

        $ar = fopen("./archivos/ventas.json", "w");

        if(isset($lista))
        {
            array_push($lista, $venta);
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

    public static function traerVentasJson() : array 
    {
        $ventas = array();

        $ar = fopen("./ventas.json", "r");

        $filesize = filesize("./ventas.json");

        if($filesize > 0)
        {
            $json = fread($ar, $filesize);

            $ventasJson = json_decode($json, true);

            if(isset($ventasJson))
            {
                foreach($ventasJson as $venta)
                {
                    array_push($ventas, new Venta($venta["idUsuario"], $venta["codigoProducto"], $venta["stock"], $venta["exito"], $venta["id"], $venta["fechaVenta"]));
                }
        
            }
        }

        fclose($ar);

        return $ventas;
    }

}

?>