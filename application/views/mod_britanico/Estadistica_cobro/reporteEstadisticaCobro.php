<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
    $.getScript('./application/views/mod_britanico/Estadistica_cobro/reporteEstadisticaCobro.js', function() {
    });

</script>

<div class="container-full marco p-1">
    <div class="row mx-0 shadow rounded" style="background-color:whitesmoke;">
        <div class="col-12 pt-1 m-0">
            <h1 class="m-0 p-0" style="font-weight:bold;color:rgb(0, 71, 186);"><?php echo $title;?></h1>
        </div>
    </div>
    <div class="container">
        <!-- <div class="row browser_controls" style="padding-right:5px;display:inline;"> -->
        <div class="row">
        <?php 
            $hoy = date('Y-m-d');

            $mes =  "<select id='mes' name='mes'>".
                    "      <option value='1'>enero</option>".
                    "      <option value='2'>febrero</option>".
                    "      <option value='3'>marzo</option>".
                    "      <option value='4'>abril</option>".
                    "      <option value='5'>mayo</option>".
                    "      <option value='6'>junio</option>".
                    "      <option value='7'>julio</option>".
                    "      <option value='8'>agosto</option>".
                    "      <option value='9'>septiembre</option>".
                    "      <option value='10'>octubre</option>".
                    "      <option value='11'>noviembre</option>".
                    "      <option value='12'>diciembre</option>".                    
                    "   </select>";

            $anioActual = date('Y');
            $d=$anioActual-2;
            $h=$anioActual+1;
            // $anio=  "Año:<select id='anio' name='anio'>".
            //         "       <option value='2019'>2019</option>".
            //         "       <option value='2020'>2020</option>".
            //         "       <option value='2021'>2021</option>".
            //         "       <option value='2022'>2022</option>".
            //         "       <option value='2023'>2023</option>".                    
            //         "   </select>";   
            $anio=  "<select id='anio' name='anio'>";
            for ($i=$d; $i<=$h; $i++) {
                $anio.="<option value='".$i."'>".$i."</option>";
            }    
            $anio.="</select>";         

            echo "<div>Mes:</div><div>".$mes."</div>";
            echo "<div>Año:</div><div>".$anio."</div>";
        ?>
        </div>
        <div class='row'>
            <div> 
                <input id='porAviso' name='porAviso' type='button' class='button' value='Por Aviso'  onclick='javascript:VerPorAvisos();' />
            </div>
            <div>                    
                <input id='porCobranza' name='porCobranza' type='button' class='button' value='Por Cobranza'  onclick='javascript:VerPorCobranzaAsync();' />
            </div>
        </div>
    </div>
    <br />
    <br />
    <?php
    ?>

    <div id="REPORT-CONTAINER" class="row m-2 p-2">   
    </div>    
    <div class='container'>
        <div class='row'><b>Total:</b>&nbsp $<div class='totalizador'>0</div></div>
    </div>
</div>
