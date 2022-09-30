<?php
//DECLARO CLASE ABSTRACTA
abstract class ClaseAbstracta
{
	//ATRIBUTOS
	protected string $atributo;
	
	//CONSTRUCTOR
	public function __construct(string $valor)
	{
		$this->atributo = $valor;
	}
	
	//METODO ABSTRACTO
	public abstract function metodoAbstracto() : string;
	
	//METODO NO ABSTRACTO
	public function getAtributo() : string
	{
		return $this->atributo;
	}

}