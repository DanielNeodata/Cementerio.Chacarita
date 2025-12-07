<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$html.=buildHeaderBrowStd($parameters,$title);
if (!isset($parameters["records"])) {
    $html.=getUnInitialized();
} else {
    $nodata=getNoData();

    $html.="<div class='container'>";

    $html.="    <div class='row seleccionParcela'>";

    $html.="        <div class='col'></div>";
    $html.="        <div class='col'>";

    // con la clase dataAdicional marco que deberia haber datos en data-adicional de los radiobutton
    $html.="            <input id='rtodas' type='radio' class='opcionParcela dataAdicional' value='TODAS' name='SelEstadoParcela'><label>Todas</label>";
    $html.="        </div>";

    $html.="        <div class='col'>";
    $html.="            <input id='rproceso' type='radio' class='opcionParcela dataAdicional' value='PROCESO' name='SelEstadoParcela'><label>En Proceso de desenganche</label>";    
    $html.="        </div>";

    $html.="        <div class='col'>";
    $html.="            <input id='rdevolucion' type='radio' class='opcionParcela dataAdicional' value='DEVOLUCION' name='SelEstadoParcela'><label>Desenganchadas Devolucion</label>";
    $html.="        </div>";

    $html.="        <div class='col'>";
    $html.="            <input id='rabandono' type='radio' class='opcionParcela dataAdicional' value='ABANDONO' name='SelEstadoParcela'><label>Desenganchadas Abandono</label>";
    $html.="        </div>";

    $html.="        <div class='col'>";
    $html.="            <input id='rviejas' type='radio' class='opcionParcela dataAdicional' value='VIEJAS' name='SelEstadoParcela'><label>Desenganchadas Viejas</label>";
    $html.="        </div>";


    $html.="        <div class='col'>";
    $html.="            <input id='rcomite' type='radio' class='opcionParcela dataAdicional' value='COMITE' name='SelEstadoParcela'><label>Estado Acta Comite</label>";
    $html.="        </div>";

    $html.="        <div class='col'>";
    $html.="            <input id='ranalisis' type='radio' class='opcionParcela dataAdicional' value='ANALISIS' name='SelEstadoParcela'><label>Analisis Gestion</label>";
    $html.="        </div>";

    $html.="        <div class='col'>";
    $html.="            <input id='rhistoricas' type='radio' class='opcionParcela dataAdicional' value='HISTORICAS' name='SelEstadoParcela'><label>Historicas</label>";
    $html.="        </div>";

    $html.="        <div class='col'>";
    $html.="            <input id='rsindetalle' type='radio' class='opcionParcela dataAdicional' value='SIN_DETALLE' name='SelEstadoParcela'><label>Sin Detalle</label>";
    $html.="        </div>";
    //$html.="    </form>";
    $html.="    </div>";

    $html.="</div>";

    $html.="<div class='body-browser d-flex border-light m-0 p-0 rounded shadow-sm'>";
    $html.=" <table id='principal' class='table table-hover table-sm table-browser m-0 p-0' style='min-width:750px;width:100%;'>";
    $html.=buildBodyHeadBrowStd($parameters);



    $html.="<tbody>";
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
}
echo $html;
?>
<script>$('.browser_controls').each(function() {$(this).find('*').addClass('search-trigger');});</script>
<script>$('.multiselect').selectpicker();</script>
<script>$(".comment").shorten();</script>
<script>
    $.getScript('./assets/js/FUNCTIONS.js').done(function(script, text) {

    });
</script>
<script>
    $.getScript('./assets/js/TOOLS.js').done(function(script, text) {

    });
</script>
<script>
    $.getScript('./application/views/mod_britanico/parcela/parcelas.js', function() {
    });
</script>