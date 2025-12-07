<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

// Cambiar
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div id='principalPagador'  class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<div class='form-row'>";
$html.="<input type='button' id='btnTransferirPagador' class='col btn-sm btn-outline-danger' value='Transferir Pagador'></input>";
$html.="<input type='button' id='btnVerHistoricoPagador' class='col btn-sm btn-outline-success' value='Ver Historico Pagador'></input>";
$html.="</div>";

$htmlModal = 
    // Modal
'<div class="modal fade" id="myModalHistorico" role="dialog"> ' .
' <div class="modal-dialog modal-lg"> ' .
    // Modal content
'   <div class="modal-content modal-lg">' .
'     <div class="modal-header">' .
'       <h4 class="modal-title" id="titulo">*TITULO*</h4>' .
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

$html.=$htmlModal;    
$html .= "<div id='contenidoGenerico'></div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"NumeroPagador","type"=>"text","readonly"=>true,"class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Nombre","type"=>"text","class"=>"form-control text dbase validate"));
$html .= getHtmlResolved($parameters, "controls", "id_TipoDocumento", array("col" => "col-md-1"));
$html .= getInput($parameters, array("custom" => "maxlength='10'", "col" => "col-md-3", "name" => "NumeroDocumento", "type" => "number", "class" => "form-control text dbase"));
$html .= getHtmlResolved($parameters, "controls", "id_paisNacionalidad", array("col" => "col-md-3"));

$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"DomicilioEntreCalles","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"DomicilioCalle","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"DomicilioNumero","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"DomicilioPiso","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"DomicilioDepartamento","type"=>"text","class"=>"form-control text dbase"));
$html.=getHtmlResolved($parameters,"controls","Domicilio_id_provincia",array("col"=>"col-md-3"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Localidad","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"CodigoPostal","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Telefono1","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Telefono2","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"InstanciaIntimatoria","type"=>"checkbox","checkboxtype"=>"01","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<hr>";

$html.="<div class='row'><div class='col-12'><h4>Agregar relación con Cliente</h4></div></div>";
$a="<div class='row'><div class='col-12'><table>";
$a.="<thead class='thead-light'><tr>";
$a.="<th>Cliente</th>";
$a.="<th></th>";
$a.="</tr></thead>";
$a.="<tbody>";
$a.="<tr>";
$a.="<td>";
$a.='<form class="form-inline" method="post" action="#">';
$a.='   <div class="input-group input-group-sm">';
$a.='       <input class="search_query form-control dbase" type="text" name="keyPagador" id="entradaPagador" placeholder="Buscar..." size="60" data-idpagador="' . $parameters["id"] . '" value="' . $pagadorDetalle .'">';
$a.='    </div>';
$a.='</form>';
$a.='<div id="resultadosPagador"></div>';
$a.="</td>";
$a.="<td>";
$a.="</td>";
$a.="</tr>";
$a.="</tbody></table></div></div>";
$html.=$a;    
$html.="<hr>";

//$html.="<div class='form-row'>";
$html.="<div class='row'><div class='col-12'><h5>Clientes Relacionados</h5></div></div>";
$a="<div class='row'><div class='col-12'>";
$a.="<table id='clientesRelacionadoConPagador'>";
$a.="<thead class='thead-light'><tr>";
$a.="<th>ID</th>";
$a.="<th>Cliente</th>";
$a.="<th></th>";
$a.="</tr></thead><tbody>";
foreach($parameters["clienteRelacionado"] as $key => $value){
    $a .= "<tr class='tr-". $value["id_cliente"]."'>";
    $a .="<td>" . $value["id_cliente"] . "</td>" 
        . "<td>" . $value["cliente"] . "</td>" 
        . "<td>" .  "<input type='button' class='btn borrarcliente btn-sm btn-outline-danger' ". " data-idClienteRelacionado='" . $value["id_cliente"] . "." . $value["id_pagador"] . "' value='(X)'></input>" . "</td>";
        //. "<td>"  . "</td>"; // saco el boton de borrado. si fuera necesario descomentar la linea de arriba y comentar aca. Tambien hay que comentar el return de Pagador.borrar_cliente_pagador
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