<?php 

/*
Crear la clase Garage que posea como atributos privados:

_razonSocial (String)
_precioPorHora (Double)
_autos (Autos[], reutilizar la clase Auto del ejercicio anterior)

Realizar un constructor capaz de poder instanciar objetos pasándole como parámetros:

i. La razón social.
ii. La razón social, y el precio por hora.

Realizar un método de instancia llamado “MostrarGarage”, que no recibirá parámetros y que
mostrará todos los atributos del objeto.
Crear el método de instancia “Equals” que permita comparar al objeto de tipo Garaje con un
objeto de tipo Auto. Sólo devolverá TRUE si el auto está en el garaje.
Crear el método de instancia “Add” para que permita sumar un objeto “Auto” al “Garage” (sólo si
el auto no está en el garaje, de lo contrario informarlo).
Ejemplo: $miGarage->Add($autoUno);
Crear el método de instancia “Remove” para que permita quitar un objeto “Auto” del “Garage”
(sólo si el auto está en el garaje, de lo contrario informarlo).
Ejemplo: $miGarage->Remove($autoUno);
En testGarage.php, crear autos y un garage. Probar el buen funcionamiento de todos los métodos.
*/

require_once "../21_ejercicio/auto.php";

class Garage
{
    private string $razonSocial;
    private float $precioPorHora;
    private $autos;

    public function __construct(string $razonSocial, float $precioPorHora = 1)
    {
        $this->razonSocial = $razonSocial;
        $this->precioPorHora = $precioPorHora;
        $this->autos = array();
    }

    public function mostrarGarage() : void 
    {
        echo "GARAGE: {$this->razonSocial} - \${$this->precioPorHora} - Autos: " . count($this->autos) . "<br>";
        foreach($this->autos as $auto)
        {
            echo Auto::mostrarAuto($auto) . "<br>";
        }
    }

    public function equals(Auto $a) : bool
    {
        foreach($this->autos as $auto)
        {
            if($auto === $a)
            {
                return true;
            }
        }
        return false;
    }

    public function add(Auto $a) : bool
    {
        if(!$this->equals($a))
        {
            array_push($this->autos, $a);
            return true;
        }
        return false;
    }

    public function remove(Auto $a) : bool
    {
        if($this->equals($a))
        {
            $index = $this->indexOf($a);
            array_splice($this->autos, $index, 1);
            return true;
        }
        return false;
    }

    public function indexOf(Auto $a) : int
    {
        return array_search($a, $this->autos);
    }
}

?>