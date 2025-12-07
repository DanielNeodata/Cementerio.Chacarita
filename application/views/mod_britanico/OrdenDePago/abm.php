<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-1 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<div id=form-detail></div>";

$html.="<script>";
$html.="_FUNCTIONS.onGetOrdenDePagoEditBaseData($(this));"; // Esto esta bien aca o lo paso a un evento ready?
$html.="</script>";
$html.="</form>";
$html.="</div>";
$html.="<div id='contenidoGenerico'></div>";
// Aca cuelgo todo
$html.="<div id='detalleDeValores'></div>";
// Boton cancelar o Imprimir
$html.="<input id='mibotonaceptar' class='apretarBotonOP btn-success' type='button' value='Grabar OP' onClick='botonGuardarOP();'>";
$html.="<input id='mibotoncancelar' class='btn-danger btn-abm-cancel' type='button' value='Cancelar'>";

$footer=buildFooterAbmStd($parameters);
//$html.='<script type="text/javascript"> $(".btn-abm-accept").prop("disabled", true);</script>';

// Quito el boton de aceptar que esta por default
$botonOK="<button type='button' class='btn-raised btn-abm-accept-confirm btn btn-success' data-id='0'data-module='mod_britanico'data-model='OrdenDePago'data-table='OrdenDePago'><i class='material-icons'>done</i></span>Aceptar</button>";
$botonCancel="<button type='button' class='btn-raised btn-abm-cancel btn btn-danger'><i class='material-icons'>not_interested</i></span>Cancelar</button>";
// Quito los botones del footer que ya contempla la vista del abm por default
$footer = str_replace($botonOK,"",$footer);
$footer = str_replace($botonCancel,"",$footer);                   
$html.=$footer;
echo $html;
?>

<script>
    $.getScript('./assets/js/FUNCTIONS.js').done(function (script, text) {
        $.getScript('./application/views/mod_britanico/OrdenDePago/abm.js', function () {
            $.getScript('./application/views/mod_britanico/recibo/recibo.js').done(function (script, text) {
                $.getScript('./application/views/mod_britanico/OrdenDePago/OrdenDePago.js').done(function (script, text) {
                    _FUNCTIONS.OnGetDatosVariosOP(null, {});
                    $('.btn-abm-accept-confirm').addClass('d-none');
                });
            });

        });
    });
</script>



