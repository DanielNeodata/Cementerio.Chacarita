<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Recibo extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["view"]="vw_recibo";
            $values["order"]="Nro_Recibo DESC";
            $values["records"]=$this->get($values);

            $values["getters"]=array(
             "search"=>true,
             //"googlesearch"=>true,
             "excel"=>true,
             "pdf"=>false,
           );

            $values["buttons"]=array(
                "new"=>true,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"ID_Recibo","format"=>"text"),
                array("field"=>"imprimir","format"=>"text"),
                array("field"=>"Nro_Recibo","format"=>"text"),
                array("field"=>"Fecha_Emision","format"=>"date"),
                array("field"=>"Fecha_Caja","format"=>"date"),
                array("field"=>"Importe","format"=>"text"),
                array("field"=>"Pagador","format"=>"text"),
                array("field"=>"RazonSocial","format"=>"text"),
                array("field"=>"Observaciones","format"=>"text"),
                //array("field" => "forzar_anular", "format" => "text"),
                array("field"=>"anular","format"=>"text"),
            );

            $values["controls"]=array(
                "<label>".lang('p_Nro_Recibo')."</label><input type='text' id='browser_Nro_Recibo' name='browser_Nro_Recibo' class='form-control text'/>",
                "<label>".lang('p_Importe')."</label><input type='text' id='browser_Importe' name='browser_Importe' class='form-control text'/>",
                "<label>".lang('p_Pagador')."</label><input type='text' id='browser_Pagador' name='browser_Pagador' class='form-control text'/>",
                "<label>".lang('p_RazonSocial')."</label><input type='text' id='browser_RazonSocial' name='browser_RazonSocial' class='form-control text'/>",
                "<label>Desde</label><input type='date' id='browser_desde' name='browser_desde' class='form-control date'/>",
                "<label>Hasta</label><input type='date' id='browser_hasta' name='browser_hasta' class='form-control date'/>",
            );

            $values["filters"]=array(
                array("name"=>"browser_Nro_Recibo", "operator"=>"like","fields"=>array("Nro_Recibo")),
                array("name"=>"browser_Importe", "operator"=>"like","fields"=>array("Importe")),
                array("name"=>"browser_Pagador", "operator"=>"like","fields"=>array("Pagador")),
                array("name"=>"browser_RazonSocial", "operator"=>"like","fields"=>array("RazonSocial")),
                array("name" => "browser_desde", "operator" => ">=", "fields" => array("Fecha_Emision")),
                array("name" => "browser_hasta", "operator" => "<=", "fields" => array("Fecha_Emision")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("Nro_Recibo","Importe", "Pagador", "RazonSocial")), 
            );
            return parent::brow($values);

        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function excel($values)
    {
        try {
            if ($values["where"] != "") {
                $values["where"] = base64_decode($values["where"]);
            }
            $values["view"] = "vw_recibo";
            $values["delimiter"] = ";";
            $values["pagesize"] = -1;
            $values["order"]=" Nro_Recibo ASC";
            $values["records"] = $this->get($values);

            $values["columns"] = array(
                array("field" => "Nro_Recibo", "format" => "text"),
                array("field" => "Fecha_Caja", "format" => "text"),  // 
                array("field" => "Importe", "format" => "text"),  // 
                array("field" => "Pagador", "format" => "text"),
                array("field" => "NumeroPagador", "format" => "text"),
                array("field" => "NumeroCliente", "format" => "text"),
                array("field" => "letras", "format" => "text"),
                array("field" => "anulado", "format" => "text"),
            );

            return parent::excel($values);
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function getRecibo($values) {
        try {
	        

            $sqlRecibo = "SELECT * FROM dbo.vw_recibo WHERE ID_Recibo=?; ";
            $sqlReciboCC = "SELECT * FROM dbo.vw_Recibo_CuentaCorriente WHERE ID_Recibo=? ORDER BY parcelaReducido, fecha_vencimiento;";
            $sqlReciboValoresEfectivo = "SELECT isnull(sum(importe), 0) as importe FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=? AND tipo_valor=5;"; //-- EFECTIVO"
            $sqlReciboValoresTrfBco = "SELECT isnull(sum(importe), 0) as importe FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=? AND tipo_valor=15 AND id_cuentacontable = 379;"; //-- TRANSFERENCIAS BCO patagonia
            $sqlReciboValoresTrfCbu = "SELECT isnull(sum(importe), 0) as importe FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=? AND tipo_valor=15 AND id_cuentacontable = 378;"; // -- TRANSFERENCIAS CBU"
            $sqlReciboValoresTrfDni = "SELECT isnull(sum(importe), 0) as importe FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=? AND tipo_valor=15 AND (id_cuentacontable = 577 OR id_cuentacontable = 575);"; // -- TRANSFERENCIAS DNI"
            $sqlReciboValoresTrfDre = "SELECT isnull(sum(importe), 0) as importe FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=? AND tipo_valor=15 AND (id_cuentacontable = 582 OR id_cuentacontable = 578);"; // -- TRANSFERENCIAS DRE"
            $sqlReciboValoresMonedaExtranjera1 = "SELECT isnull(sum(importe), 0) as importe FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=? AND tipo_valor in(3,18);"; // -- MONEDA EXTRANJERA"
            $sqlReciboValoresMonedaExtranjera2 = "SELECT isnull(sum(importe), 0) as importe FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=? AND tipo_valor in(10,14);"; // -- MONEDA EXTRANJERA2"
            $sqlReciboValoresTarjeta = "SELECT isnull(sum(importe), 0) as importe FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=? AND tipo_valor IN (16,17);"; //-- TARJETA
            $sqlReciboValoresCheques = "SELECT isnull(sum(importe), 0) as importe,desc_valores,nro_comprobante FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=? AND tipo_valor=2 GROUP BY desc_valores,nro_comprobante;"; // --CHEQUES"
            $sqlReciboValoresTotal = "SELECT isnull(SUM(importe), 0) as importe FROM dbo.vw_Recibo_Valores WHERE ID_Recibo=?;"; //
            $prms=array("ID_Recibo" => $values["ID_Recibo"]); 
            $rc = $this->execAdHocWithParms($sqlRecibo, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $recibo = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboCC, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboCC = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresEfectivo, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresEfectivo = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresTrfBco, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresTrfBco = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresTrfCbu, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresTrfCbu = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresTrfDni, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresTrfDni = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresTrfDre, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresTrfDre = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresMonedaExtranjera1, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresMonedaExtranjera1 = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresMonedaExtranjera2, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresMonedaExtranjera2 = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresTarjeta, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresTarjeta = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresCheques, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresCheques = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlReciboValoresTotal, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $reciboValoresTotal = $rc->result_array();
            }
            $margins_path = './files/margenes.php'; // it is the path of the text files ( Britanico\trunk\files\margenes.php ) 
            $marginsJson = file_get_contents($margins_path); // here $data is called for fetching the files message
            $margins = json_decode($marginsJson, true); // returns associative array

            // Margenes del recibo desde tabla
            $sqlReciboMargenes = "select ReciboMargenClave as clave, ReciboMargenX as x, ReciboMargenY as y, ReciboMargenWidth as width,ReciboMargenHeight as height, 'mm' as unit  from dbo.RecibosMargenes";
            $prms = array();
            $rc = $this->execAdHocWithParms($sqlReciboMargenes, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $recibosMargenes = $rc->result_array();
            }    
            $margenes=array();
            $keyMargenes =  array_column($recibosMargenes, "clave");
            for ($i=0; $i<count($keyMargenes); $i++) {
                $item = array();
                $item["x"]      = $recibosMargenes[$i]["x"];
                $item["y"]      = $recibosMargenes[$i]["y"];
                $item["width"]  = $recibosMargenes[$i]["width"];
                $item["height"] = $recibosMargenes[$i]["height"];
                $item["unit"]   = $recibosMargenes[$i]["unit"];
                $margenes[$keyMargenes[$i]] = $item;
            }
            $coordenadasImpresionRecibo = array("recibo" => $margenes);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "recibo"=>$recibo,
                "reciboCC"=>$reciboCC,
                "reciboValoresEfectivo"=>$reciboValoresEfectivo,
                "reciboValoresTrfBco"=>$reciboValoresTrfBco,
                "reciboValoresTrfCbu"=>$reciboValoresTrfCbu,
                "reciboValoresTrfDni"=>$reciboValoresTrfDni,
                "reciboValoresTrfDre"=>$reciboValoresTrfDre,
                "reciboValoresMonedaExtranjera1"=>$reciboValoresMonedaExtranjera1,
                "reciboValoresMonedaExtranjera2"=>$reciboValoresMonedaExtranjera2,
                "reciboValoresTarjeta"=>$reciboValoresTarjeta,
                "reciboValoresCheques"=>$reciboValoresCheques,
                "reciboValoresTotal"=>$reciboValoresTotal,
                "coordenadasImpresion"=>$coordenadasImpresionRecibo, // margenes impresion
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }    
    public function anularRecibo($values) {
        try {
	        
            $sqlDelRecibo = "dbo.spDelRecibo ?; ";
            $prms=array("ID_Recibo" => $values["ID_Recibo"]); 
            $rc = $this->execAdHocWithParms($sqlDelRecibo, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $delRecibo = $rc->result_array();
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "delRecibo"=>$delRecibo,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }    
    public function getPdf3()
    {
        require_once __DIR__ . '/vendor/autoload.php';
        // Descomentar
        //$mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view('<b>Hello World</b>',[],true);
        $mpdf->WriteHTML($html);
        $mpdf->Output(); // opens in browser
        //$mpdf->Output('test.pdf','D'); // it downloads the file into the user system.
    }
    public function getPdf2($values) {
        try {
	        
            $data["parameters"] = $values;
            $data["title"] = $values["title"];;
            $htmlpage=$values["html"];
            $this->load->library("m_pdf");
            $this->m_pdf->pdf->WriteHTML($htmlpage);
            $this->m_pdf->pdf->Output();
            $this->m_pdf->pdf->WriteHTML($htmlpage, 2); // Default 0, HTML_BODY 2
            ob_end_clean();
            print_r($this->m_pdf->pdf);
            $html=$this->m_pdf->pdf->Output($values["filename"], "D"); // 'D'ownload, 'S'tring, 'F'ile
            print_r($html);
             return array(
                 "code"=>"2000",
                 "status"=>"OK",
                 "message"=>"Records",
                 "table"=>$this->table,
                 "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
             );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }        
    public function getPdf($values){
        try {
            //if(!isset($values["interface"])){$values["interface"]=("pdf");}
            //$data["parameters"] = $values;
            //$data["title"] = ucfirst(lang("m_".strtolower($values["model"])));
            //$html=$this->load->view($values["interface"],$data,true);
            $html=$values["html"];
            $this->load->library("m_pdf");
            $this->m_pdf->pdf->WriteHTML($html, 2);
            ob_end_clean();
            $descarga=$this->m_pdf->pdf->Output("legalizacion.pdf", "S");
            ////$descarga=$this->m_pdf->pdf->Output("recibo.pdf", "D");
            //$descarga=$this->m_pdf->pdf->Output("legalizacion.pdf", "F");
            //$descarga=$this->m_pdf->pdf->Output("recibo.pdf", "I");
            
            ////$ret=array("message"=>$html,"mime"=>"application/pdf","mode"=>$values["mode"],"indisk"=>false); // mode: view  exit: download  
            $ret=array("message"=>$descarga,"mime"=>"application/pdf","indisk"=>false);
            return $ret;
            //return $this->m_pdf->pdf->Output("recibo.pdf", "F"); 
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function edit($values){
        try {

            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/abm"); // CCOO -> por esto el directorio de la vista debe ser en minuscula
            $values["page"]=1;
            $values["table"]="vw_parcela";
            $values["view"]="vw_parcela";
            $values["where"]=("id_parcela=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_id_TipoParcela=array(
                "model"=>(MOD_BRITANICO."/TipoParcela"),
                "table"=>"TipoParcela",
                "name"=>"id_TipoParcela",  // aca va eñ nombre del campo de la tabla principal, inhumados por ej. Sirva como contenedor del dato luego de la seleccion.
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_TipoParcela"), // --> id de la tabla inhumados
                "id_field"=>"id_TipoParcela", // --> esta es la PK de la tabla del combo y es la que usa para identificar e seleccionado.
                "description_field"=>"Nombre",  // --> descripcion de la tabla del combo
                "get"=>array("order"=>"Nombre  ASC","pagesize"=>-1),
            );
            $values["controls"]=array("id_TipoParcela"=>getCombo($parameters_id_TipoParcela,$this));
            $registro = $values["records"]["data"][0];
	        
            $sqlClienteRelacionado = "SELECT * FROM dbo.vw_Rel_Cliente_Pagador_Parcela WHERE id_cliente= ? AND id_parcela= ?";
            $prms=array(
                "id_cliente" => $registro["id_cliente"],
                "id_Parcela" => $registro["id_Parcela"],
            );            
            $rc = $this->execAdHocWithParms($sqlClienteRelacionado, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $clienteRelacionado = $rc->result_array();
                $values["clienteRelacionado"]=$clienteRelacionado;
            }
            $sqlInhumadosParcela = "SELECT * FROM dbo.vw_inhumados_by_parcela_simple WHERE id_Parcela_Actual= ? ORDER BY NumeroInhumado ASC";
            $prms=array("id_Parcela" => $registro["id_Parcela"]);            
            $rc = $this->execAdHocWithParms($sqlInhumadosParcela, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $inhumadosParcela = $rc->result_array();
                $values["inhumadosParcela"]=$inhumadosParcela;
            }
            $sqlContratosArrendamientoParcela = "SELECT * FROM dbo.vw_rel_cuentacorriente_numeros WHERE id_parcela= ? ORDER BY Fecha_Vencimiento DESC";
            $prms=array("id_Parcela" => $registro["id_Parcela"]);            
            $rc = $this->execAdHocWithParms($sqlContratosArrendamientoParcela, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $contratosArrendamientoParcela = $rc->result_array();
                $values["contratosArrendamientoParcela"]=$contratosArrendamientoParcela;
            }
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function generarRecibo($values) {
        try {
            $sqlGenerarRecibo = "dbo.spInsRecibo ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?;";
            $prms = array(
                "ID_EmpresaSucursal" => $values["ID_EmpresaSucursal"], 
                "id_cliente" => $values["id_cliente"],
                "Nro_Recibo_Provisorio" => $values["Nro_Recibo_Provisorio"],
                "Fecha_Caja" => $values["Fecha_Caja"], 
                "Fecha_Emision" => $values["Fecha_Emision"], 
                "Fecha_Contable" => $values["Fecha_Contable"], 
                "ImporteTotalRecibo" => $values["ImporteTotalRecibo"], 
                "Conciliacion" => $values["Conciliacion"], 
                "Observaciones" => $values["Observaciones"], 
                "ID_Caja_Tesoreria" => $values["ID_Caja_Tesoreria"], 
                "comprobantes_pagados" => $values["comprobantes_pagados"], 
                "valores_recibidos" => $values["valores_recibidos"], 
                "ImporteACuenta" => $values["ImporteACuenta"],
                "id_pagador" => $values["id_pagador"], 
                //"username" => $values["username"],
                "username" => $values["username_active"], // del framework 
                "idRecibo" => $values["idRecibo"],
            );
            log_message("error", "RELATED " . json_encode($sqlGenerarRecibo, JSON_PRETTY_PRINT));
            log_message("error", "RELATED " . json_encode($prms, JSON_PRETTY_PRINT));

            $rc = $this->execAdHocWithParms($sqlGenerarRecibo, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $recibo = $rc->result_array();
            }
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "recibo"=>$recibo,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }    
    }
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                // Insert
                if($fields==null) {
                    $storedp = "dbo.spInsRecibo ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                    $p = array(
                        "ID_EmpresaSucursal"=>$values["ID_EmpresaSucursal"], 
                        "id_cliente"=>$values["id_cliente"],
                        "Nro_Recibo_Provisorio"=>$values["Nro_Recibo_Provisorio"],
                        "Fecha_Caja"=>$values["Fecha_Caja"], 
                        "Fecha_Emision"=>$values["Fecha_Emision"], 
                        "Fecha_Contable"=>$values["Fecha_Contable"], 
                        "ImporteTotalRecibo"=>$values["ImporteTotalRecibo"], 
                        "Conciliacion"=>$values["Conciliacion"], 
                        "Observaciones"=>$values["Observaciones"], 
                        "ID_Caja_Tesoreria"=>$values["ID_Caja_Tesoreria"], 
                        "comprobantes_pagados"=>$values["comprobantes_pagados"], 
                        "valores_recibidos"=>$values["valores_recibidos"], 
                        "ImporteACuenta"=>$values["ImporteACuenta"],
                        "id_pagador"=>$values["id_pagador"], 
                        "username"=>$values["username"], 
                        "idRecibo"=>$values["idRecibo"],
                    );
                    // Campos de la ventana de ABM
                    $fields = array(
                        'id_Parcela'=>$values["id_Parcela"],
                        'ID_EmpresaSucursal'=>$values["ID_EmpresaSucursal"],
                        'Sector' => secureEmptyNull($values,"Sector"),
                        'Manzana' => $values["Manzana"],
                        'Parcela' => $values["Parcela"],
                        'Secuencia' => secureEmptyNull($values,"Secuencia"),
                        'id_EstadoParcela' => $values["id_EstadoParcela"],
                        'id_TipoParcela' => $values["id_TipoParcela"],
                        'id_TamanioParcela' => $values["id_TamanioParcela"],
                        'CodigoAnterior' => secureEmptyNull($values,"CodigoAnterior"),
                        'FechaArrendamiento' => $values["FechaArrendamiento"],
                        'ClienteCategoria' => $values["ClienteCategoria"],
                        'id_cliente' => $values["id_cliente"],
                        'id_pagador' => secureEmptyNull($values,"id_pagador"),
                        'FechaDia' => $values["FechaDia"],
                        'MesesContratoArrendamiento' => $values["MesesContratoArrendamiento"],
                        'id_ConceptoListaPrecio_Arrendamiento' => secureEmptyNull($values,"id_ConceptoListaPrecio_Arrendamiento"),
                        'CertificadoTitularidad' => $values["CertificadoTitularidad"],
                        'NumeroCompacto' => $values["NumeroCompacto"],
                        'Disponible' => secureEmptyNull($values,"Disponible"),
                        'fecha_limite_conservacion' => secureEmptyNull($values,"fecha_limite_conservacion"),
                        'numero_pagina_mapa' => secureEmptyNull($values,"numero_pagina_mapa"),
                        'observaciones' => secureEmptyNull($values,"observaciones"),                         
                    );
                }
            } else {
                if($fields==null) {
                    $sqlParcela = "SELECT * FROM dbo.Parcela WHERE id_Parcela = ?";
                    $prms=array("id_Parcela" => $values["id"]);            
                    $rc = $this->execAdHocWithParms($sqlParcela, $prms);
                    if (!$rc) {
                        // si dio false estoy en problemas.....hacer un throw o raise...
                        $mierror = $this->db->error();
                        throw new Exception($mierror['message'], $mierror['code']);
                    } else {
                        $_parcelaEnEdicion = $rc->result_array();
                    }
                    $storedp = "dbo.coop_Parcela_Update ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                    $sp2 = "dbo.coop_Rel_Cliente_Pagador_Update ?, ?";
                    $id_cliente="";
                    $id_pagador="";
                    $x=[];
                    $y=[];
                    if (isset($values["key"])) {
                        if ($values["key"]!=null){
                            $x=explode("|*|", $values["key"]);
                            $y=explode(".", $x[1]);
                            $id_cliente=$y[0];  // 
                            $id_pagador=$y[1];
                        }
                    }
                    $parcelaEnEdicion = $_parcelaEnEdicion[0];
                    // Busco lo que NO esta en $values, y los tuve que traer con el select en $parcela
                    $ID_EmpresaSucursal = $parcelaEnEdicion["ID_EmpresaSucursal"];
                    $Sector = $parcelaEnEdicion["Sector"];
                    $Manzana = $parcelaEnEdicion["Manzana"];
                    $Parcela = $parcelaEnEdicion["Parcela"];
                    $Secuencia = $parcelaEnEdicion["Secuencia"];
                    $id_EstadoParcela = $parcelaEnEdicion["id_EstadoParcela"];
                    $id_TipoParcela = $parcelaEnEdicion["id_TipoParcela"];
                    $id_TamanioParcela = $parcelaEnEdicion["id_TamanioParcela"];
                    $CodigoAnterior = $parcelaEnEdicion["CodigoAnterior"];
                    $FechaArrendamiento = $parcelaEnEdicion["FechaArrendamiento"];
                    $FechaDia = $parcelaEnEdicion["FechaDia"];
                    $MesesContratoArrendamiento = $parcelaEnEdicion["MesesContratoArrendamiento"];
                    $id_ConceptoListaPrecio_Arrendamiento = $parcelaEnEdicion["id_ConceptoListaPrecio_Arrendamiento"];
                    $ImporteArrendamiento = $parcelaEnEdicion["ImporteArrendamiento"];
                    $PeriodoConservacion = $parcelaEnEdicion["PeriodoConservacion"];
                    $id_ConceptoListaPrecio_Conservacion = $parcelaEnEdicion["id_ConceptoListaPrecio_Conservacion"];
                    $CertificadoTitularidad = $parcelaEnEdicion["CertificadoTitularidad"];
                    $NumeroCompacto = $parcelaEnEdicion["NumeroCompacto"];
                    $fecha_limite_conservacion = $parcelaEnEdicion["fecha_limite_conservacion"];
                    $o = secureEmptyNull($values,"Observaciones");
                    $oo = $values["Observaciones"];
                    // parametros del SP
                    $p = array(
                        'id_Parcela'=>$values["id"],            // PK, creada en el alta
                        'ID_EmpresaSucursal'=> $ID_EmpresaSucursal, // se crea en el alta. No editable. No viene en $values
                        'Sector' => $Sector,  // Clave compuesta, se crea en el alta. No editable. No viene en $values
                        'Manzana' => $Manzana,                // Clave compuesta, se crea en el alta. No editable. No viene en $values
                        'Parcela' => $Parcela,                // Clave compuesta, se crea en el alta. No editable. No viene en $values
                        'Secuencia' => $Secuencia,            // Clave compuesta, se crea en el alta. No editable. No viene en $values
                        'id_EstadoParcela' => $id_EstadoParcela, //  estado 1 Arrendada 2 Libre 3 Fuera de Venta. No editable. No viene en $values
                        'id_TipoParcela' => $id_TipoParcela,  // Boveda, Cripta, etc.  Si codigoAnterior == S -> id_TipoParcela = null
                        'id_TamanioParcela' => $id_TamanioParcela, // Tamaño. Esta definido en el Alta. No editable. No viene en $values  
                        'CodigoAnterior' => $values["CodigoAnterior"], // Mapeado con Parcela Historica.
                        'FechaArrendamiento' => $FechaArrendamiento, // No editable. No viene en $values
                        'ClienteCategoria' => $values["ClienteCategoria"], // Estado: _N_ormal, _J_ardin, _C_amino
                        'id_cliente' => $id_cliente, // sale del buscador, y si no hay cliente podra pasar Disponible a S
                        'id_pagador' => $id_pagador, // sale del buscador, y si no hay cliente podra pasar Disponible a S
                        'FechaDia' => $FechaDia, // No editable. No viene en $values
                        'MesesContratoArrendamiento' => $MesesContratoArrendamiento, // No editable. No viene en $values
                        'id_ConceptoListaPrecio_Arrendamiento' => $id_ConceptoListaPrecio_Arrendamiento, // No editable. No viene en $values
                        'ImporteArrendamiento' => $ImporteArrendamiento, // No editable. No viene en $values
                        'PeriodoConservacion' => $PeriodoConservacion, // No editable. No viene en $values
                        'id_ConceptoListaPrecio_Conservacion' => $id_ConceptoListaPrecio_Conservacion, // No editable. No viene en $values
                        'CertificadoTitularidad' => $CertificadoTitularidad, // No editable. No viene en $values
                        'NumeroCompacto' => $NumeroCompacto, // No editable. No viene en $values
                        'Disponible' => null, // No es editable, depende de la existencia de cliente y lo resuelve el SP
                        'fecha_limite_conservacion' => $fecha_limite_conservacion,  // No editable. No viene en $values
                        'numero_pagina_mapa' => $values["numero_pagina_mapa"], // Numero de Pagina. Si codigoAnterior == S -> nroPagina = null
                        'observaciones' => $values["observaciones"],   // Datos parcela
                    );

                    // Campos de la ventana de ABM
                    // Aca no importa el tema es que venga algo....
                    $fields = array(
                        'id_Parcela'=>$values["id_Parcela"],
                        'ID_EmpresaSucursal'=>$values["ID_EmpresaSucursal"],
                        'Sector' => secureEmptyNull($values,"Sector"),
                        'Manzana' => $values["Manzana"],
                        'Parcela' => $values["Parcela"],
                        'Secuencia' => secureEmptyNull($values,"Secuencia"),
                        'id_EstadoParcela' => $values["id_EstadoParcela"],
                        'id_TipoParcela' => $values["id_TipoParcela"],
                        'id_TamanioParcela' => $values["id_TamanioParcela"],
                        'CodigoAnterior' => $values["CodigoAnterior"],
                        'FechaArrendamiento' => $values["FechaArrendamiento"],
                        'ClienteCategoria' => $values["ClienteCategoria"],
                        'id_cliente' => $values["id_cliente"],
                        'id_pagador' => $values["id_pagador"],
                        'FechaDia' => $values["FechaDia"],
                        'MesesContratoArrendamiento' => $values["MesesContratoArrendamiento"],
                        'id_ConceptoListaPrecio_Arrendamiento' => secureEmptyNull($values,"id_ConceptoListaPrecio_Arrendamiento"),
                        'CertificadoTitularidad' => $values["CertificadoTitularidad"],
                        'NumeroCompacto' => $values["NumeroCompacto"],
                        'Disponible' => secureEmptyNull($values,"Disponible"),
                        'fecha_limite_conservacion' => secureEmptyNull($values,"fecha_limite_conservacion"),
                        'numero_pagina_mapa' => secureEmptyNull($values,"numero_pagina_mapa"),
                        'observaciones' => secureEmptyNull($values,"observaciones"),                         
                    );
                }
            }
            $saving = parent::saveExtended($values,$fields, $forcedTable=null, $forcedSp=$storedp, $param=$p, $keyname="id_Parcela");
            $prms = array(
                        "id_cliente" => $p["id_cliente"],
                        "id_pagador" => $p["id_pagador"],
                    );
            return $saving;
        }
        catch (Exception $e){
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }    
    public function getClientePagadorParcela($values) {
        try {
            $sqlClientePagadorParcela = " SELECT cast(id_cliente as varchar(200))+'|'+cast(id_pagador as varchar(200)) as id, " .
                                        " pagador as pagador, pagador as detalle " . 
                                        " FROM dbo.vw_Rel_Cliente_Pagador_Parcela " .
                                        " WHERE pagador Like '%" . $this->db->escape_like_str($values["searchKey"]) . "%' " .
                                        " ORDER BY 2; ";
            $rc = $this->execAdHoc($sqlClientePagadorParcela);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $clientePagadorParcela = $rc->result_array();
            }
            $bancos=Array();
            $cuentas=Array();
            $valores=Array();
            $empresa=Array();
            $cajaTesoreria=Array();
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "clientePagadorParcela"=>$clientePagadorParcela,
                "bancos"=>$bancos,
                "cuentas"=>$cuentas,
                "valores"=>$valores,
                "empresa"=>$empresa,
                "cajaTesoreria"=>$cajaTesoreria,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }     
    public function getDatosVarios($values) {
        try {
            $sqlEmpresaSucursal = "select id, descripcion from dbo.vw_EmpresaSucursal; ";
            $sqlValores = "select id_Valor, Codigo_Valor, Desc_Valores from dbo.Valor; ";
            $sqlCajaTesoreria = "SELECT id,descripcion,ID_EmpresaSucursal FROM dbo.vw_Caja_Tesoreria ORDER BY descripcion; ";
            $sqlBancos = "select id_Banco, Codigo_Bancos, Desc_Bancos, Codigo_Bancos + ' - ' + Desc_Bancos as descripcion from dbo.Banco where Estado = 'A' order by Codigo_Bancos; ";
            $sqlCuentasBancarias = "select id_Cuenta_Bancaria, nro_cuenta, detalle as descripcion from dbo.vw_Cuenta_Bancaria order by detalle; ";
            $rc = $this->execAdHoc($sqlEmpresaSucursal);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $empresaSucursal = $rc->result_array();
            }
            $rc = $this->execAdHoc($sqlValores);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $valores = $rc->result_array();
            }
            $rc = $this->execAdHoc($sqlCajaTesoreria);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $cajaTesoreria = $rc->result_array();
            }
            $rc = $this->execAdHoc($sqlBancos);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $bancos = $rc->result_array();
            }
            $rc = $this->execAdHoc($sqlCuentasBancarias);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $cuentasBancarias = $rc->result_array();
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "empresaSucursal"=>$empresaSucursal,
                "valores"=>$valores,
                "cajaTesoreria"=>$cajaTesoreria,
                "bancos"=>$bancos,
                "cuentasBancarias"=>$cuentasBancarias,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }     




}
