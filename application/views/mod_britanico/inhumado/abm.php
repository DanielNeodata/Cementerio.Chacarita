<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

// Cambiar
$html=buildHeaderAbmStd($parameters,"Fallecido");
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<div class='form-row'>";
if ($parameters["accionParaPermiso"] == "UPDATE") {
    if ($parameters["permisosAdicionalesModificarNombreFallecido"] == "S") {
        $html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Nombre","type"=>"text", "readonly"=>false, "class"=>"form-control text dbase validate"));
    } else {
        $html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Nombre","type"=>"text", "custom"=>"disabled","class"=>"form-control text dbase validate"));
    }
} else {
    $html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Nombre","type"=>"text", "readonly"=>false, "class"=>"form-control text dbase validate"));
}
$fecha=getInput($parameters,array("col"=>"col-md-2","name"=>"FechaDeceso", "type"=>"date","class"=>"form-control text dbase validate"));
$html.=$fecha;
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"CausaDeceso","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"LugarDeceso","type"=>"text","class"=>"form-control text dbase"));

$html.=getHtmlResolved($parameters,"controls","id_cocheria",array("col"=>"col-md-3"));
$html.=getHtmlResolved($parameters,"controls","id_paisNacionalidad",array("col"=>"col-md-3"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"Edad","type"=>"text","class"=>"form-control text dbase"));
$html.=getHtmlResolved($parameters,"controls","id_estadoCivil",array("col"=>"col-md-2"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"lata","type"=>"checkbox","checkboxtype"=>"SN","class"=>"form-control text dbase "));
$html.=getHtmlResolved($parameters,"controls","id_tipo_servicio",array("col"=>"col-md-2"));
$html.=getHtmlResolved($parameters,"controls","id_Parcela_Actual",array("col"=>"col-md-4"));
$html.=getHtmlResolved($parameters,"controls","id_Parcela_ActualReadOnly",array("col"=>"col-md-4"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"NumeroInhumado","type"=>"text","readonly"=>true,"class"=>"form-control text dbase validate"));
$html.="</div>";
$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
    $('.comboFormated').selectpicker();
</script>