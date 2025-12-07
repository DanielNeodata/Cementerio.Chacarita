

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
    $.getScript('./application/views/mod_britanico/Cashflow/Cashflow.js', function() {
    });

</script>

<div class="container-full marco p-1">
    <div class="row mx-0 shadow rounded" style="background-color:whitesmoke;">
        <div class="col-12 pt-1 m-0">
            <h1 class="m-0 p-0" style="font-weight:bold;color:rgb(0, 71, 186);"><?php echo $title;?></h1>
        </div>
    </div>
    <div class="form-row">
        <div class="browser_controls" style="padding-right:5px;display:inline;">
            <label class="search-trigger">Desde</label>
            <input type="date" id="TB-aDesde" name="TB-aDesde" class="form-control text" value="<?php echo date('Y-m-d', strtotime('-1 month')) ?>"/>
        </div>
    </div>
    <div class="form-row">
        <div class="browser_controls" style="padding-right:5px;display:inline;">
            <label class="search-trigger">Hasta</label>
            <input type="date" id="TB-aHasta" name="TB-aHasta" class="form-control text" value="<?php echo date('Y-m-d') ?>"/>
        </div>
    </div>
         <div class="form-row" style="display:none;">

            <div class="col-md-8">
                <label for="LBL-Destino" style="font-weight:bold;">Destino</label>
                &nbsp;&nbsp;
                <label for="DESTINO_I"> Excel </label>
                <input id="DESTINO_I" type="radio" name="DESTINO" value="E" >
                &nbsp;&nbsp;
                <label for="DESTINO_P">Pantalla</label>
                <input id="DESTINO_P" type="radio" name="DESTINO" value="P" checked>
            </div>
        
        </div>

     
    <br />
    <br />
    <div id="REPORT-CONTAINER" class="row m-2 p-2">
        
    </div>

    <hr/>
    
    <hr/>
    <div class="row">
        <div class="col-12" style="padding-top:15px;">
            <a href="#" class="btnAction btnAccept btn btn-success btn-raised pull-right" onclick="showReport();"><?php echo lang('b_accept');?></a>
        </div>
    </div>
</div>
</div>

