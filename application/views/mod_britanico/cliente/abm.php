<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

// Cambiar
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div id='principalCliente' class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$id_cliente = $parameters["clienteRelacionado"][0]["id_cliente"];
$id_pagador = $parameters["clienteRelacionado"][0]["id_pagador"];
$pagadorDetalleId = $id_cliente.".".$id_pagador;
$pagadorDetalle = $parameters["clienteRelacionado"][0]["pagador"]."|*|".$pagadorDetalleId;
$id = $parameters["id"];
$obs = $parameters["records"]["data"][0]["observaciones"];

$html.="<div class='form-row'>";
$html.="    <input type='button' id='btnTransferirTitularidad' class='col btn-sm btn-outline-danger rounded-pill' value='Transferir Titularidad'></input>";
$html.="    <input type='button' id='btnVerHistoricoTitularidadCliente' class='col btn-sm btn-outline-success rounded-pill' value='Ver Historico Titularidad'></input>";
//$html.="    <input type='button' id='btnObservacionesCliente' class='col btn-sm btn-outline-success mastertooltip rounded-pill' onclick=\"javascript:$('#trDatosComplementarios').toggle();\" value='Datos Cliente'  title=''></input>";
$html.="    <input type='button' id='btnObservacionesCliente' class='col btn-sm btn-outline-success mastertooltip rounded-pill' value='Datos Cliente' title=''></input>";
$html.="    <input type='button' id='btnCuentaCorriente' class='col btn-sm btn-outline-success rounded-pill' value='Cta.Cte.'></input>";
$html.="    <input type='button' id='btnCuentaCorrienteImpaga' class='col btn-sm btn-outline-success rounded-pill' value='Impago'></input>";
$html.="    <input type='button' id='btnCuentaCorrienteHistorica' class='col btn-sm btn-outline-success rounded-pill' value='CC Histórica'></input>";
$html.="    <input type='button' id='btnResumenDeuda' class='col btn-sm btn-outline-success rounded-pill' value='Resumen Deuda'></input>";
$html.="    <input type='button' id='btnPlanPago' class='col btn-sm btn-outline-success rounded-pill' value='Plan Pagos'></input>";
$html.="</div>";
$html.="<br>";
$html.="<div id='trDatosComplementarios' class='border border-dark rounded-5' style='display: none'>";
$html.="    <label class='d-block align-top' for='txtDatosComplementarios'>Datos Complementarios:</label>";
$html.="    <input type='button' class='d-block btn-sm btn-outline-success rounded-pill align-top' value='Grabar datos' onclick='javascript:_FUNCTIONS.GrabarDatosComplementarios_CEM(" . $id . ");'></input>";
$html.="    <textarea id='txtDatosComplementarios' name='datosComplementarios'  rows='8' cols='60'>";
$html.=        $obs;
$html.=     "</textarea>";
$html.="</div>";

$a="<div class='row'><table>";
$a.="<thead class='thead-light'>";
$a.="    <tr>";
$a.="        <th>Cliente</th>";
$a.="        <th></th>";
$a.="    </tr>";
$a.="</thead>";

$a.="<tbody>";

$htmlModal = 
    // Modal
'<div class="modal fade" id="myModalHistorico" role="dialog"> ' .
' <div class="modal-dialog modal-lg"> ' .
    // Modal content
'   <div class="modal-content modal-lg">' .
'     <div class="modal-header">' .
'       <h4 class="modal-title">*TITULO*</h4>' .
'       <button type="button" class="close" data-dismiss="modal">&times;</button>' .
'     </div>' .
'     <div id="modalContenido" class="modal-body modal-lg">' .
'       <div>*CONTENIDO*</div>' .
'     </div>' .
'     <div class="modal-footer modal-lg">' .
'       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' .
'     </div>' .
'   </div>' .
' </div>' .
'</div>';

$htmlModalGenerica = 
    // Modal
'<div class="modal fade" id="myModal__" role="dialog"> ' .
' <div class="modal-dialog modal-lg"> ' .
    // Modal content
'   <div class="modal-content modal-lg">' .
'     <div class="modal-header">' .
'       <h4 class="modal-title">*TITULO*</h4>' .
'       <button type="button" class="close" data-dismiss="modal">&times;</button>' .
'     </div>' .
'     <div id="myModalContenido___" class="modal-body modal-lg">' .
'       <div>*CONTENIDO*</div>' .
'     </div>' .
'     <div class="modal-footer modal-lg">' .
'       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' .
'     </div>' .
'   </div>' .
' </div>' .
'</div>';

//$contenido ="<div class='row'>";
$contenido ="<div>";
$contenido.="<table id='historicoTitularidad'>";
$contenido.="<thead class='thead-light'><tr>";
$contenido.="<th>Num. Cliente</th>";
$contenido.="<th>Fecha Historico</th>";
$contenido.="<th>Nombre</th>";
$contenido.="<th>Telefono</th>";
$contenido.="<th>Nro.Documento</th>";
$contenido.="<th>CUIT</th>";
$contenido.="</tr></thead><tbody>";

foreach($parameters["historicoTitularidad"] as $key => $value){
    $contenido .= "<tr>";
    $contenido .= "<td>".$value["NumeroCliente"]."</td>";
    

    $fd = "";
    $fd = $value["fecha_historico"];
    $d = $fd;

    $yyyy = substr($d, 0, 4);
    $MM = substr($d, 5, 2);
    $dd = substr($d, 8, 2);
    $hh =substr($d, 11, 2); 
    $mi =substr($d, 14, 2);
    $fechaStr = $dd."/".$MM."/".$yyyy. " " . $hh . ":" . $mi;    

    $contenido .= "<td>".$fechaStr."</td>";
    
    $contenido .= "<td>".$value["RazonSocial"]."</td>";
    $contenido .= "<td>".$value["Telefono1"]."</td>";
    $contenido .= "<td>".$value["NumeroDocumento"]."</td>";
    $contenido .= "<td>".$value["Cuit"]."</td>";
    $contenido .= "</tr>";

}
$contenido.="</tbody></table></div>";

$xx = str_replace("*TITULO*", "Historico de Clientes", $htmlModal);
$sal = str_replace("<div>*CONTENIDO*</div>", $contenido, $xx);

$html.=$sal;    

$html.=$htmlModalGenerica;  // no se estaria usando??

$html .= "<div id='contenidoGenerico' data-id=''></div>";
$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"NumeroCliente","type"=>"text","readonly"=>true,"class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"RazonSocial","type"=>"text","class"=>"form-control text dbase validate"));
$html .= getInput($parameters, array("custom"=>"maxlength='10'", "col" => "col-md-3", "name" => "NumeroDocumento", "type" => "text", "onkeypress" => "return (event.charCode >= 48 && event.charCode <= 57);", "class" => "form-control text dbase validate"));
if ($parameters["permisosAdicionalesModificarMarcaSinDatos"] == "S") {
    //$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"RevisadoGerencia","type"=>"text","class"=>"form-control text dbase "));
    $html .= getInput($parameters, array("col" => "col-md-1", "name" => "RevisadoGerencia", "type" => "checkbox", "checkboxtype" => "SN", "class" => "form-control text dbase "));
} else {
    $x = $parameters["records"]["data"][0]["RevisadoGerencia"];
    if ( $x==null) {
        $x="";
        $parameters["records"]["data"][0]["RevisadoGerencia"]="";
    }
    $html.=getInput($parameters,array("col"=>"col-md-1","name"=>"RevisadoGerencia","type"=>"text","custom"=>"disabled", "class"=>"form-control text dbase "));
}
if ((int) $id != 0) {
    $html .= getInput($parameters, array("col" => "col-md-1", "name" => "gestion_externa", "type" => "checkbox", "checkboxtype" => "SN", "class" => "form-control text dbase "));
}
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"DomicilioEntreCalles","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"DomicilioCalle","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"DomicilioNumero","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"DomicilioPiso","type"=>"text","class"=>"form-control text dbase "));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"DomicilioDepartamento","type"=>"text","class"=>"form-control text dbase"));
$html.=getHtmlResolved($parameters,"controls","Domicilio_id_provincia",array("col"=>"col-md-3 validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Localidad","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"CodigoPostal","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getHtmlResolved($parameters,"controls","id_PaisNacionalidad",array("col"=>"col-md-3 validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Telefono1","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Telefono2","type"=>"text","class"=>"form-control text dbase"));
$selTipoDoc=getHtmlResolved($parameters,"controls","id_TipoDocumento",array("col"=>"col-md-4 validate"));
$sdt=str_replace ( "<option  value='1'>", "<option  value='1' selected>", $selTipoDoc );
$html.=$sdt;
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"observaciones","type"=>"text","readonly"=>true,"class"=>"form-control text dbase"));
$html.="</div>";

$html.="<hr>";

$html.="<div class='form-row'>";
$html.="<div class='col-12'><h4>Agregado de Notas de Seguimiento</h4></div>";
$html.="<div class='col-4'><label>Tipo de Nota:</label>";
$html.=getHtmlResolved($parameters,"controls","id_tipo_notas",array("col"=>"col-md-12"));
$html.="</div>";

//$html.="Datos Parcela: ".getTextArea($parameters,array("col"=>"col-md-3","name"=>"observaciones","type"=>"text","class"=>"form-control text dbase validate"));
$html .= "<div class='col-8'><label>Nota:</label><textarea id='nota' rows='10' class='form-control text'></textarea></div>";
$html.="</div>";

$html.="<div class='col-12 text-right'>";
$html.="<input type='button' id='agregarNotaAlCliente' class='btn btn-sm btn-outline-danger' value='Guardar Nota'></input>";
$html.="</div>";

$html.="<hr>";

$html.="<div class='row'><div class='col-12'><h5>Notas de Seguimiento</h5></div></div>";
$a="<div class='row'><div class='col-12'>";
$a.="<table id='notasAsociadasAlCliente' style='width:100%;'>";
$a.="<thead class='thead-dark'><tr>";
$a.="<th>Fecha</th>";
$a.="<th>Tipo</th>";
$a.="<th>Nota</th>";
$a.="<th>Usuario</th>";
$a.="</tr></thead><tbody>";
foreach($parameters["notasAsociadasAlCliente"] as $key => $value){
    $fd = "";
    $fd = $value["fecha_alta"];
    $d = $fd;

    $yyyy = substr($d, 0, 4);
    $MM = substr($d, 5, 2);
    $dd = substr($d, 8, 2);
    $hh =substr($d, 11, 2); 
    $mi =substr($d, 14, 2);
    $fechaStr = $dd."/".$MM."/".$yyyy. " " . $hh . ":" . $mi;    

    $a .= "<tr>";
    $a .= "<td>" . $fechaStr . "</td>" 
        . "<td>" . $value["tipo_nota"] . "</td>" 
        . "<td>" . $value["nota"] . "</td>"
        . "<td>" . $value["usuario"] . "</td>";
    $a .= "</tr>"; 
}
$a .= "</tbody></table></div></div>";
$html.=$a;   

$html.="</form>";
$html.="</div>";


$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
    $('.multiselect').selectpicker();
</script>