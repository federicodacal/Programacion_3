<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . './clases/producto.php';

header('content-type:application/pdf');

$mpdf = new \Mpdf\Mpdf(['orientation' => 'P', 
                        'pagenumPrefix' => 'Página nro. ',
                        'pagenumSuffix' => ' - ',
                        'nbpgPrefix' => ' de ',
                        'nbpgSuffix' => ' páginas']);//P-> vertical; L-> horizontal



$mpdf->SetHeader('{DATE j-m-Y}||{PAGENO}{nbpg}');
//alineado izquierda | centro | alineado derecha
$mpdf->SetFooter('{DATE Y}|Programacón III|{PAGENO}');


$arrayDeProductos = Producto::traerTodosLosProductos();

$grilla = '<table class="table" border="1" align="center">
            <thead>
                <tr>
                    <th>  COD. BARRA </th>
                    <th>  NOMBRE     </th>
                    <th>  FOTO       </th>
                </tr> 
            </thead>';   	

foreach ($arrayDeProductos as $prod){
    $producto = array();
    $producto["codBarra"] = $prod->getCodBarra();
    $producto["nombre"] = $prod->getNombre();

    $grilla .= "<tr>
                    <td>".$prod->getCodBarra()."</td>
                    <td>".$prod->getNombre()."</td>
                    <td><img src='archivos/".$prod->getPathFoto()."' width='100px' height='100px'/></td>
                </tr>";
}

$grilla .= '</table>';

$mpdf->WriteHTML("<h3>Listado de productos</h3>");
$mpdf->WriteHTML("<br>");

$mpdf->WriteHTML($grilla);


$mpdf->Output('mi_pdf.pdf', 'I');