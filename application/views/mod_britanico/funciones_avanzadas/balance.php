<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$month = date('m');
$year = date('Y');
?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
    $.getScript('./application/views/mod_britanico/funciones_avanzadas/balance.js', function() {});
</script>

<div class="container-full marco p-1">
    <div class="row mx-0 shadow rounded" style="background-color:whitesmoke;">
        <div class="col-12 pt-1 m-0">
            <h1 class="m-0 p-0" style="font-weight:bold;color:rgb(0, 71, 186);"><?php echo $title;?></h1>
        </div>
    </div>
    <div class="form-row">
        <div class="browser_controls" style="padding-right:5px;display:inline;">
            <label class="search-trigger">Fecha Desde</label>
            <input type="date" id="TB-fDesde" name="TB-fDesde" class="form-control text" value="<?php echo date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)) ?>"/>
        </div>
    </div>
    <div class="form-row">
        <div class="browser_controls" style="padding-right:5px;display:inline;">
            <label class="search-trigger">FechaHasta</label>
            <input type="date" id="TB-fHasta" name="TB-fHasta" class="form-control text" value="<?php echo date('Y-m-d') ?>"/>
        </div>
    </div>


    <div class="form-row">
        <div class="browser_controls" style="padding-right:5px;display:inline;">
            <?php echo getHtmlResolved($parameters, "controls", "id_cuenta_desde", array("col" => "col-md-12")); ?>
        </div>
    </div>
    <div class="form-row">
        <div class="browser_controls" style="padding-right:5px;display:inline;">
            <?php echo getHtmlResolved($parameters, "controls", "id_cuenta_hasta", array("col" => "col-md-12")); ?>
        </div>
    </div>

         <div class="form-row">

            <div class="col-md-8">
                <label for="LBL-Destino" style="font-weight:bold;">Destino</label>
                &nbsp;&nbsp;
                <label for="DESTINO_I"> Impresora </label>
                <input id="DESTINO_I" type="radio" name="DESTINO" value="I">
                &nbsp;&nbsp;
                <label for="DESTINO_E">Excel</label>
                <input id="DESTINO_E" type="radio" name="DESTINO" value="E">
                &nbsp;&nbsp;
                <label for="DESTINO_P">Pantalla</label>
                <input id="DESTINO_P" type="radio" name="DESTINO" value="P" checked>
            </div>
        
        </div>

    <div class="form-row" style="display:none;">

            <div class="col-md-8">
                <label for="LBL-Adicionales" style="font-weight:bold;">Nivel de detalle</label>
                &nbsp;&nbsp;
                <label for="ADICIONALES_B"> Rubros y Cuentas </label>
                <input id="ADICIONALES_B" type="radio" name="ADICIONALES" value="B">
                &nbsp;&nbsp;
                <label for="ADICIONALES_C">Cuentas</label>
                <input id="ADICIONALES_C" type="radio" name="ADICIONALES" value="C" checked>
                &nbsp;&nbsp;
                <label for="ADICIONALES_R">Rubros</label>
                <input id="ADICIONALES_R" type="radio" name="ADICIONALES" value="R" >
            </div>
        
        </div>

        <div class="form-row">    

            <div class="col-md-3" style="padding-right:5px;display:inline;">
                <label class="search-trigger" style="font-weight:bold;">Prefijo</label>
                <input type="text" id="TB-PREFIJO" name="TB-PREFIJO" class="form-control text" value=""/>
            </div>
            <br />

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


