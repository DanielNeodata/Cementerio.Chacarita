function showHola() {}

function procesarDepositos() {

    let emp = $( "#id_empresaSucursal" ).val();
    let caja = $( "#id_CajaTesoreria" ).val();
    let cuenta = $( "#id_Cuenta_Bancaria" ).val();
    let fecha = $( "#fechaDeposito" ).val();
    let concatenado = $( "#concatenado" ).text();

    data = {id_empresaSucursal: emp, id_CajaTesoreria: caja, id_Cuenta_Bancaria: cuenta, fechaDeposito: fecha, idConcatenado: concatenado};
    alert('Procesar: ' + emp + " " + caja + " " + cuenta + " " + fecha + " " + concatenado);
    var myObj = data;
    _AJAX.UiProcesarDepositos(myObj).then(function (datajson) {
        $('#resultado').text("Deposito Grabado");
        $('#resultado').show();
        $('#principal').hide();
    });
}
function concatenar() {}

$(document).ready(function(){
    var $checkboxes = $('.table-depositos td p input[type="checkbox"]');
    $checkboxes.change(function(){
        var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
        let _selected = new Array();
        let checkedCheckboxes = $checkboxes.filter(':checked').each(function() {_selected.push(this.value); });
        if (_selected.length > 0) {
            let x = _selected.join(",");
            $('#concatenado').text(x);
            alert("Selected values: " + x);
        }
        $('#cuenta').text(countCheckedCheckboxes);
    });
});