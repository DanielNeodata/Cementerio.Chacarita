<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<h4>Cuenta</h4>";
$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Nombre_cocheria","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Domicilio","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Localidad","type"=>"text","class"=>"form-control text dbase "));
$html .= getInput($parameters, array("col" => "col-md-1", "name" => "CodigoPostal", "type" => "text", "class" => "form-control text dbase "));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"Telefonos","type"=>"text","class"=>"form-control text dbase "));
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
