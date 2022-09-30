<?php 

/*
En la clase Fabrica, la cantidad máxima de operarios será inicializada en 5.
Métodos (en Fabrica)
RetornarCostos (de instancia, privado): Retorna el dinero que la fábrica tiene que gastar en
concepto de salario de todos sus operarios.
MostrarOperarios (de instancia, privado): Recorre el Array de operarios de la fábrica y muestra el
nombre, apellido y el salario de cada operario (utilizar el método Mostrar de operario).
MostrarCosto (de clase): muestra la cantidad total del costo de la fábrica en concepto de salarios
(utilizar el método RetornarCostos).
Crear el método de clase “Equals”, recibe una Fabrica y un Operario. Retronará un booleano
informando si el operario se encuentra en la fábrica o no. Reutilizar código.
Add (de instancia): Agrega un operario al Array de tipo Operario, siempre y cuando haya lugar
disponible en la fábrica y el operario no se encuentre ya ingresado. Reutilizar código. Retorna TRUE
si pudo ingresar al operario, FALSE, caso contrario.
Remove (de instancia): Recibe a un objeto de tipo Operario y lo saca de la fábrica, siempre y
cuando el operario se encuentre en el Array de tipo Operario. Retorna TRUE si pudo quitar al
operario, FALSE, caso contrario.
Crear los objetos necesarios en testFabrica.php como para probar el buen funcionamiento de las
clases.
*/

require_once "operario.php";

class Fabrica 
{
    private int $cantMaxOperarios;
    private string $razonSocial;
    private $operarios;

    public function __construct(string $rs)
    {
        $this->razonSocial = $rs;
        $this->cantMaxOperarios = 5;
        $this->operarios = array();
    }

    private function retornarCostos() : float
    {
        $costos = 0;
        foreach($this->operarios as $operario)
        {
            $costos += $operario->getSalario();
        }
        return $costos;
    }

    private function mostrarOperarios() : string
    {
        $mensaje = "";
        foreach($this->operarios as $operario)
        {
            $mensaje .= $operario->mostrar() . "<br>";
        }
        return $mensaje;
    }
    
    public function mostrar() : string 
    {
        $mensaje = "Fabrica: {$this->razonSocial} - Capacidad: {$this->cantMaxOperarios} - Cantidad: " . count($this->operarios) . "<br>";
        $mensaje .= $this->mostrarOperarios() . "<br>";
        Fabrica::mostrarCosto($this);
        return $mensaje;
    }

    public static function mostrarCosto(Fabrica $fb) : void 
    {
        echo "Costos: $" . $fb->retornarCostos() . "<br>";
    }

    public static function equals(Fabrica $f, Operario $o) : bool
    {
        foreach($f->operarios as $operario)
        {
            if($o->equals($operario))
            {
                return true;
            }
        }
        return false;
    }

    public function add(Operario $o) : bool
    {
        if($this->cantMaxOperarios > count($this->operarios) && !Fabrica::equals($this, $o))
        {
            array_push($this->operarios, $o);
            return true;
        }
        return false;
    }

    public function indexOf(Operario $o) : int 
    {   
        $index = -1;
        if(Fabrica::equals($this, $o))
        {
            for($i = 0; $i < count($this->operarios); $i++)
            {
                if($o->equals($this->operarios[$i]))
                {
                    $index = $i;
                    break;
                }
            }
        }
        return $index;
    }

    public function remove(Operario $o) : bool 
    {
        if(Fabrica::equals($this, $o))
        {
            $index = $this->indexOf($o);
            if($index != -1)
            {
                array_splice($this->operarios, $index, 1);
                return true;
            }
        }
        return false;
    }
}



?>