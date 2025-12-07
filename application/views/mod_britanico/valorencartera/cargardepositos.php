
<?php
defined('BASEPATH') or exit('No direct script access allowed');
$month = date('m');
$year = date('Y');
?>

<script>
    $.getScript('./application/views/mod_britanico/valorencartera/cargardepositos.js', function() {});
</script>

<div class="container-full marco p-1">
    <div class="form-row">
        <div class="col-12 pt-1 m-0">
            <h4 class="m-0 p-0" style="font-weight:bold;color:rgb(0, 71, 186);">Carga de depósitos bancarios</h4>
        </div>
        <div class="col-md-6">
            <label style="font-weight:bold;" for="EMPRESA">Empresa</label>
            <?php echo getHtmlResolved($parameters, "controls", "id_empresa", array("col" => "col-md-12", "nolabel" => true)) ?>
        </div>
        <div class="col-md-6">
            <label style="font-weight:bold;" for="CAJA">Caja</label>
            <?php echo getHtmlResolved($parameters, "controls", "id_caja_tesoreria", array("col" => "col-md-12", "nolabel" => true)) ?>
        </div>
    </div>
    <div class="form-row mt-2">
        <div class="col-md-4">
            <label style="font-weight:bold;" for="LNAO">Listar NO a la orden?</label>
            <input data-type="checkbox" checked autocomplete="nope" checkboxtype="SN" value="S" class=" text dbase " type="checkbox" name="LNAO" id="LNAO" >
        </div>
        <div class="col-md-4">
            <label style="font-weight:bold;" for="VC">Ver cheques?</label>
            <input data-type="checkbox" checked autocomplete="nope" checkboxtype="SN" value="N" class=" text dbase " type="checkbox" name="VC" id="VC" >
        </div>
        <div class="col-md-4">
            <label style="font-weight:bold;" for="VE">Ver efectivo?</label>
            <input data-type="checkbox" checked autocomplete="nope" checkboxtype="SN" value="N" class=" text dbase " type="checkbox" name="VE" id="VE" >
        </div>
        <div class="col-md-4">
            <label style="font-weight:bold;" for="VVFC">Ver valores fisicamente en CHACARITA?</label>
            <input data-type="checkbox"  autocomplete="nope" checkboxtype="SN" value="N" class=" text dbase " type="checkbox" name="VVFC" id="VVFC" >
        </div>
        <div class="col-md-4">
            <label style="font-weight:bold;" for="VVFN">Ver valores fisicamente en NOGUES?</label>
            <input data-type="checkbox"  autocomplete="nope" checkboxtype="SN" value="N" class=" text dbase " type="checkbox" name="VVFN" id="VVFN" >
        </div>
    </div>
    <div class="form-row mt-2">
        <div class="col-4 browser_controls" style="padding-right:5px;display:inline;">
            <label class="search-trigger">Fecha Desde</label>
            <input type="date" id="TB-fDesde" name="TB-fDesde" class="form-control text" value="<?php echo date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)) ?>"/>
        </div>
        <div class="col-4 browser_controls" style="padding-right:5px;display:inline;">
            <label class="search-trigger">FechaHasta</label>
            <input type="date" id="TB-fHasta" name="TB-fHasta" class="form-control text" value="<?php echo date('Y-m-d') ?>"/>
        </div>
        <div class="col-4" style="padding-top:15px;">
            <a href="#" class="btnAction btnAccept btn btn-success btn-raised pull-right" onclick="showReport();">Buscar</a>
        </div>
    </div>
    <br />
    <br />
    <div id="REPORT-CONTAINER" class="row m-2 p-2"></div>
    <hr/>
</div>


