<?php

/*
Vuelo
Atributos privados: _fecha (DateTime), _empresa (string) _precio (double), _listaDePasajeros
(array de tipo Pasajero), _cantMaxima (int; con su getter). Tanto _listaDePasajero como
_cantMaxima sólo se inicializarán en el constructor.
Crear el constructor capaz de que de poder instanciar objetos pasándole como parámetros:

i. La empresa y el precio.
ii. La empresa, el precio y la cantidad máxima de pasajeros.

Agregar un método getter, que devuelva en una cadena de caracteres toda la información de un
vuelo: fecha, empresa, precio, cantidad máxima de pasajeros, y toda la información de todos los
pasajeros.
Crear un método de instancia llamado AgregarPasajero, en el caso que no exista en la lista, se
agregará (utilizar Equals). Además tener en cuenta la capacidad del vuelo. El valor de retorno de
este método indicará si se agregó o no.
Agregar un método de instancia llamado MostrarVuelo, que mostrará la información de un vuelo.
Crear el método de clase “Add” para que permita sumar dos vuelos. El valor devuelto deberá ser de
tipo numérico, y representará el valor recaudado por los vuelos. Tener en cuenta que si un pasajero
es Plus, se le hará un descuento del 20% en el precio del vuelo.
Crear el método de clase “Remove”, que permite quitar un pasajero de un vuelo, siempre y cuando
el pasajero esté en dicho vuelo, caso contrario, informarlo. El método retornará un objeto de tipo
Vuelo.
*/

require_once "pasajero.php";

class Vuelo 
{
    private DateTime $fecha;
    private string $empresa;
    private float $precio;
    private $pasajeros;
    private int $cantMaxima;

    public function __construct(string $empresa, float $precio, int $cantMaxima = 10)
    {
        $this->fecha = new DateTime();
        $this->empresa = $empresa;
        $this->precio = $precio;
        $this->cantMaxima = $cantMaxima;
        $this->pasajeros = array();
    }

    public function getCantidadMaxima() : int 
    {
        return $this->cantMaxima;
    }

    public function getInfoVuelo() : string 
    {
        $mensaje = "Fecha {$this->fecha->format('d/m/Y')}<br>" . "Empresa: {$this->empresa}<br>" . "Precio \${$this->precio}<br>" . "Capacidad: " . $this->getCantidadMaxima() . "<br>Pasajeros: " . count($this->pasajeros) . "<br>";
        foreach($this->pasajeros as $pasajero)
        {
            $mensaje .= $pasajero->getInfoPasajero() . "<br>";
        }
        return $mensaje; 
    }

    public function mostrarVuelo() : void 
    {
        echo $this->getInfoVuelo();
    }

    public function equals(Pasajero $p)
    {
        foreach($this->pasajeros as $pasajero)
        {
            if($p->equals($pasajero))
            {
                return true;
            }
        }
        return false;
    }

    public function agregarPasajero(Pasajero $p) : bool 
    {
        $rta = false;
        if($this->getCantidadMaxima() > count($this->pasajeros))
        {
            if(!$this->equals($p))
            {
                array_push($this->pasajeros, $p);
                return true;
            }
        }
        return false;
    }

    public static function add(Vuelo $v1, Vuelo $v2) : float
    {
        return $v1->getRecaudacion() + $v2->getRecaudacion();
    }

    public function getRecaudacion() : float 
    {
        $recaudacion = 0;
        foreach($this->pasajeros as $pasajero)
        {
            if($pasajero->getEsPlus())
            {
                $recaudacion += $this->precio - ($this->precio * 0.2);
            }
            else 
            {
                $recaudacion += $this->precio;
            }
        }
        return $recaudacion;
    }

    public static function remove(Vuelo $v, Pasajero $p) : Vuelo
    {
        if($v->equals($p))
        {
            foreach($v->pasajeros as $pasajero)
            {
                if($p->equals($pasajero))
                {
                    $index = array_search($p, $v->pasajeros);
                    array_splice($v->pasajeros, $index, 1);
                }
            }
        }
        return $v;
    }

}

?>