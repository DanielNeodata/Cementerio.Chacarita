<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

// Cambiar
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"id","type"=>"text","readonly"=>true,"class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"Codigo","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"Nombre","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"Precio","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"Meses","type"=>"number","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"Clase","type"=>"text","class"=>"form-control text dbase validate"));

$html.=getHtmlResolved($parameters,"controls","ID_CuentaContable",array("col"=>"col-md-3"));
$html.=getHtmlResolved($parameters,"controls","id_TipoParcela",array("col"=>"col-md-2"));
$html.=getHtmlResolved($parameters,"controls","id_TamanioParcela",array("col"=>"col-md-2"));
$html.=getHtmlResolved($parameters,"controls","ID_Operacion",array("col"=>"col-md-3"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"orden","type"=>"text","class"=>"form-control text dbase validate"));
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
    $('.multiselect').selectpicker();
</script>