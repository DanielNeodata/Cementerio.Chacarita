<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//log_message("error", "Estoy en deposito.php");
//log_message("error", "deposito parameter: ".json_encode($parameters,JSON_PRETTY_PRINT));
//log_message("error", "deposito data: ".json_encode($data,JSON_PRETTY_PRINT));
//log_message("error", "deposito data: ".json_encode($misDatos,JSON_PRETTY_PRINT));

$datos = $_ci_vars["misDatos"];
log_message("error", "deposito datos por ci_vars: ".json_encode($datos,JSON_PRETTY_PRINT));

$parameters = $_ci_vars["parameters"];
$title = $parameters["title"];
?>
<script>
    //$.getScript('./application/views/mod_britanico/valorencartera/deposito.js', function() {
    $.getScript('./application/views/mod_britanico/valorencartera/deposito.js').done(function(script, text) {

    });
</script>
<?php

/*
$html=<<<EOD
<script type="text/javascript">
  alert("This alert box was called with the onload event");
</script>
EOD;
$html.=<<<EOD
<script src=""
</script>
EOD;
$html.=<<<EOD
<script>
    //$.getScript('./application/views/mod_britanico/valorencartera/deposito.js', function() {
    $.getScript('mod_britanico/valorencartera/deposito.js', function() {
    });
</script>
EOD;*/

$js = file_get_contents('./application/views/mod_britanico/valorencartera/deposito.js');
//$html.="<script>".$js."</script>";

//$html.=buildHeaderBrowStd($parameters,$title);
//$html.=buildHeaderAbmStd($parameters,$title);
$html.="<div id='principal'>".buildHeaderAbmStd($parameters,$title);

if (!isset($parameters["records"])) {
    $html.=getUnInitialized();
} else {
    $nodata=getNoData();
    $html.="<div class='body-browser d-flex border-light m-0 p-0 rounded shadow-sm'>";
    $html.=" <table class='table-depositos table table-hover table-sm table-browser m-0 p-0' style='min-width:750px;width:100%;'>";
    $html.=buildBodyHeadBrowStd($parameters);
    $html.="  <tbody>";
    if(is_array($parameters["records"]["data"])) {
        foreach ((array)$parameters["records"]["data"] as $record){
            $nodata="";
            $style="";
            if(isset($parameters["conditionalBackground"])) {
                foreach($parameters["conditionalBackground"] as $conditional){
                    $style="";
                    $OK=false;
                    if (!isset($conditional["operator"])) {$conditional["operator"]="=";}
                    switch($conditional["operator"]) {
                        case "=":
                            $OK=($record[$conditional["field"]]==$conditional["value"]);
                            break;
                        case "!=":
                            $OK=($record[$conditional["field"]]!=$conditional["value"]);
                            break;
                        case ">=":
                            $OK=($record[$conditional["field"]]>=$conditional["value"]);
                            break;
                        case "<=":
                            $OK=($record[$conditional["field"]]<=$conditional["value"]);
                            break;
                        case ">":
                            $OK=($record[$conditional["field"]]>$conditional["value"]);
                            break;
                        case "<":
                            $OK=($record[$conditional["field"]]<$conditional["value"]);
                            break;
                    }
                    if ($OK) {$style="style='background-color:".$conditional["color"].";'";break;}
                }
            }
            $html.="<tr data-table='".$parameters["table"]."' data-module='".$parameters["module"]."' data-model='".$parameters["model"]."' data-id='".secureField($record,"id")."' data-pk='xxxx' class='record-dbl-click record-".secureField($record,"id")."' ".$style.">";
            $html.=getTdCheck($parameters,$record,true);
            $html.=getTdEdit($parameters,$record,true);
            foreach ($parameters["columns"] as $column) {$html.=getTdCol($parameters,$record,$column);}
            $html.=getTdDelete($parameters,$record,true);
            $html.=getTdOffline($parameters,$record,true);
            $html.="</tr>";
        }
    }
    $html.="  </tbody>";
    $html.="  <tfoot></tfoot>";
    $html.=" </table>";
    $html.="</div>";
    // CCOO - Agregado
    $html.="<div id='agregado'></div>";
    // CCOO - Fin Agregado
    $html.=$nodata;
    $html.=buildFooterBrowStd($parameters);
    //$html.="<button id='myBtn' onclick='showHola()'>Click Me</button>";

    $boton = lang('b_accept');
    $html.="<hr/>";

    $html.="<div class='form-row'>";
    //$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"id_Cuenta_Bancaria","type"=>"checkbox","checkboxtype"=>"SN","class"=>"form-control text dbase "));
    $html.='Empresa:'."&nbsp".getHtmlResolved($parameters,"controls","id_empresaSucursal",array("col"=>"col-md-4"));
    $html.="</div>";
    
    $html.="<div class='form-row'>";
    //$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"id_Cuenta_Bancaria","type"=>"checkbox","checkboxtype"=>"SN","class"=>"form-control text dbase "));
    $html.='Caja Tesoreria/SACAR:'."&nbsp".getHtmlResolved($parameters,"controls","id_CajaTesoreria",array("col"=>"col-md-4"));
    $html.="</div>";

    $html.="<div class='form-row'>";
    //$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"id_Cuenta_Bancaria","type"=>"checkbox","checkboxtype"=>"SN","class"=>"form-control text dbase "));
    $html.= lang('p_'.'id_Cuenta_Bancaria')."&nbsp".getHtmlResolved($parameters,"controls","id_Cuenta_Bancaria",array("col"=>"col-md-4"));
    $html.="</div>";

    $html.="<div class='form-row'>";
    //$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"id_Cuenta_Bancaria","type"=>"checkbox","checkboxtype"=>"SN","class"=>"form-control text dbase "));
    $html.= 'Fecha Deposito:'."&nbsp".getInput($parameters,array("col"=>"col-md-3","name"=>"fechaDeposito","type"=>"date","class"=>"form-control text dbase validate"));
    $html.="</div>";

    $html.="<hr/>";
    $html.="<div class='row'><a href='#' class='btnAction btnAccept btn btn-success btn-raised pull-right' onclick='procesarDepositos()'>" . $boton . "</a>";
    $html.="<div>Seleccionados: </div>&nbsp &nbsp<div id='cuenta'>0</div></div>";
    $html.="<div id='concatenado'></div>";


}
$html.="</div>";
$html.="<div id='resultado' style='display:none'>";
$html.="</div>";
echo $html;
?>
<script>$('.browser_controls').each(function() {$(this).find('*').addClass('search-trigger');});</script>
<script>$('.multiselect').selectpicker();</script>
<script>$(".comment").shorten();</script>