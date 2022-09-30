<?php

/*
Aplicación No 19 (Figuras geométricas)
La clase FiguraGeometrica posee: todos sus atributos protegidos, un constructor por defecto, un
método getter y setter para el atributo _color, un método virtual (ToString) y dos métodos
abstractos: Dibujar (público) y CalcularDatos (protegido).
CalcularDatos será invocado en el constructor de la clase derivada que corresponda, su
funcionalidad será la de inicializar los atributos _superficie y _perimetro.
Dibujar, retornará un string (con el color que corresponda) formando la figura geométrica del objeto
que lo invoque (retornar una serie de asteriscos que modele el objeto).
*/

namespace Figuras;

abstract class FiguraGeometrica 
{
    protected string $color;
    protected float $perimetro;
    protected float $superficie;

    public function __construct() 
    { 
        $this->color = "";
        $this->perimetro = 0;
        $this->superficie = 0;
    }

    public function getColor() : string 
    {
        return $this->color;
    }
    
    public function setColor(string $color) : void
    {
        $this->color = $color;
    }

    protected abstract function calcularDatos() : void;

    public abstract function dibujar() : string;

    public function toString() : string
    {
        return "Color:  {$this->color} - Perimetro: {$this->perimetro} - Superficie: {$this->superficie}<br>";
    }


}

?>