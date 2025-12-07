<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,"Modelo de notificación");

$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"ModeloNotificacionNombre","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"ModeloNotificacionTitulo","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"remitente","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"NombreRemitente","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";
$html.="<div class='form-row'>";
$html.=getTextAreaHtmlEditor($parameters,array("col"=>"col-md-12","name"=>"ModeloNotificacionHtml","type"=>"textarea","class"=>"html form-control text dbase","rows"=>"20","cols"=>"200","free"=>"style='width: 900px; height: 200px; display: block;'"));
$html.="</div>";

$html.="<h4>Las variables a ser reemplazadas por datos del sistema son</h4><p>[TITULAR],[DIRECCION],[LOCALIDAD],[COD_POSTAL],[SECCION],[SEPULTURA],[VENCIMIENTO],[IMPORTE] y [HOY]</p>";
$html.="<h5>Ejemplo</h5><p>Estimado [TITULAR] de la sepultura [SEPULTURA] en la sección [SECCION].....</p>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
