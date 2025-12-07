<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
    $.getScript('./application/views/mod_britanico/Gestion_cobranza/Gestion_cobranza.js', function() {
    });

</script>

<!-- Aca esta el contenedor para la modal myModalGenerica, conviene que este al principio de todo. -->
<div id='contenidoGenerico'></div>

<div class="container-full marco p-1">
    <div class="row mx-0 shadow rounded" style="background-color:whitesmoke;">
        <div class="col-12 pt-1 m-0">
            <h1 class="m-0 p-0" style="font-weight:bold;color:rgb(0, 71, 186);"><?php echo $title;?></h1>
        </div>
    </div>
    <div class="container form-row">
        <!-- <div class="row browser_controls" style="padding-right:5px;display:inline;"> -->
        <div class="row browser_controls">
            <label class="search-trigger">Fecha de consulta:</label><input type="date" id="fechaActividad" name="fechaActividad" class="form-control text" value="<?php echo date('Y-m-d', strtotime('-1 month')) ?>"/>
            <input id='actividad' name='actividad' type='button' class='button' value='Actividad en Notas'  onclick='javascript:VerActividadNotas();' />
        </div>
    </div>
    <br />
    <br />
    <?php
        $a="<table>";
        $a.="<tr>";    
        foreach($_ci_vars["gestionCobranzaSegPorAntiguedad"] as $key => $value){
            //$a.=$value["id_segmento"] . " ". $value["descripcion"] . " " . $value["minimo"]. " " . $value["maximo"];
            $a.= "<td>";
            $a.= "<input style='background-color:" . "lightblue" . "' id='btn" . $value["id_segmento"] . "' name='btn" . $value["id_segmento"] . "' type='button' class='button' value='" . $value["descripcion"] . "' onclick='javascript:changeSegmento(" . $value["id_segmento"] . ");'/>";    
            $a.= "<input id='minimo" . $value["id_segmento"] . "' name='minimo" . $value["id_segmento"] . "' type='hidden' value='" . $value["minimo"] . "'/>";
            $a.= "<input id='maximo" . $value["id_segmento"] . "' name='maximo" . $value["id_segmento"] . "' type='hidden' value='" .  $value["maximo"] . "'/>";
            $a.= "</td>";
        }
        $a.="</tr>";    
        $a.="</table>";
        
        echo $a;
    ?>
    <!-- <hr/>
    <div class="row">
        <div class="col-12" style="padding-top:15px;">
            <a href="#" class="btnAction btnAccept btn btn-success btn-raised pull-right" onclick="showReport();">/*<?php echo lang('b_accept');?>*/</a>
        </div>
    </div>
    <hr/> -->
    <!-- Aca pongo el reporte -->
    <div id="REPORT-CONTAINER" class="row m-2 p-2">   
    </div>    
</div>
