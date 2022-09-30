<?php 

namespace Negocios;

require_once "./clases/mascota.php";

use Animalitos\Mascota;

class Guarderia
{
    public string $nombre;
    public $mascotas;

    public function __construct(string $nombre)
    {
        $this->nombre = $nombre;
        $this->mascotas = array();
    }

    public static function equals(Guarderia $g, Mascota $m) : bool
    {
        if(isset($g) && isset($m))
        {
            foreach($g->mascotas as $mascota)
            {
                if($m->equals($mascota))
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function add(Mascota $m) : bool
    {
        $rta = false;
        if(!Guarderia::equals($this, $m))
        {
            array_push($this->mascotas, $m);
            $rta = true;
        }
        return $rta;
    }

    public function toString() : string
    {
        $mensaje = "";
        foreach($this->mascotas as $mascota)
        {
            $mensaje .= $mascota->toString() . "<br>";
        }
        return $mensaje . "El promedio de edad es: " . $this->calcularPromedioMascotas() . " años.";
    }

    public function calcularPromedioMascotas() : float
    {   
        $acumEdades = 0;
        foreach($this->mascotas as $mascota)
        {
            $acumEdades += $mascota->edad;
        }
        return $acumEdades / count($this->mascotas);
    }
}

?>