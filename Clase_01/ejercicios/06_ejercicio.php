<?php 

/*
Aplicación No 6 (Calculadora)
Escribir un programa que use la variable $operador que pueda almacenar los símbolos
matemáticos: ‘+’, ‘-’, ‘/’ y ‘*’; y definir dos variables enteras $op1 y $op2. De acuerdo al
símbolo que tenga la variable $operador, deberá realizarse la operación indicada y mostrarse el
resultado por pantalla.
*/

$operador = '-';
$op1 = 10;
$op2 = 5;
$operacionOk = false;
$resultado;

if(isset($op1) && isset($op2))
{
    switch ($operador)
    {
    case '+';
        $resultado = $op1 + $op2;
        $operacionOk = true;
        break;
    case '-';
        $resultado = $op1 - $op2;
        $operacionOk = true;
        break;
    case '*':
        $resultado = $op1 * $op2;
        $operacionOk = true;
        break;
    case '/';
        if($op2 != 0)
        {
            $resultado = $op1 / $op2;
            $operacionOk = true;            
        }
        else
        {
            echo "División por 0 no es válida";
        }
        break;
    default:
        echo "Operador no válido";
        break;
    }
    
    if($operacionOk)
    {
        echo "$op1 $operador $op2 = $resultado";
    }
}
else
{
    echo "Operandos no asignados";
}


?>