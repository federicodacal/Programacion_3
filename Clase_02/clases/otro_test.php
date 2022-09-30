<?php

//CLASE QUE DERIVA DE TEST E IMPLEMENTA UNA INTERFACE
class OtroTest extends Test implements IMostrable
{
	public string $atributoPropio;
	public static string $atributoEstatico;
	
	public function __construct(string $valor = NULL)
	{
		//INVOCO AL CONSTRUCTOR PADRE
		parent::__construct();
		
		if($valor != NULL)
			$this->atributoPropio = $valor;
		else
			$this->atributoPropio = "valor propio";

		// ACCEDO A MIEMBRO ESTÁTICO PROPIO
		self::$atributoEstatico = "un valor estático";
	}
	
	//POLIMORFISMO
	public function mostrar() : string
	{
		//INVOCO AL METODO MOSTRAR DEL PADRE
		$mostrarPadre = parent::mostrar();
		return $mostrarPadre . " - " . $this->atributoPropio;
	}
	
	public function mostrarMensaje() : void
	{
		echo "<br/>Mensaje desde un m&eacute;todo de una interface.<br/>";
	}
}
