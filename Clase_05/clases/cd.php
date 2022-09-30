<?php
namespace Pdo;

class Cd
{
    // Los atributos tienen que tener el mismo 'nombre' (identificador) de la consulta que voy a hacer (o sus alias)
    public string $titulo;
    public string $interprete;
    public int $anio;

    public function mostrarDatos() : string
    {
        return $this->titulo . " - " . $this->interprete . " - " . $this->anio;
    } 
}