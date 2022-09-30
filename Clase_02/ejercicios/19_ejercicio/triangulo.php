<?php 

namespace Figuras;

require_once 'figura_geometrica.php';

class Triangulo extends FiguraGeometrica
{
    private float $altura;
    private float $base;

    public function __construct(float $altura, float $base)
    {
        parent::__construct();
        $this->altura = $altura;
        $this->base = $base;
        $this->calcularDatos();
    }

    protected function calcularDatos() : void 
    {
        $this->perimetro = $this->base + $this->calcularLado()*2;
        $this->superficie = ($this->altura * $this->base) / 2;
    }

    private function calcularLado() : float
    {
        return sqrt($this->altura + $this->base/2);
    }

    public function dibujar() : string 
    {
        return "";
    }

    public function toString() : string 
    {
        $mensaje = parent::toString();
        $mensaje .= $this->dibujar();
        return $mensaje;
    }
}


?>