<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-full marco p-1">
    <div class="row mx-0 shadow rounded" style="background-color:whitesmoke;">
        <div class="col-12 pt-1 m-0">
            <h4 class="m-0 p-0" style="font-weight:bold;color:rgb(0, 71, 186);"><?php echo $title;?></h4>
            <h5 class="m-0 p-0" style="font-weight:bold;color:rgb(0, 71, 186);">Generación directa de PDF</h5>
        </div>
    </div>
    <div id="selectAnio" class="form-row"></div>
    <div id="SALDO-CONTAINER" class="row m-2 p-2"></div>
    <div id="REPORT-CONTAINER" class="row m-2 p-2"></div>
    <hr/>
    <div class="row">
        <div class="col-12" style="padding-top:15px;">
            <a href="#" class="btnAction btnAccept btn btn-success btn-raised pull-right d-none" onclick="showReport();"><?php echo lang('b_accept');?></a>
        </div>
    </div>
</div>


<script>
    $.getScript('./application/views/mod_britanico/Avisos/avisos.js', function() {
    getYears( <?php $param = '"'.$parameters["permiteGenerar"].'"'; 
                    echo $param;
            ?> );
    GetEmailStatus_CEM();
});
</script>