<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,"Notificación");

$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"Uso","type"=>"text","readonly"=>true,"class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"Etiqueta","type"=>"text","readonly"=>true,"class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"PosX","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"PosY","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"Width","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"Height","type"=>"text","class"=>"form-control text dbase"));
$html.=getTextAreaHtmlEditor($parameters,array("col"=>"col-md-12","name"=>"Contenido","type"=>"textarea","class"=>"html form-control text dbase","rows"=>"20","cols"=>"200","free"=>"style='width: 900px; height: 200px; display: block;'"));
$html.="</div>";

$html.="<h4>Las variables a ser reemplazadas por datos del sistema son</h4>";
$html.="<h7>_EMPRESA_ (Chacarita/Nogues) | _IDPAGADOR_  | _ANIO_ (del aviso) | _MES_ (del aviso)</h6><br/>";
$html.="<h7>_NUMEROPAGADOR_  | _NOMBREPAGADOR_ | _DOMICILIO_ | _CODIGOPOSTAL_</h6><br/>";  
$html.="<h7>_DOMICILIO_ | _CODIGOPOSTAL_ | _PROVINCIA_ | _PAIS_</h6><br/>"; 
$html.="<h7>_DETALLEDEUDA_ | _FECHAVISO_ | _FECHAVENCIMIENTO_ | _IMPORTETOTAL_</h6><br/>";
$html.="<h7>_NUMEROPAGADORPADEADO_ | _BARCODE_ | _TITULO_</h6><br/>";
$html.="<h5>Ejemplo</h5>"; 
$html.="<p>Total a Pagar $ _IMPORTETOTAL_</p>";
$html.="<p>&lt;b&gt;PagoMisCuentas:&lt;/b&gt; puede abonar por medio de &lt;b&gt;www.pagomiscuentas.com&lt;/b&gt;, con este n&ordm; de referencia &lt;b&gt;_NUMEROPAGADORPADEADO_&lt;/b&gt;</p>"; 

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
