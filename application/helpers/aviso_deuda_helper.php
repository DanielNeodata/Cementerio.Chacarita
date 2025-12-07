<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Resumen de Deuda para el aviso mensual
 *
 * Devuelve el Resumen de Deuda para el aviso mensual
 *
 * @param int $idPagador. Si 0, es para todos
 * @param int $anio. Del resumen actual generado y en curso
 * @param int $mes. Del resumen actual generado y en curso
 * @param string $empresa. B Britanico/Chacarita, N Nogues
 * @param string $salida  I impresa, E electronico (default = I)
 * @return array Html del resumen con la deuda, incluye barcode Pago Facil ("html","paginas","caracteres","skips","registros")
 * 
 */
function generateAvisoResumenAsHtml(int $idPagador, int $anio, int $mes, string $empresa, int $filtro) : array {

    $CI = &get_instance();

    $dbBritanico = 'neo_britanico'; // de config/database.php
    $dbNogues = 'neo_nogues';
    $dbEmpresa = "";
    $logo = "";
    if ((int)$filtro == 0) {$logo = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAPoAAAA3CAMAAAASRKdlAAAC6FBMVEUAAAD////+/v79/f38/Pz7+/v6+vr5+fn4+Pj39/f29vb19fX09PTz8/Py8vLx8fHw8PDv7+/u7u7t7e3s7Ozr6+vq6urp6eno6Ojn5+fm5ubl5eXk5OTj4+Pi4uLh4eHg4ODf39/e3t7d3d3c3Nzb29va2trZ2dnY2NjX19fW1tbV1dXU1NTT09PS0tLR0dHQ0NDPz8/Ozs7Nzc3MzMzLy8vKysrJycnIyMjHx8fGxsbFxcXExMTDw8PCwsLBwcHAwMC/v7++vr69vb28vLy7u7u6urq5ubm4uLi3t7e2tra1tbW0tLSzs7OysrKxsbGwsLCvr6+urq6tra2srKyrq6uqqqqpqamoqKinp6empqalpaWkpKSjo6OioqKhoaGgoKCfn5+enp6dnZ2cnJybm5uampqZmZmYmJiXl5eWlpaVlZWUlJSTk5OSkpKRkZGQkJCPj4+Ojo6NjY2MjIyLi4uKioqJiYmIiIiHh4eGhoaFhYWEhISDg4OCgoKBgYGAgIB/f39+fn59fX18fHx7e3t6enp5eXl4eHh3d3d2dnZ1dXV0dHRzc3NycnJxcXFwcHBvb29ubm5tbW1sbGxra2tqamppaWloaGhnZ2dmZmZlZWVkZGRjY2NiYmJhYWFgYGBfX19eXl5dXV1cXFxbW1taWlpZWVlYWFhXV1dWVlZVVVVUVFRTU1NSUlJRUVFQUFBPT09OTk5NTU1MTExLS0tKSkpJSUlISEhHR0dGRkZFRUVERERDQ0NCQkJBQUFAQEA/Pz8+Pj49PT08PDw7Ozs6Ojo5OTk4ODg3Nzc2NjY1NTU0NDQzMzMyMjIxMTEwMDAvLy8uLi4tLS0sLCwrKysqKiopKSkoKCgnJycmJiYlJSUkJCQjIyMiIiIhISEgICAfHx8eHh4dHR0cHBwbGxsaGhoZGRkYGBgXFxcWFhYVFRUUFBQTExMSEhIQEBAODg4MDAwKCgoICAgGBgYEBAQCAgL///8vhimaAAAA+HRSTlP/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////ACjOtjcAAAAJcEhZcwAACxMAAAsTAQCanBgAAAa2aVRYdFhNTDpjb20uYWRvYmUueG1wAAAAAAA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzE0OCA3OS4xNjQwMzYsIDIwMTkvMDgvMTMtMDE6MDY6NTcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDowQjc4RTQyRTQyOTIxMUVBQjM2QUFDQjYxQzZFRkMyNCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo4MzRmOWVhYy1hNTI2LWI0NDAtYmQxNS02ZmIxOTQzODJhYTUiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDowQjc4RTQyRTQyOTIxMUVBQjM2QUFDQjYxQzZFRkMyNCIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSBXaW5kb3dzIiB4bXA6Q3JlYXRlRGF0ZT0iMjAyNC0wOS0xNFQxMTowNDozNy0wMzowMCIgeG1wOk1vZGlmeURhdGU9IjIwMjQtMDktMTRUMTI6Mjg6MDYtMDM6MDAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMjQtMDktMTRUMTI6Mjg6MDYtMDM6MDAiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIyIiBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowRkUxODI3RDNCOTUxMUVBOEEyRjg5MEUxQUI2RDlEOSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowRkUxODI3RTNCOTUxMUVBOEEyRjg5MEUxQUI2RDlEOSIvPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDphNmU0NmRiNS1iZmI1LTkyNGMtOWE0Zi1lNWY4MzIwNWE3YWEiIHN0RXZ0OndoZW49IjIwMjQtMDktMTRUMTI6MjU6MjUtMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ODM0ZjllYWMtYTUyNi1iNDQwLWJkMTUtNmZiMTk0MzgyYWE1IiBzdEV2dDp3aGVuPSIyMDI0LTA5LTE0VDEyOjI4OjA2LTAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9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" style="width:250px;"/>';}
    switch ($empresa) {
        case "B":
            $dbEmpresa = $dbBritanico;
            break;
        case "N":
            $dbEmpresa = $dbNogues;
            break;
        default:
            $dbEmpresa  = $dbBritanico;
    } 

    $comunicacion = "FormAviso";
    $paginaFull = array();
    $htmlPaginado=array();
    $htmlFinal = "";
    $codigoPagoFacil = "";
    $barcodePagoFacil = "";
    $ciclo=0;
    $registros=0;
    $skips=0;
    $CantidadImporteCero=0;
    $lastIdCliente=0;
    $cantidadClienteSalteado=0;
    $paginas=0;
    $caracteres=0;
    $datoCorregido=false;

    $cantidadDatosCorregidos=0;
    $faltabaDatoPagador=0;
    $skip=false;


    // 'YYYY-MM-01'
    $fechaAlta = "";
    $fechaAlta .= $anio;
    $fechaAlta .= "-";
    if ($mes<10 && $mes>0) {
        $fechaAlta .= "0";
    }
    if ($mes>0) {
        $fechaAlta .= $mes;
    }
    $fechaAlta .= "-01";
    //$fechaAlta = "'" . $fechaAlta . "'";

    $d = new DateTime( $fechaAlta );  // 'YYYY-MM-01'
    //$fechaVencStr = $d->format( 'Y-m-t' );  // YYYY-MM-31
    $fechaVencStr = getDateLastDayOfMonth($d);        // YYYY-MM-31
    $fechaVenc = new DateTime( $fechaVencStr );
    $fechaVenc2=$fechaVenc->modify('+1 month');
    $fechaVencStr2 = getDateLastDayOfMonth($fechaVenc2);        // YYYY-MM-31
    
    /*
    $today = new DateTime();
    $jan1 = new DateTime('January 1');
    //$jan1->modify('+1 year');
    //$days = $today->diff($jan1)->days;
    $days = $fechaVenc->diff($jan1)->days; // Dias  desde el 1 de enero del año de vencimiento
    $days += 1;
    */
    $days = getNumberOfDaysInYearDate($fechaVenc);


    $textoMargenes = array();
    try {

        $textoMargenes["FormAvisoTitle"]        = getTextoMargenesPorClave("FormAvisoTitle", $comunicacion);
        $textoMargenes["FormAvisoBody"]         = getTextoMargenesPorClave("FormAvisoBody", $comunicacion);
        $textoMargenes["FormAvisoPagina"]       = getTextoMargenesPorClave("FormAvisoPagina", $comunicacion);
        $textoMargenes["FormAvisoCabe1"]        = getTextoMargenesPorClave("FormAvisoCabe1", $comunicacion);
        $textoMargenes["FormAvisoCabe2"]        = getTextoMargenesPorClave("FormAvisoCabe2", $comunicacion);
        $textoMargenes["FormAvisoCabe3"]        = getTextoMargenesPorClave("FormAvisoCabe3", $comunicacion);
        $textoMargenes["FormAvisoConcepto"]     = getTextoMargenesPorClave("FormAvisoConcepto", $comunicacion);
        $textoMargenes["FormAvisoFechaAviso"]   = getTextoMargenesPorClave("FormAvisoFechaAviso", $comunicacion);
        $textoMargenes["FormAvisoVenc"]         = getTextoMargenesPorClave("FormAvisoVenc", $comunicacion);
        $textoMargenes["FormAvisoTotal"]        = getTextoMargenesPorClave("FormAvisoTotal", $comunicacion);
        $textoMargenes["FormAvisoPie"] = getTextoMargenesPorClave("FormAvisoPie", $comunicacion);
        $textoMargenes["LogoAviso"] = getTextoMargenesPorClave("LogoAviso", $comunicacion);
    } catch (Exception $e){
        throw new Exception($e->getCode(), $e->getMessage());
    }

    $prmsPagador = array(
        "periodo" => $fechaAlta, // string YYYY-MM-DD
        "idPagador" => $idPagador,
        "modo" => "D", // solo deudores
        "filtro"=> $filtro
    );
    log_message("error", "RELATED pagador  spObtenerDeudaParaAviso -> " . json_encode($prmsPagador, JSON_PRETTY_PRINT));

    // Pagadores con Deuda en el periodo
    $sqlDatosPagador=   "dbo.spObtenerDeudaParaAviso ?, ?, ?, ?;";
    $conexionDatosPagador =  $CI->load->database($dbEmpresa, true); // return connection id
    /*
    database parameters:
    -> 1. The database connection values, passed either as an array or a DSN string.
    -> 2. TRUE/FALSE (boolean). Whether to return the connection ID (see Connecting to Multiple Databases below).
    3. TRUE/FALSE (boolean). Whether to enable the Query Builder class. Set to TRUE by default.
    */
    
    $queryDatosPagador = $conexionDatosPagador->query($sqlDatosPagador, $prmsPagador);

    $numPagador = $queryDatosPagador->num_rows();
    $resultadoDatosPagador = $queryDatosPagador->result_array();

    $conexionDatosPagador->close();

    $registros = count($resultadoDatosPagador); 

    // 1. DatosPagador  -- Ciclo principal
    foreach ($resultadoDatosPagador as $row)
    {
        $ciclo++;

        $idCliente = $row["id_cliente"];
        $numero_pagador = $row["NumeroPagador"];
        $pagador = $row["pagador"];
        $domicilio = $row["domicilio"];
        $codigo_postal = $row["CodigoPostal"];
        $provincia = $row["Provincia"];
        $pais = $row["pais"];
        $NumeroDocumentoPagador = $row["NumeroDocumentoPagador"];

        $parte_detalle = "";
        $parte_valor = "";
        $strbarcode = "";
        $dv1 = "";
        $dv2 = "";
        $skip = false;

        $parte_detalle = $row["detalleDeuda"];
        $parte_valor = $row["valorDeuda"];
        if (str_contains2($parte_valor, ".")===false && str_contains2($parte_valor, ",")===false) {
            $parte_valor += ".00"; 
        }

        // no deberia haber ninguna porque lo resuelve el SP
        $skip = ($parte_valor == "0" or $parte_valor == "0.0" Or $parte_valor == "0.00" or $pagador == "");

        // Obtengo el codigo de Pago Facil, full, con digito verificador y todo.
        $codigoPagoFacil = generateCodigoPagofacil($numero_pagador, $parte_valor, $anio, $days);

        // Armo el codigo de barras y lo paso a code128
        $bc_code128 = generateCode128asBase64Img($codigoPagoFacil); 
        $barcodePagoFacil = "<img class='barcode-image' src='" . $bc_code128 ."' alt='Barcode' />";

        if (!$skip) {
            $paginas++;
        } else {
            $skips++;
        }
        /*DATOS DE PAGO MIS CUENTAS!*/
        $clientePagoMisCuentas="163";
        $utility="90063805";
        $forked_client="806";
        /**/
        $date=date('Y-m-d');
        $monto=str_replace(",", "", $parte_valor);
        $monto=str_replace(".", "", $monto);
        $sqlRegistrarCodigoBarras = "dbo.spCodigoPagoFacil_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?;";
        $prms = array(
	        "client_id"=>$clientePagoMisCuentas,
	        "item_id"=>$ciclo,
	        "utility"=>$utility,
	        "amount"=>$monto,
	        "expired_amount"=>$monto,
	        "debt_type_id"=>"1",
	        "open_amount"=>"0",
	        "first_expiration_date"=>str_replace($fechaVencStr,"-",""),
	        "second_expiration_date"=> str_replace($fechaVencStr2, "-", ""),
	        "text_show"=>'Deuda Cementerio',
	        "query_ticket_text"=> 'Deuda Cementerio',
	        "client_code"=>$numero_pagador,
	        "client_name"=>$pagador,
	        "payer_identity_number"=>$NumeroDocumentoPagador,
	        "date"=> str_replace($date,"-",""),
	        "time"=>"0000",
	        "soft_descriptor"=>"Cementerio",
	        "forked_client"=>$forked_client,
	        "cvu"=>"",
	        "barcode"=>$codigoPagoFacil
        );
        $conexionMarcarAccedido = $CI->load->database($dbEmpresa, true); // return connection id
        $rc = $conexionMarcarAccedido->query($sqlRegistrarCodigoBarras, $prms);


        $fechaAvisoStr = substr($fechaAlta, 8, 2)."/".substr($fechaAlta, 5, 2)."/".substr($fechaAlta, 0, 4); // YYYY-MM-DD  -> DD/MM/YYYY
        $reemplazos = array(
            "_NUMEROPAGADOR_" => $numero_pagador,
            "_NOMBREPAGADOR_" => $pagador,
            "_DOMICILIO_" => $domicilio,
            "_CODIGOPOSTAL_" => $codigo_postal,
            "_FECHAVISO_" => $fechaAvisoStr ,   // YYYY-MM-DD  -> DD/MM/YYYY
            "_PROVINCIA_" => $provincia,
            "_PAIS_" => $pais,
            "_DETALLEDEUDA_" => $parte_detalle,
            "_FECHAVENCIMIENTO_" => substr($fechaVencStr, 8, 2)."/".substr($fechaVencStr, 5, 2)."/".substr($fechaVencStr, 0, 4), // YYYY-MM-DD  -> DD/MM/YYYY
            "_IMPORTETOTAL_" => $parte_valor,
            "_NUMEROPAGADORPADEADO_" => str_pad($numero_pagador, 5, "0", STR_PAD_LEFT),
            "_BARCODE_" => $barcodePagoFacil,
            "_LOGO_" => $logo,
        );
        $tags_template = array_keys($reemplazos);
        $datos_reemplazo = array_values($reemplazos);

        $html = array();

        $html["FormAvisoPagina"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["FormAvisoPagina"]["reemplazado"]);
        $html["FormAvisoCabe1"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["FormAvisoCabe1"]["reemplazado"]);
        $html["FormAvisoCabe2"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["FormAvisoCabe2"]["reemplazado"]);
        $html["FormAvisoCabe3"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["FormAvisoCabe3"]["reemplazado"]);
        $html["FormAvisoConcepto"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["FormAvisoConcepto"]["reemplazado"]);
        $html["FormAvisoFechaAviso"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["FormAvisoFechaAviso"]["reemplazado"]);
        $html["FormAvisoVenc"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["FormAvisoVenc"]["reemplazado"]);
        $html["FormAvisoTotal"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["FormAvisoTotal"]["reemplazado"]);
        $html["FormAvisoPie"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["FormAvisoPie"]["reemplazado"]);
        $html["LogoAviso"] = str_replace($tags_template, $datos_reemplazo, $textoMargenes["LogoAviso"]["reemplazado"]);

        $htmlIndividual = "";
        $htmlIndividual .= $html["FormAvisoPagina"]["TagInicio"];
        $htmlIndividual .= $html["FormAvisoCabe1"]["TagInicio"] . $html["FormAvisoCabe1"]["Contenido"] . $html["FormAvisoCabe1"]["TagCierre"];
        $htmlIndividual .= $html["FormAvisoCabe2"]["TagInicio"] . $html["FormAvisoCabe2"]["Contenido"] . $html["FormAvisoCabe2"]["TagCierre"];
        $htmlIndividual .= $html["FormAvisoCabe3"]["TagInicio"] . $html["FormAvisoCabe3"]["Contenido"] . $html["FormAvisoCabe3"]["TagCierre"];
        $htmlIndividual .= $html["LogoAviso"]["TagInicio"] . $html["LogoAviso"]["Contenido"] . $html["LogoAviso"]["TagCierre"];
        $htmlIndividual .= $html["FormAvisoConcepto"]["TagInicio"].$html["FormAvisoConcepto"]["Contenido"] .$html["FormAvisoConcepto"]["TagCierre"];
        $htmlIndividual .= $html["FormAvisoFechaAviso"]["TagInicio"] . $html["FormAvisoFechaAviso"]["Contenido"] . $html["FormAvisoFechaAviso"]["TagCierre"];
        $htmlIndividual .= $html["FormAvisoVenc"]["TagInicio"] . $html["FormAvisoVenc"]["Contenido"] . $html["FormAvisoVenc"]["TagCierre"];
        $htmlIndividual .= $html["FormAvisoTotal"]["TagInicio"] . $html["FormAvisoTotal"]["Contenido"] . $html["FormAvisoTotal"]["TagCierre"];
        $htmlIndividual .= $html["FormAvisoPie"]["TagInicio"] . $html["FormAvisoPie"]["Contenido"] . $html["FormAvisoPie"]["TagCierre"];
        $htmlIndividual .= $html["FormAvisoPagina"]["TagCierre"];

        if (!$skip) {
            $htmlFinal .= $htmlIndividual; 
            array_push($htmlPaginado, $htmlIndividual); // y agrego al final del array, como stack. Asi tengo cada una de las paginas.
        }

    } // Fin 1. DatosPagador -- Ciclo principal      

    $paginaFull["FormAvisoTitle"] = str_replace(array("_MES_", "_ANIO_"), array($mes, $anio), $textoMargenes["FormAvisoTitle"]["reemplazado"]["Contenido"]);
    $paginaFull["FormAvisoBody"] = str_replace(array("_TITULO_",), $paginaFull["FormAvisoTitle"], $textoMargenes["FormAvisoBody"]["reemplazado"]);
    
    $htmlSalida =   $paginaFull["FormAvisoBody"]["TagInicio"] .$paginaFull["FormAvisoBody"]["Contenido"] .$htmlFinal .$paginaFull["FormAvisoBody"]["TagCierre"];

    $caracteres=strlen($htmlSalida);
    $salida = array(
                    "html"=>$htmlSalida,
                    "titulo"=>$paginaFull["FormAvisoTitle"],
                    "paginas"=>$paginas,        // cantidad de paginas del HTML (registros - skips)
                    "caracteres"=>$caracteres,  // cantidad de caracteres del string concatenado
                    "skips"=>$skips,            // salteados por cero
                    "registros"=>$registros,    // registros totales
                    "htmlPaginado" => $htmlPaginado,
                );

    return $salida;                            
}
/**
 * Cuando alguien entra al sitio publico de descarga del resumen, deja la fecha de acceso grabada en la tabla emails
 *
 * Setea la marca de acceso al resumen individual.
 *
 * @param int $idPagador.
 * @param int $anio. Del resumen actual generado y en curso
 * @param int $mes. Del resumen actual generado y en curso
 * @param string $empresa. B Britanico/Chacarita, N Nogues
 * @return bool true -> OK
 * 
 */
function marcarAvisoMensualComoAccedido(int $idPagador, int $anio, int $mes, string $empresa) : array {

    $CI = &get_instance();

    $dbBritanico = 'neo_britanico';  // de config/database.php
    $dbNogues = 'neo_nogues';
    $dbEmpresa = "";
    
    switch ($empresa) {
        case "B":
            $dbEmpresa = $dbBritanico;
            break;
        case "N":
            $dbEmpresa = $dbNogues;
            break;
        default:
            $dbEmpresa  = $dbBritanico;
    } 


    $sqlMarcarAccedido = " UPDATE emails SET verified=getdate() WHERE id_pagador = ? and anio = ? and mes = ?; ";
    $prmsMarcarAccedido = array(
                                "id_pagador" => $idPagador,
                                "anio" => $anio,
                                "mes" => $mes,
                            );
    
    $conexionMarcarAccedido =  $CI->load->database($dbEmpresa, true); // return connection id
    $rc = $CI->db->query($sqlMarcarAccedido, $prmsMarcarAccedido);
    $salida = false;
    if (!$rc) {
        // si dio false estoy en problemas.....hacer un throw o raise...
        $mierror = $CI->db->error();
        throw new Exception($mierror['message'], $mierror['code']);
    } else {
        $salida = true;
        $MarcarAccedido = $rc; // es un update
    }

    return array(
                "resultado"=>$salida,
                );                            
}

/**
 * Generar la hash que conforma la URL que va a figurar en los mails de aviso de deuda mensual.
 *
 * Con los datos: $idPagador, int $anio, int $mes, string $empresa
 * empresa|IdPagador|anio|mes -> base64 -> AD+base64
 * B|31|2022|6  -> base64 ->  MzF8MjAyMnw2 -> ADMzF8MjAyMnw2   
 * http://localhost:4001/avisodeuda/ADQnw4M3wyMDIyfDg
 *
 * @param int $idPagador. Si 0, es para todos
 * @param int $anio. Del resumen actual generado y en curso
 * @param int $mes. Del resumen actual generado y en curso
 * @param string $empresa. B Britanico/Chacarita, N Nogues
 * @return string hash -> AD+base64(empresa|IdPagador|anio|mes)
 */
function generateUrlAvisoDeudaMensual(int $idPagador, int $anio, int $mes, string $empresa) : string {
    $delimitador = "|";
    $prefijo = "AD";


    $cadena = "";
    $cadena = $empresa . $delimitador . $idPagador . $delimitador . $$anio . $delimitador . $mes; 
    $b64 = base64_encode($cadena);
    $encoded =  $prefijo . $b64;
    return $encoded;
}

/**
 * Obtiene los datos para buscar los avisos de deuda mensuales en funcion de la URL de avisos.
 *
 * Obtiene los datos para buscar los avisos de deuda mensuales en funcion de la URL de avisos, si hay un base64 lo decodifica
 *
 * @param string $url
 * @return array array( "empresa" => "", "idPagador" => 0, "anio" => 0, "mes" => 0);
 */
function getDatosFromUrlAvisoDeudaMensual(string $url) : array {

        // B|31|2022|6     B Chacarita  N Nogues  | Id Pagador | anio | mes   
    // -> AD + base64 ->   ADQnwzM3wyMDIyfDY 
    // http://localhost:4001/avisodeuda/ADQnwzM3wyMDIyfDY

    $param = "";
    $prefijo = "AD";
    $separator = "|";
    
    $datos=array();
    $salida=array(
        "empresa" => "",
        "idPagador" => 0,
        "anio" => 0,
        "mes" => 0,
    );
    $idPagador=0;
    $anio=0;
    $mes=0;
    $empresa="";

    // Si la URL tiene el prefijo, esta hasheada
    if (substr($url, 0, strlen($prefijo)) === $prefijo) {
        $param = substr($url, strlen($prefijo), strlen($url)-strlen($prefijo)); // quito del prefijo 
        $deco = base64_decode($param);
        $datos = explode($separator, $deco);
        if ($datos){
            $empresa = $datos[0];  // B o N
            $idPagador=$datos[1];  // int
            $anio=$datos[2];       // int
            $mes=$datos[3];        // int

            $salida=array(
                        "empresa" => $datos[0],
                        "idPagador" => $datos[1],
                        "anio" => $datos[2],
                        "mes" => $datos[3],
                    ); 
        }
    } 
    
    $CI =& get_instance();

    //$x = $this->uri->uri_string();
    // https://www.tutorialandexample.com/codeigniter-url-helper
    $x = $CI->uri->uri_string(); // "avisodeuda/ADQnw4M3wyMDIyfDg"
    $x = $CI->uri->ruri_string(); // AvisoDeDeuda/getDeudaByHash/ADQnw4M3wyMDIyfDg
    $x = $CI->uri->segment(0); // null
    $x = $CI->uri->segment(1); // avisodeuda
    $x = $CI->uri->segment(2); //ADQnw4M3wyMDIyfDg
    $x = parse_url(current_url()); //array(scheme -> http, host-> localhost, path -> "/avisodeuda/ADQnw4M3wyMDIyfDg")


    $X="0";
    return $salida;
}