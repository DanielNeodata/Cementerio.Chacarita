<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"parcela_formateada3","type"=>"text","readonly"=>true,"class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"Disponible","type"=>"text","readonly"=>true,"class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"numero_pagina_mapa","type"=>"text","class"=>"form-control text dbase"));
$html.=getHtmlResolved($parameters,"controls","id_TipoParcela",array("col"=>"col-md-3"));
if ($parameters["permisosAdicionalesDeleteClienteParcela"] == "S") {
    $html .= getInput($parameters,array("col"=>"col-md-2","name"=>"CodigoAnterior","type"=>"checkbox","checkboxtype"=>"SN","readonly"=>false, "class"=>"form-control text dbase"));
} else {
    $html .= getInput($parameters,array("col"=>"col-md-2","name"=>"CodigoAnterior","type"=>"checkbox","checkboxtype"=>"SN","custom"=>"disabled","class"=>"form-control text dbase"));
}
$html .= "</div>";
$html .= "<div class='form-row p-2 shadow-lg'>";
$html.="<div class='col-2'>Estado (N/C/J/A): </div>";
if ($parameters["records"]["data"][0]["ClienteCategoria"]=="N") {
    $html.="  <div class='col-2'><label for 'ClienteCategoria_0'>Normal</label><input id='ClienteCategoria_0' type='radio' checked class='opcionClienteCategoria' value='N' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html.="  <div class='col-2'><label for 'ClienteCategoria_1'>Camino</label><input id='ClienteCategoria_1' type='radio' class='opcionClienteCategoria' value='C' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html.="  <div class='col-2'><label for 'ClienteCategoria_2'>Jardin</label><input id='ClienteCategoria_2' type='radio' class='opcionClienteCategoria' value='J' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html .= "  <div class='col-2'><label for 'ClienteCategoria_3'>Abandono</label><input id='ClienteCategoria_3' type='radio' class='opcionClienteCategoria' value='A' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
} else if ($parameters["records"]["data"][0]["ClienteCategoria"]=="C") {
    $html.="  <div class='col-2'><label for 'ClienteCategoria_0'>Normal</label><input id='ClienteCategoria_0' type='radio' class='opcionClienteCategoria' value='N' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html.="  <div class='col-2'><label for 'ClienteCategoria_1'>Camino</label><input id='ClienteCategoria_1' type='radio' checked class='opcionClienteCategoria' value='C' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html.="  <div class='col-2'><label for 'ClienteCategoria_2'>Jardin</label><input id='ClienteCategoria_2' type='radio' class='opcionClienteCategoria' value='J' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html .= "  <div class='col-2'><label for 'ClienteCategoria_3'>Abandono</label><input id='ClienteCategoria_3' type='radio' class='opcionClienteCategoria' value='A' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
} else if ($parameters["records"]["data"][0]["ClienteCategoria"]=="J") {
    $html.="  <div class='col-2'><label for 'ClienteCategoria_0'>Normal</label><input id='ClienteCategoria_0' type='radio' class='opcionClienteCategoria' value='N' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html.="  <div class='col-2'><label for 'ClienteCategoria_1'>Camino</label><input id='ClienteCategoria_1' type='radio' class='opcionClienteCategoria' value='C' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html .= "  <div class='col-2'><label for 'ClienteCategoria_2'>Jardin</label><input id='ClienteCategoria_2' type='radio' checked class='opcionClienteCategoria' value='J' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html .= "  <div class='col-2'><label for 'ClienteCategoria_3'>Abandono</label><input id='ClienteCategoria_3' type='radio' class='opcionClienteCategoria' value='A' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
} else if ($parameters["records"]["data"][0]["ClienteCategoria"]=="A") {
    $html.="  <div class='col-2'><label for 'ClienteCategoria_0'>Normal</label><input id='ClienteCategoria_0' type='radio' class='opcionClienteCategoria' value='N' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html.="  <div class='col-2'><label for 'ClienteCategoria_1'>Camino</label><input id='ClienteCategoria_1' type='radio' class='opcionClienteCategoria' value='C' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html .= "  <div class='col-2'><label for 'ClienteCategoria_2'>Jardin</label><input id='ClienteCategoria_2' type='radio' class='opcionClienteCategoria' value='J' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
    $html .= "  <div class='col-2'><label for 'ClienteCategoria_3'>Abandono</label><input id='ClienteCategoria_3' type='radio' checked class='opcionClienteCategoria' value='A' name='SelClienteCategoria' onchange=javascript:seleccionClienteCategoria(); ></div>";
}
$html .= "</div>";
$html .= "<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-6 datoClienteCategoria d-none","name"=>"ClienteCategoria","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getTextArea($parameters,array("col"=>"col-md-6","name"=>"observaciones","type"=>"text","class"=>"form-control text dbase"));
$html .= "</div>";

//$html.="<div class='form-row'>";
$html.="<div class='row'><div class='col-12'><h4>Cliente Relacionado</h4></div></div>";
$a="<div class='row'><div class='col-12'><table>";
$a.="<thead class='thead-light'><tr>";
$a.="<th>Cliente</th>";
$a.="<th></th>";
$a.="</tr></thead>";

$a.="<tbody>";

$id_cliente = $parameters["clienteRelacionado"][0]["id_cliente"];
$id_pagador = $parameters["clienteRelacionado"][0]["id_pagador"];

if ($id_cliente<>null && $id_cliente!="" && $id_cliente<>0 && $id_pagador<>null && $id_pagador!="" && $id_pagador<>0) {
    $pagadorDetalleId = $id_cliente.".".$id_pagador;
    $pagadorDetalle = $parameters["clienteRelacionado"][0]["pagador"]."|*|".$pagadorDetalleId;
} else {
    $pagadorDetalleId = "";
    $pagadorDetalle = "";
}

$a.="<tr>";
$a.="<td>";
$a.='<form class="form-inline" method="post" action="#">';
$a.='   <div class="input-group input-group-sm">';
$a.='       <input class="search_query form-control dbase" type="text" name="key" id="entrada" placeholder="Buscar..." size="60" value="' . $pagadorDetalle .'">';
$a.='    </div>';
$a.='    <div id="borrarentrada">';
if ($parameters["permisosAdicionalesDeleteClienteParcela"] == "S") {
    $a.="       <input type='button' class='btn borrarcliente btn-sm btn-outline-danger' value='(B)' data-idClienteRelacionado='" . $pagadorDetalleId . "'></input>";
} else {
    $a.="       <input type='button' disabled class='btn borrarcliente btn-sm btn-outline-danger' value='(B)' data-idClienteRelacionado='" . $pagadorDetalleId . "'></input>";
} 
$a.='    </div>';
$a.='</form>';
$a.='<div id="resultados"></div>';
$a.="</td>";
$a.="<td>";
$a.="</td>";
$a.="</tr>";
$a.="</tbody></table></div></div>";
$html.=$a;    
$html.=" <div id='id_cliente' data-id='";
$html.=$id_cliente;
$html.="'></div>";

$id_parcela = $parameters["id"];

$x=" <input type='button' class='button btnA' value='Inicio devolución' id='A_1' name='A_1' onclick='javascript:Abandono_CEM(1, ".$id_parcela.", ".$id_cliente.");'/>";
$html.=$x;
$x=" <input type='button' class='button btnA' value='Envío carta' id='A_2' name='A_2' onclick='javascript:Abandono_CEM(2, ".$id_parcela.", ".$id_cliente.");'/>";
$html.=$x;
$x=" <input type='button' class='button btnA' value='Recepción carta' id='A_3' name='A_3' onclick='javascript:Abandono_CEM(3, ".$id_parcela.", ".$id_cliente.");'/>";
$html.=$x;
$x=" <input type='button' class='button btnA' value='Acta comité' id='A_4' name='A_4' onclick='javascript:Abandono_CEM(4, ".$id_parcela.", ".$id_cliente.");'/>";
$html.=$x;
$x=" <input type='button' class='button btnA' value='Terminado por abandono' id='A_5' name='A_5' onclick='javascript:Abandono_CEM(5, ".$id_parcela.", ".$id_cliente.");'/>";
$html.=$x;

$x=" <input type='button' class='button btnB' value='Devolución parcela' id='B_1' name='B_1' onclick='javascript:PresentacionPersonal_CEM(1, ".$id_parcela.", ".$id_cliente.");'/>";
$html.=$x;
$x=" <input type='button' class='button btnB' value='Terminado por devolución' id='B_5' name='B_5' onclick='javascript:PresentacionPersonal_CEM(5, ".$id_parcela.", ".$id_cliente.");'/>";
$html.=$x;

$x=" <input type='button' style='border:solid 2px red;' class='button btnX' value='Suspender y revertir el proceso de abandono' id='X_0' name='X_0' onclick='javascript:RevertirDesenganche_CEM( ".$id_parcela.", ".$id_cliente.");'/>";
$html.=$x;

//$html.=" <input type='button' class='button btnA' value='Inicio devolución' id='A_1' name='A_1' onclick='javascript:Abandono_CEM(1, 10, 11);'/>";
//$html.=" <input type='button' class='button btnA' value='Envío carta' id='A_2' name='A_2' onclick='javascript:Abandono_CEM(2, 10, 11);'/>";
//$html.=" <input type='button' class='button btnA' value='Recepción carta' id='A_3' name='A_3' onclick='javascript:Abandono_CEM(3, 10, 11);'/>";
//$html.=" <input type='button' class='button btnA' value='Acta comité' id='A_4' name='A_4' onclick='javascript:Abandono_CEM(4, 10, 11);'/>";
//$html.=" <input type='button' class='button btnA' value='Terminado por abandono' id='A_5' name='A_5' onclick='javascript:Abandono_CEM(5, 10, 11);'/>";

//$html.=" <input type='button' class='button btnB' value='Devolución parcela' id='B_1' name='B_1' onclick='javascript:PresentacionPersonal_CEM(1, 10, 11);'/>";
//$html.=" <input type='button' class='button btnB' value='Terminado por devolución' id='B_5' name='B_5' onclick='javascript:PresentacionPersonal_CEM(5, 10, 11);'/>";

//$html.=" <input type='button' style='border:solid 2px red;' class='button btnX' value='Suspender y revertir el proceso de abandono' id='X_0' name='X_0' onclick='javascript:RevertirDesenganche_CEM( 10, 11);'/>";

$html.="<hr>";

$html.="<div class='row'><div class='col-12'><h5>Listado Inhumados en la Parcela</h5></div></div>";
$a="<div class='row'><div class='col-12'>";
$a.="<table>";
$a.="<thead class='thead-light'><tr>";
$a.="<th>Nro.</th>";
$a.="<th>Nombre</th>";
$a.="<th>Deceso</th>";
$a.="</tr></thead><tbody>";
foreach($parameters["inhumadosParcela"] as $key => $value){

    $fd = "";
    $fds = "";
    $fd = $value["FechaDeceso"];
    $d = $fd;

    $yyyy = substr($d, 0, 4);
    $MM = substr($d, 5, 2);
    $dd = substr($d, 8, 2);
    $fds = $dd."/".$MM."/".$yyyy;    

    $a .= "<tr>";
    $a .="<td>" . $value["NroInhumado"] . "</td>" 
        . "<td>" . $value["Nombre"] . "</td>" 
        . "<td>" . $fds . "</td>";
    $a .= "</tr>"; 
}
$a .= "</tbody></table></div></div>";
$html.=$a;    

$html.="<hr>";

//$html.="<div class='form-row'>";
$html.="<div class='row'>Contratos de Arrendamiento de la Parcela: </div>";
$a="<div class='row'><table>";
$a.="<thead class='thead-light'><tr>";
$a.="<th>Vencim.</th>";
$a.="<th>Emision</th>";
$a.="<th>Nro.Deceso</th>";
$a.="<th>Parcela</th>";
$a.="<th>Cliente</th>";
$a.= "</tr></thead><tbody>";
foreach($parameters["contratosArrendamientoParcela"] as $key => $value){
    $a .= "<tr>";

    $fv = "";
    $fvs = "";
    $fv = $value["Fecha_Vencimiento"];
    $d = $fv;
    $yyyy = substr($d, 0, 4);
    $MM = substr($d, 5, 2);
    $dd = substr($d, 8, 2);
    $fvs = $dd."/".$MM."/".$yyyy;

    $fe = "";
    $fes = "";
    $fe = $value["Fecha_Emision"];
    $d = $fe;
    $yyyy = substr($d, 0, 4);
    $MM = substr($d, 5, 2);
    $dd = substr($d, 8, 2);
    $fes = $dd."/".$MM."/".$yyyy;
 
    $a .="<td>"
        . $fvs 
        . "</td>" . "<td>" 
        . $fes 
        . "</td>" . "<td>" 
        . $value["Nro_Arrendamiento"] 
        . "</td>" . "<td>" 
        . $value["numerocliente"] 
        . "</td>" . "<td>" 
        . $value["cliente"] 
        .  "</td>";
    $a .= "</tr>";
}
$a .= "</tbody></table></div>";
$html.=$a;    

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
    $.getScript('./application/views/mod_britanico/parcela/abm.js', function() {
    });
</script>