<?php
namespace MiNamespace;

class Clase 
{
    public static function test():string 
    {
        return "método desde namespace.";
    }
}

function funcion():string
{ 
    return "función desde namespace.";
}

const CONSTANTE = 3;

echo "namespace actual: " . __NAMESPACE__ . "<br/>";