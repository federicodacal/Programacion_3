<?php 

/*
Realizar una clase llamada “Auto” que posea los siguientes atributos privados:

_color (String)
_precio (Double)
_marca (String).
_fecha (DateTime)


Realizar un constructor capaz de poder instanciar objetos pasándole como parámetros:
i. La marca y el color.
ii. La marca, color y el precio.
iii. La marca, color, precio y fecha.

Realizar un método de instancia llamado “AgregarImpuestos”, que recibirá un doble por
parámetro y que se sumará al precio del objeto.
Realizar un método de clase llamado “MostrarAuto”, que recibirá un objeto de tipo “Auto” por
parámetro y que mostrará todos los atributos de dicho objeto.
Crear el método de instancia “Equals” que permita comparar dos objetos de tipo “Auto”. Sólo
devolverá TRUE si ambos “Autos” son de la misma marca.
Crear un método de clase, llamado “Add” que permita sumar dos objetos “Auto” (sólo si son de la
misma marca, y del mismo color, de lo contrario informarlo) y que retorne un Double con la suma
de los precios o cero si no se pudo realizar la operación.
*/

class Auto 
{
    private string $color;
    private float $precio;
    private string $marca;
    private DateTime $fecha;

    public function __construct(string $marca, string $color, float $precio = 0, DateTime $fecha = new DateTime())
    {
        $this->color = $color;
        $this->precio = $precio;
        $this->marca = $marca;
        $this->fecha = $fecha;
    }

    //Realizar un método de instancia llamado “AgregarImpuestos”, que recibirá un doble por
    //parámetro y que se sumará al precio del objeto.
    public function agregarImpuestos(float $impuestos) : void
    {
        $this->precio += $impuestos;
    }

    //Realizar un método de clase llamado “MostrarAuto”, que recibirá un objeto de tipo “Auto” por
    //parámetro y que mostrará todos los atributos de dicho objeto.
    public static function mostrarAuto(Auto $a) : string
    {
        return "Marca: {$a->marca} - Color: {$a->color} - Precio: \${$a->precio} - Fecha: {$a->fecha->format('Y-m-d H:i:s')}";
    }

    //Crear el método de instancia “Equals” que permita comparar dos objetos de tipo “Auto”. Sólo
    //devolverá TRUE si ambos “Autos” son de la misma marca.

    public function equals(Auto $a) : bool
    {
        return $a->marca === $this->marca;
    }

    //Crear un método de clase, llamado “Add” que permita sumar dos objetos “Auto” (sólo si son de la
    //misma marca, y del mismo color, de lo contrario informarlo) y que retorne un Double con la suma
    //de los precios o cero si no se pudo realizar la operación.
    public static function add(Auto $a1, Auto $a2) : float 
    {
        if($a1->equals($a2) && $a1->color === $a2->color)
        {
            return $a1->precio + $a2->precio;
        }
        return 0;
    }

}


?>