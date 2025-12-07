<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";

//$html.="<h3>CEMENTERIO </h3>";
$html.="<h4>Abm de Asientos</h4>";
//$html.="<div class='row'>  <div class='col-12' style='padding-top:15px;'>";
//$html.="<img src='./assets/img/print.jpg' style='height:35px;' id='printimg' name='printimg' onclick='showReport();'></img>";
//$html.=" </div>  </div>";

$html.="<hr/>";
$html.="<div class='form-row' style='display:none;'>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"ID","type"=>"text","class"=>"form-control text dbase "));
$html.="</div>";
$html.="<div class='form-row mb-1'>";
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"NUMERO_ENCABEZADO","type"=>"text","class"=>"form-control text dbase "));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"FECHA","type"=>"date","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"RENGLONES","type"=>"text","class"=>"form-control text dbase "));
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"COMENTARIO","type"=>"text","class"=>"form-control text dbase validate"));
$html.="</div>";

$html.="<div id='form-detail'></div>";

$html.="<script>";
$html.="_FUNCTIONS.onGetAsientoDetail($(this));";


$html.="</script>";


$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
//$html.='<script type="text/javascript"> $(".btn-abm-accept").prop("disabled", true);</script>';
echo $html;
?>

<script>
    
    $.getScript('./application/views/mod_britanico/Sac_funcavanzadas_asientos/abm.js', function () {
        //alert("Done script abm js 111111111111111111111111111");
    });

</script>



