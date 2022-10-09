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

    public function __construct(int $idUsuario, string $codigoProducto, int $stock, bool $exito = false, int $id = 0, string $fechaVenta = "")
    {

        $this->idUsuario = $idUsuario;
        $this->codigoProducto = $codigoProducto;
        $this->stock = $stock;
        $this->exito = $exito;
        $this->setId($id);
        $this->setFechaVenta($fechaVenta);
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

    private function setFechaVenta(string $fechaVenta) : void
    {
        if($fechaVenta == null)
        {
            $this->fechaVenta = date("Y-m-d H:i:s");
        }
        else 
        {
            $this->fechaVenta = $fechaVenta;
        }
    }

    public function vender() : void
    {
        $mensaje = "No se pudo realizar la venta. ";

        $listaProductos = Producto::traerProductos();
        $listaUsuarios = Usuario::traerUsuariosJson("../registro_json/archivos/usuarios.json");

        $existeUsuario = false;
        $existeProducto = false;

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

            if($existeUsuario)
            {
                foreach($listaProductos as $prod)
                {
                    if($prod->codigoDeBarra === $this->codigoProducto && $prod->stock > 0)
                    {
                        $stockResultante = $prod->stock - $this->stock;
                        if($stockResultante >= 0)
                        {
                            $prod->stock -= intval($this->stock);

                            if(Venta::guardarJson($this))
                            {
                                Producto::guardarJson($prod, "venta");
                                $mensaje = "Vendido!";
                                break;
                            }
                        }
                        else 
                        {
                            $mensaje .= "No alcanza el stock";
                            break;
                        }
                    }
                }
            }
            else 
            {
                $mensaje .= "No existe el usuario";
            }
        }

        echo $mensaje;
    }

    private static function guardarJson(Venta $venta) : bool
    {
        $rta = false;

        $lista = venta::traerVentasJson();

        $ar = fopen("./ventas.json", "w");

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

        if(!file_exists("./ventas.json"))
        {
            $newFile = fopen("./ventas.json", "w");

            fwrite($newFile, "");

            fclose($newFile);
        }

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