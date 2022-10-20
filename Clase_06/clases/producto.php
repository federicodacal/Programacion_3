<?php
class Producto
{
//--------------------------------------------------------------------------------//
//--ATRIBUTOS
	private string $codBarra;
 	private string $nombre;
  	private string $pathFoto;
//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--GETTERS Y SETTERS
	public function getCodBarra() : string
	{
		return $this->codBarra;
	}
	public function getNombre() : string
	{
		return $this->nombre;
	}
	public function getPathFoto() : string
	{
		return $this->pathFoto;
	}

	public function setCodBarra(string $valor) : void
	{
		$this->codBarra = $valor;
	}
	public function setNombre(string $valor) : void
	{
		$this->nombre = $valor;
	}
	public function setPathFoto(string $valor) : void
	{
		$this->pathFoto = $valor;
	}

//--------------------------------------------------------------------------------//
//--CONSTRUCTOR
	public function __construct($codBarra=NULL, $nombre=NULL, $pathFoto=NULL)
	{
		if($codBarra !== NULL && $nombre !== NULL){
			$this->codBarra = $codBarra;
			$this->nombre = $nombre;
			$this->pathFoto = $pathFoto;
		}
	}

//--------------------------------------------------------------------------------//
//--TOSTRING	
  	public function toString() : string
	{
	  	return $this->codBarra." - ".$this->nombre." - ".$this->pathFoto."\r\n";
	}
//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--METODOS DE CLASE
	public static function guardar(Producto $obj) : bool
	{
		$resultado = FALSE;
		
		//ABRO EL ARCHIVO EN MODO APPEND
		$ar = fopen("./archivos/productos.txt", "a");
		
		//AGREGO EN EL ARCHIVO
		$cant = fwrite($ar, $obj->toString());
		
		if($cant > 0)
		{
			$resultado = TRUE;			
		}
		//CIERRO EL ARCHIVO
		fclose($ar);
		
		return $resultado;
	}
	public static function traerTodosLosProductos() : array
	{
		$listaDeProductosLeidos = array();

		//ABRO EL ARCHIVO EN MODO READ
		$archivo = fopen("./archivos/productos.txt", "r");
		
		if( ! $archivo){
			echo "no se conectó...";
		}
		else{
			while(!feof($archivo))
			{
				$archAux = fgets($archivo);
				$productos = explode(" - ", $archAux);
				//http://www.w3schools.com/php/func_string_explode.asp
				$productos[0] = trim($productos[0]);
				if($productos[0] != ""){
					$listaDeProductosLeidos[] = new Producto($productos[0], $productos[1],$productos[2]);
				}
			}
			//CIERRO EL ARCHIVO
			fclose($archivo);
		}

		return $listaDeProductosLeidos;	
	}
	public static function modificar(Producto $obj) : bool
	{
		$resultado = TRUE;
		
		$listaDeProductosLeidos = Producto::traerTodosLosProductos();
		$imagenParaBorrar = NULL;
		
		for($i=0; $i<count($listaDeProductosLeidos); $i++){
			if($listaDeProductosLeidos[$i]->codBarra == $obj->codBarra){//encontré el modificado
				$imagenParaBorrar = trim($listaDeProductosLeidos[$i]->pathFoto);
				$listaDeProductosLeidos[$i] = $obj;
				break;
			}
		}
		
		//SI SE ENCONTRO EL PRODUCTO A SER MODIFICADO...
		if ($imagenParaBorrar !== NULL) {
			//BORRO LA IMAGEN ANTERIOR
			unlink("./archivos/".$imagenParaBorrar);

			//ABRO EL ARCHIVO EN MODO WRITE
			$ar = fopen("./archivos/productos.txt", "w");
			
			//ESCRIBO EN EL ARCHIVO
			foreach($listaDeProductosLeidos AS $item){
				$cant = fwrite($ar, $item->toString());
				
				if($cant < 1)
				{
					$resultado = FALSE;
					break;
				}
			}
			
			//CIERRO EL ARCHIVO
			fclose($ar);
		}
		else{
			$resultado = FALSE;
		}

		return $resultado;
	}
	public static function eliminar(string $codBarra) : bool
	{
		if($codBarra === NULL){
			return FALSE;
		}
			
		$resultado = TRUE;
		
		$listaDeProductosLeidos = Producto::traerTodosLosProductos();
		$listaDeProductos = array();
		$imagenParaBorrar = NULL;
		
		for($i=0; $i<count($listaDeProductosLeidos); $i++){
			if($listaDeProductosLeidos[$i]->codBarra == $codBarra){//encontré el borrado, lo excluyo
				$imagenParaBorrar = trim($listaDeProductosLeidos[$i]->pathFoto);
				continue;
			}
			$listaDeProductos[$i] = $listaDeProductosLeidos[$i];
		}

		//SI SE ENCONTRO EL PRODUCTO A SER ELIMINADO...
		if ($imagenParaBorrar !== NULL) {
			//BORRO LA IMAGEN ANTERIOR
			unlink("./archivos/".$imagenParaBorrar);
		
			//ABRO EL ARCHIVO EN MODO WRITE
			$ar = fopen("./archivos/productos.txt", "w");
			
			//ESCRIBO EN EL ARCHIVO
			foreach($listaDeProductos AS $item){
				$cant = fwrite($ar, $item->toString());
				
				if($cant < 1)
				{
					$resultado = FALSE;
					break;
				}
			}
			//CIERRO EL ARCHIVO
			fclose($ar);
		}
		else{
			$resultado = FALSE;
		}

		return $resultado;
	}
//--------------------------------------------------------------------------------//
}