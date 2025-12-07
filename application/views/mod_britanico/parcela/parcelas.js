
$('body').off('click', '.opcionParcela').on('click', '.opcionParcela', function() {	
    inicializarRadioButtons();
});

$('body').off('change', '.opcionParcela').on('change', '.opcionParcela', function() {	
    let seleccion_estado = $("input:radio[name='SelEstadoParcela']:checked").val();
    let data_adicional="";
    if (seleccion_estado=='ANALISIS'){
        _FUNCTIONS.OnGetAnalisisGestion(null,);
        $('#principal').addClass('d-none'); // Quito la tabla principal
        $('#agregado').html('');
        $('.page-item').addClass('d-none'); // Quito la paginacion
        return false;
    }
    if ($("input:radio[name='SelEstadoParcela']:checked").hasClass("dataAdicional")){
        data_adicional = $("input:radio[name='SelEstadoParcela']:checked").attr("data-adicional");
        if (data_adicional!=null && data_adicional!=undefined && data_adicional!=''){
            $('button.btn-browser-search').attr("data-adicional", data_adicional);  // seteo la data del filtro en los controles de busqueda
            $('a.btn-browser-search').attr("data-adicional", data_adicional);
            $('button.btn-excel-search').attr("data-adicional", data_adicional);  // seteo la data del filtro en los controles de busqueda
            $('a.btn-excel-search').attr("data-adicional", data_adicional);
            $('button.btn-pdf-search').attr("data-adicional", data_adicional);  // seteo la data del filtro en los controles de busqueda
            $('a.btn-pdf-search').attr("data-adicional", data_adicional);            
         }
        $('button.btn-browser-search').each(function () { });
    } else {
            $('button.btn-browser-search').attr("data-adicional", '');
            $('button.btn-excel-search').attr("data-adicional", '');
            $('button.btn-pdf-search').attr("data-adicional", '');
    }
});
$('body').off('click', 'button.btn-browser-search').on('click', 'button.btn-browser-search', function() {	});	

function inicializarRadioButtons() {
    let x = {opcion: "TODAS"};
    $('#rtodas').attr('data-adicional', encodeURIComponent(JSON.stringify(x)));
    x = {opcion: "PROCESO"};  // En Proceso de desenganche
    $('#rproceso').attr('data-adicional', encodeURIComponent(JSON.stringify(x)));
    x = {opcion: "DEVOLUCION"};
    $('#rdevolucion').attr('data-adicional', encodeURIComponent(JSON.stringify(x)));
    x = {opcion: "ABANDONO"};
    $('#rabandono').attr('data-adicional', encodeURIComponent(JSON.stringify(x)));
    x = {opcion: "VIEJAS"};
    $('#rviejas').attr('data-adicional', encodeURIComponent(JSON.stringify(x)));
    x = {opcion: "COMITE"};
    $('#rcomite').attr('data-adicional', encodeURIComponent(JSON.stringify(x)));
    x = {opcion: "ANALISIS"};
    $('#ranalisis').attr('data-adicional', encodeURIComponent(JSON.stringify(x)));
    x = {opcion: "HISTORICAS"};
    $('#rhistoricas').attr('data-adicional', encodeURIComponent(JSON.stringify(x)));
    x = {opcion: "SIN_DETALLE"};
    $('#rsindetalle').attr('data-adicional', encodeURIComponent(JSON.stringify(x)));
}
