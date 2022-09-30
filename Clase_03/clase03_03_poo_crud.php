<?php
require_once("./elemento.php");

use TestCrud\Elemento;

//RECUPERO TODOS LOS VALORES (POST)
$tipoEjemplo = isset($_POST["tipoEjemplo"]) ? (int) $_POST["tipoEjemplo"] : 0;
$clave = isset($_POST["clave"]) ? (int) $_POST["clave"] : 0;
$valor_uno = isset($_POST["valor_uno"]) ? $_POST["valor_uno"] : NULL;
$valor_dos = isset($_POST["valor_dos"]) ? $_POST["valor_dos"] : NULL;

//****************************************** */
//CRUD - SOBRE ARCHIVOS
//****************************************** */

switch($tipoEjemplo)
{
	case 1://Create (Alta)

		$obj = new Elemento($clave, $valor_uno, $valor_dos);

		if(Elemento::agregar($obj)){

			echo "<h2> registro AGREGADO </h2><br/>";	
		}

		break;

	case 2://Read (listar)

		echo Elemento::listar();

		break;

	case 3://Update (Modificar)

		$obj = new Elemento($clave, $valor_uno, $valor_dos);

		if(Elemento::modificar($obj))
		{
			echo "<h2> registro MODIFICADO </h2><br/>";			
		}

		break;

	case 4://Delete (Borrar)

		if(Elemento::borrar($clave))
		{
			echo "<h2> registro BORRADO </h2><br/>";			
		}

		break;
				
	default:
		echo "<h2> Sin ejemplo </h2>";
}