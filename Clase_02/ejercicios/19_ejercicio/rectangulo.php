<?php

namespace Figuras;

require_once 'figura_geometrica.php';

class Rectangulo extends FiguraGeometrica
{
    private float $l1;
    private float $l2;

    public function __construct(float $l1, float $l2)
    {
        parent::__construct();
        $this->l1 = $l1;
        $this->l2 = $l2;
        $this->calcularDatos();
    }

    protected function calcularDatos() : void
    {
        $this->perimetro = $this->l1*2 + $this->l2*2;
        $this->superficie = $this->l1 * $this->l2;
    }
    
    public function dibujar() : string 
    {
        $dibujo = "";
        for($i = 0; $i < $this->l1; $i++)
        {
            for($j = 0; $j < $this->l2; $j++)
            {
                $dibujo .= "*";
            }
            $dibujo .= "<br>";
        }
        return $dibujo;
    }

    public function toString() : string 
    {
        $mensaje = parent::toString();
        $mensaje .= $this->dibujar();
        return $mensaje;
    }
}

?>