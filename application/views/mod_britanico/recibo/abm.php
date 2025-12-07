<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.="<div>";
$html.="<div><b>Seleccione Pagador: </b></div>";
// Aca escribo nombre del pagador para realizar la busqueda, y traer la CC
$html.="<input class='search_query form-control dbase' type='text' name='keyPagador' id='entradaPagadorRecibo' placeholder='...Buscar Pagador (al menos 2 letras)...' size='60' data-idpagador=''>";
$html.="<div id='resultadoPagadorRecibo'></div>";
$html.="</div>";

// Datos del recibo
$html.="";


// Aca pongo la CC
$html.="<div id='contenidoGenerico'></div>";
$html.="<div id='myModal2'></div>";
// Detalle de Valores a seleccionar
$html.= "<div id='detalleDeValores'></div>";

// Detalle de Valores Cargados

// Detalle Total

// Boton Guardar Recibo

//$html.="<input id='mibotonaceptar' class='apretarBoton btn-success' type='button' value='Grabar Recibo'>";

// Boton cancelar o Imprimir
$html.="<input id='mibotonaceptar' class='apretarBoton btn-success' type='button' value='Grabar Recibo' >";
$html.="<input id='mibotoncancelar' class='btn-danger btn-abm-cancel' type='button' value='Cancelar'>";

$footer.=buildFooterAbmStd($parameters);
// Quito el boton de aceptar que esta por default
$botonOK="<button type='button' class='btn-raised btn-abm-accept btn btn-success' data-id='0'data-module='mod_britanico'data-model='Recibo'data-table='vw_parcela'><i class='material-icons'>done</i></span>Aceptar</button>";
//'<button type="button" class="btn-raised btn-abm-accept btn btn-success" data-id="0" data-module="mod_britanico" data-model="Recibo" data-table="vw_parcela"><i class="material-icons">done</i>Aceptar</button>'
$botonCancel="<button type='button' class='btn-raised btn-abm-cancel btn btn-danger'><i class='material-icons'>not_interested</i></span>Cancelar</button>";

// Quito los botones del footer que ya contempla la vista del abm por default
$footerA = str_replace($botonOK,
                    "",
                    $footer);
$footerB = str_replace($botonCancel,
                    "",
                    $footerA);                   
$html.=$footerB;

//$html.=$footer;

echo $html;
?>
<script>
    $.getScript('./application/views/mod_britanico/parcela/abm.js', function() {
    });
    $.getScript('./application/views/mod_britanico/recibo/recibo.js', function() {
        // Aca arranca la ventana, tanto el render como los datos
        _FUNCTIONS.OnGetDatosVariosRecibos(null, {});
    });
</script>
