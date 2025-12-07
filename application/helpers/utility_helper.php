<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md

/**
 * Helper que arma Digito Verificador de Pago Facil
 *
 * Implementacion del algoritmo del Digito Verificador de Pago Facil como Helper
 *
 * @see new_function_name()
 *
 * @param string Mandatorio. string como Numero para hacer el DV
 * @return int DV
 */
function DigitoVerificadorPagoFacil(string $arg_number) : int {
    // https://gist.github.com/jplarrea/08a41ac87ddb671c8b847cf9c4db27eb
    
    // recibe string para que no recorte ceros

    $sumaProd = 0;

    //Paso 1: Comenzando por el primer dígito del string numérico, asignarle la secuencia 1, 3, 5, 7, 9; y luego 3, 5, 7, 9 hasta completar la longitud total del mismo.
    $patron = "1" . str_repeat("3579", ceil(strlen($arg_number)/4));

    //Misma longitud
    $patron = substr($patron,0,strlen($arg_number));

    //Paso 2: Realizar el producto de cada elemento de la secuencia por el elemento correspondiente del string a verificar.
    //Paso 3: Sumar todos los productos.
    foreach (str_split($patron) as $key => $value)
        $sumaProd += $value * $arg_number[$key];

    //Paso 4: Dividir el resultado de la suma por 2.
    $resDiv = $sumaProd / 2;

    //Paso 5: Tomar la parte entera del paso 4 y dividirla por 10. El resto de esta división (modulo 10) será el dígito verificador.
    $digitoVerificador = intval($resDiv) % 10;

    return $digitoVerificador;

}

/**
 * Helper que genera Digitos Verificadores de Pago Facil
 *
 * Pago facil tiene doble digitio verificador, al string que es dato, le aplico el algoritmo, me da un digito verificador
 * Al string original mas el digito verificador le aplico otra vez el algoritmo y la resultante es
 * STRING ORIGINAL + DV1 + DV2
 * DV1: Digito del STRING ORIGINAL
 * DV2: digito de (STRING ORIGINAL + DV1)
 *
 * @link https://gist.github.com/jplarrea/08a41ac87ddb671c8b847cf9c4db27eb
 * 
 * @param string $code Mandatorio. Numero
 * @return string DV
 */
function DigitosVerificadoresPagoFacil($code) : string {
    // recibe string para que no recorte ceros
    $dv1 = 0;
    $dv2 = 0;
    
    $dv1 = DigitoVerificadorPagoFacil($code);
    $dv2 = DigitoVerificadorPagoFacil($code . strval($dv1));

    return $code . strval($dv1) . strval($dv2);  // devuelvre string para que no recorte ceros

}

/**
 * Dado los datos, generar la URL que va a figurar en los mails de aviso
 *
 * Con los datos: $idPagador, int $anio, int $mes, string $empresa
 * 
 * generar la URL que va a figurar en los mails de aviso
 *
 * @param int $idPagador. Si 0, es para todos
 * @param int $anio. Del resumen actual generado y en curso
 * @param int $mes. Del resumen actual generado y en curso
 * @param string $empresa. B Britanico/Chacarita, N Nogues
 * @return string URL
 */
function generateCodigoPagofacil(string $numero_pagador, string $valor, string $anio, string $days) : string {
    $a = "";
    $strbarcode = "";

    $strbarcode = "1434"; // fijo! pagos PyMe
    $strbarcode .= str_pad( str_replace(".", "", str_replace(",", "", $valor)), 8, "0", STR_PAD_LEFT); // "00000000" importe en centavos
    $strbarcode .= substr($anio, 2, 2) . str_pad($days, 3, "0", STR_PAD_LEFT); // AADDD fecha 1º vence
    $strbarcode .= "00806"; // Identificacion DE LA EMPRESA -- Pareciera ser este número.!!!!
    $strbarcode .= "9"; // fijo! tipo generacion
    $strbarcode .= str_pad($numero_pagador, 8, "0", STR_PAD_LEFT); // _numero_pagador "00000000"
    $strbarcode .= "0"; // fijo! moneda
    $strbarcode .= "000000"; // recargo 1º vto "000000"
    $strbarcode .= "99"; // + dias 2º vto "DD"

    $codigoPagoFacil = DigitosVerificadoresPagoFacil($strbarcode);
    $code= ($a . $codigoPagoFacil);
    return $code;
}

/**
 * Simula el str_contains que es propia de PHP8
 *
 * Devuelve true si la aguja se encuentra en el pajar
 *
 * @since x.x.x
 * @see new_function_name()
 *
 * @param string Mandatorio. Pajar
 * @param string Mandatorio. Aguja
 * @return boolean
 */    

function str_contains2(string $haystack, string $needle): bool
{
    // Funcion 
    return ( '' === $needle || false !== ( strpos($haystack, $needle) ) );
}


/*
function mb_detect_encoding($string, $enc=null) {

    // https://www.php.net/manual/es/function.mb-detect-encoding.php
    if(!function_exists('mb_detect_encoding')) {       
        static $list = array(          'UTF-8', 'ASCII',
        'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
        'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
        'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
        'Windows-1251', 'Windows-1252', 'Windows-1254',);
        
        foreach ($list as $item) {
            $sample = iconv($item, $item, $string);
            if (md5($sample) == md5($string)) {
                if ($enc == $item) { return true; }    else { return $item; }
            }
        }
        return null;
    }
}
*/

/**
 * Número de día o Juliano de la fecha pasada como argumento
 *
 * Devuelve el número de día del año de la fecha pasada como argumento 
 *
 * @since x.x.x
 * @see new_function_name()
 *
 * @param DateTime fecha. Fecha Argumento
 * @param string Mandatorio. Aguja
 * @return int numero de dias
 */  
function getNumberOfDaysInYearDate(DateTime $fecha) {

    $today = new DateTime();
    $jan1 = new DateTime('January 1');
    //$jan1->modify('+1 year');
    //$days = $today->diff($jan1)->days;
    $days = $fecha->diff($jan1)->days; // Dias  desde el 1 de enero del año de vencimiento
    $days += 1;

    return $days;
}

/**
 * Fecha string con el ultimo dia del mes
 *
 * Devuelve la fecha del ultimo dia del mes de la fecha argumento en formato string YYYY-MM-DD 
 *
 * @since x.x.x
 * @see new_function_name()
 *
 * @param DateTime fecha. Fecha Argumento
 * @return string Fecha string Ultimo dia del mes YYYY-MM-DD
 */    
function getDateLastDayOfMonth(DateTime $fecha) {
   return $fecha->format( 'Y-m-t' );
}

/**
 * Trae Texto y Margenes por clave
 *
 * Devuelve texto y margenes por clave y uso. Util para recuperar los texto configurados. Retorna una array asociativo
 * que contiene en el elemento "original" los elementos originales y otro en el elemento "reemplazado" todo los que aparezca en los
 * tags de inicio y cierre que hagan referencia a !Width!, !Height!, !PosX!, !PosY!
 * 
 * Uso/Etiqueta:
 * ----------------------------
 * Conviene mantener el nombre de los tags, y en caso de tener que generar diferentes comunicaciones para una misma cosa
 * multiplicar Uso con nombres diferentes.
 * ----------------------------
 * **MailAviso -> Uso
 * MailAvisoPagoMensTitulo: sin tags
 * MailAvisoPagoMensCuerpo: _HASHAVISO_
 * **FormAviso  -> Uso
 * FormAvisoPagina: sin tags
 * FormAvisoCabe1: _NUMEROPAGADOR_  _NOMBREPAGADOR_
 * FormAvisoCabe2: _DOMICILIO_  _CODIGOPOSTAL_
 * FormAvisoCabe3: _PROVINCIA_ _PAIS_
 * FormAvisoConcepto: _DETALLEDEUDA_
 * FormAvisoFechaAviso: _FECHAVISO_
 * FormAvisoVenc: _FECHAVENCIMIENTO_
 * FormAvisoTotal: _IMPORTETOTAL_
 * FormAvisoPie: _NUMEROPAGADORPADEADO_  _BARCODE_
 * 
 * @param string $Etiqueta. Cual es el dato que busco. Por ej MailAvisoPagoMensCuerpo
 * @param string $uso. Clase de uso. Conforma la clave compuesta de busqueda. Por ej MailAviso
 * @return array array(
 *                  "original"    => Lo original con los siguientes elementos en ele array: [Etiqueta],[Uso],[Width],[Height],[PosX],[PosY],[Unidades],[Contenido],[tagInicio],[tagCierre],
 *                  "reemplazado" => con los tags propios reemplazados
 *              );
 */    
function getTextoMargenesPorClave(string $etiqueta, string $uso) : array {

    $sqlTextosMargenes = "spGetTextosMargenes ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?;";

    $prmTexto = array(
        "Uso"=>$uso,
        "Etiqueta"=>$etiqueta,
        "Cliente"=>'CLI',
        "Estado"=>'A',
        "PosX"=>null,
        "PosY"=>null,
        "Width"=>null,
        "Height"=>null,
        "Unidades"=>null,
        "Contenido"=>null,
        "TagInicio"=>null,
        "TagCierre"=>null,
    );

    $CI = &get_instance();

    $rc = $CI->db->query($sqlTextosMargenes, $prmTexto);

    if (!$rc) {

        // si dio false estoy en problemas.....hacer un throw o raise...
        $error = $CI->db->error();
        throw new Exception($error['message'], $error['code']);
    } else {
        $resultado = $rc->result_array();
        $reemplazado=array();
        $reemplazosMetadata = array();
        
        $reemplazar = false;
        // hago los reemplazos con los tags donde tengo valor seteado
        if ( !($resultado[0]["Width"] === NULL) ) {
            $reemplazosMetadata["!Width!"] = $resultado[0]["Width"];
            $reemplazar=true; 
        }
        if ( !($resultado[0]["Height"] === NULL) ) {
            $reemplazosMetadata["!Height!"] = $resultado[0]["Height"];
            $reemplazar=true; 
        }
        if ( !($resultado[0]["PosX"] === NULL) ) {
            $reemplazosMetadata["!PosX!"] = $resultado[0]["PosX"];
            $reemplazar=true; 
        }
        if ( !($resultado[0]["PosY"] === NULL) ) {
            $reemplazosMetadata["!PosY!"] = $resultado[0]["PosY"]; 
            $reemplazar=true;
        }
        $tags_template = array_keys($reemplazosMetadata);
        $datos_reemplazo = array_values($reemplazosMetadata);
        
        // Asi me aseguro que $reemplazado sea identico a $resultado, pero con el reemplazo de los tags donde corresponda.
        $reemplazado = $resultado[0];

        if ($reemplazado["TagInicio"]==="") {
            $tempo["TagInicio"] = null;
        } else {
            $tempo["TagInicio"] = str_replace($tags_template, $datos_reemplazo, $reemplazado["TagInicio"]);    
        }
        if ($reemplazado["TagCierre"]==="") {
            $tempo["TagCierre"] = null;
        } else {
            $tempo["TagCierre"] = str_replace($tags_template, $datos_reemplazo, $reemplazado["TagCierre"]);
        }
        if ($reemplazado["Contenido"]===""){
            $tempo["Contenido"] = null;
        } else {
            $tempo["Contenido"] = str_replace($tags_template, $datos_reemplazo, $reemplazado["Contenido"]);
        }
        
        $reemplazado["TagInicio"] = $tempo["TagInicio"];
        $reemplazado["TagCierre"] = $tempo["TagCierre"];
        $reemplazado["Contenido"] = $tempo["Contenido"];
        
        $salida = array(
                        "original"=> $resultado[0],
                        "reemplazado"=>$reemplazado,
                    );
        return $salida;
    }
}