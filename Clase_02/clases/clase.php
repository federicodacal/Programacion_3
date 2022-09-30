<?php

//CLASE QUE DERIVA DE LA CLASE ABSTRACTA
class Clase extends ClaseAbstracta
{
	//ATRIBUTOS
	public string $otroAtributo;
	
	//CONSTRUCTOR
	public function __construct(string $valor, string $otroValor)
	{
		parent::__construct($valor);
		$this->otroAtributo = $otroValor;		
	}
	
	//IMPLEMENTO METODO ABSTRACTO
	public function metodoAbstracto() : string
	{
		return "<br/>M&eacute;todo Abstracto";
	}
}