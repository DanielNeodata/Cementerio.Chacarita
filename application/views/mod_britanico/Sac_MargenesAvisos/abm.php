<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,"Márgenes avisos");
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"AvisoMargenClave","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"AvisoMargenWidth","type"=>"number","class"=>"form-control number dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"AvisoMargenHeight","type"=>"number","class"=>"form-control number dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"AvisoMargenX","type"=>"number","class"=>"form-control number dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"AvisoMargenY","type"=>"number","class"=>"form-control number dbase validate"));
$html.="</div>";


$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
