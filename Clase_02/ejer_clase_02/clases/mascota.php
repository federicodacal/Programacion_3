<?php

namespace Animalitos;

class Mascota 
{
    public string $nombre;
    public string $tipo;
    public int $edad;

    public function __construct(string $nombre, string $tipo, int $edad = 0)
    {
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->edad = $edad;
    }

    public function toString() : string 
    {
        return "Nombre: {$this->nombre} - Tipo: {$this->tipo} - Edad: {$this->edad}";
    }

    public static function mostrar(Mascota $mascota) : string
    {
        return $mascota->toString();
    }

    public function equals(Mascota $mascota) : bool
    {
        if(isset($mascota->nombre) && isset($mascota->tipo) && isset($this->tipo) && isset($this->tipo))
        {
            return $this->nombre == $mascota->nombre && $this->tipo == $mascota->tipo;
        }
        return false;
    }
}

?>