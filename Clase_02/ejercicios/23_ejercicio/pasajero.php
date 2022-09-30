<?php 

/*
Pasajero
Atributos privados: _apellido (string), _nombre (string), _dni (string), _esPlus (boolean)
Crear un constructor capaz de recibir los cuatro parámetros.
Crear el método de instancia “Equals” que permita comparar dos objetos Pasajero. Retornará
TRUE cuando los _dni sean iguales.
Agregar un método getter llamado GetInfoPasajero, que retornará una cadena de caracteres con los
atributos concatenados del objeto.
Agregar un método de clase llamado MostrarPasajero que mostrará los atributos en la página.
*/

class Pasajero 
{
    private string $apellido;
    private string $nombre;
    private string $dni;
    private bool $esPlus;

    public function __construct(string $apellido, string $nombre, string $dni, bool $esPlus)
    {
        $this->apellido = $apellido;
        $this->nombre = $nombre;
        $this->dni = $dni;
        $this->esPlus = $esPlus;
    }

    public function equals(Pasajero $pasajero) : bool 
    {
        return $this->dni === $pasajero->dni;
    }

    public function getInfoPasajero() : string 
    {
        return "{$this->nombre} {$this->apellido} DNI: {$this->dni}";
    }

    public static function mostrarPasajero(Pasajero $pasajero) : void 
    {
        echo $pasajero->getInfoPasajero();
    }

    public function getEsPlus() : bool 
    {
        return $this->esPlus;
    }
}


?>