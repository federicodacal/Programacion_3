<?php 

/*
Métodos getters y setters (en Operario):
GetSalario: Sólo retorna el salario del operario.
SetAumentarSalario: Sólo permite asignar un nuevo salario al operario. La asignación consiste en
incrementar el salario de acuerdo al porcentaje que recibe como parámetro.
Constructores: realizar los constructores para cada clase (Fabrica y Operario) con los parámetros
que se detallan en la imagen.
Métodos (en Operario)
GetNombreApellido (de instancia): Retorna un String que tiene concatenado el nombre y el
apellido del operario separado por una coma.
Mostrar (de instancia): Retorna un String con toda la información del operario. Utilizar el método
GetNombreApellido.
Mostrar (de clase): Recibe un operario y retorna un String con toda la información del mismo
(utilizar el método Mostrar de instancia)
Crear el método de instancia “Equals” que permita comparar al objeto actual con otro de tipo
Operario. Retronará un booleano informando si el nombre, apellido y el legajo de los operarios
coinciden al mismo tiempo.
*/

class Operario 
{
    private string $apellido;
    private string $nombre;
    private float $salario;
    private int $legajo;

    public function __construct(int $legajo, string $apellido, string $nombre, float $salario)
    {
        $this->apellido = $apellido;
        $this->nombre = $nombre;
        $this->legajo = $legajo;
        $this->salario = $salario;
    }

    public function getSalario() : float 
    {
        return $this->salario;
    }

    public function setAumentarSalario(float $aumento) : void 
    {
        $this->salario = $this->salario + ($this->salario * $aumento/100);
    }

    /*
    GetNombreApellido (de instancia): Retorna un String que tiene concatenado el nombre y el
    apellido del operario separado por una coma.
    Mostrar (de instancia): Retorna un String con toda la información del operario. Utilizar el método
    GetNombreApellido.
    Mostrar (de clase): Recibe un operario y retorna un String con toda la información del mismo
    (utilizar el método Mostrar de instancia)
    Crear el método de instancia “Equals” que permita comparar al objeto actual con otro de tipo
    Operario. Retronará un booleano informando si el nombre, apellido y el legajo de los operarios
    coinciden al mismo tiempo.
    */
    public function getNombreApellido() : string 
    {
        return "{$this->nombre}, {$this->apellido}";
    }

    public function mostrar() : string 
    {
        return $this->getNombreApellido() . "- Salario: $ " . $this->getSalario() . " - Legajo: {$this->legajo}";
    }

    public static function mostrarOperario(Operario $o) : string 
    {
        return $o->mostrar();
    }

    public function equals(Operario $o)
    {
        if($this->nombre === $o->nombre && $this->apellido === $o->apellido && $this->legajo === $o->legajo)
        {
            return true;
        }
        return false;
    }
} 


?>