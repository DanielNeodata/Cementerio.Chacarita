<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Avisos extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function verifyCode($values)
    {
        try {
            $sql = "SELECT * FROM neo_britanico.dbo.CodigosPagoFacil WHERE barcode='".$values["code"]."'";
            $tot = $this->execAdHocAsArray($sql);
            if (count($tot) == 0) {
                $sql = "SELECT * FROM neo_nogues.dbo.CodigosPagoFacil WHERE barcode='" . $values["code"] . "'";
                $tot = $this->execAdHocAsArray($sql);
                if (count($tot) == 0) {
                    throw new Exception('No se ha encontrado el código de barras asociado a una deuda', 401);
                }
            }
            $tot[0]["item_id"] = (string) $tot[0]["item_id"];
            return $tot[0];
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }


    public function brow($values){
        try {

            $values["view"]="emails";
            $values["order"]="destinatario ASC";

            // SELECT * FROM emails WHERE fecha_envio is null ORDER BY destinatario ASC

            $values["records"]=$this->get($values);

            $values["getters"]=array(
                "search"=>true,
                //"googlesearch"=>true,
                "excel"=>true,
                "pdf"=>true,
            );
   
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"id","format"=>"text"),
                array("field"=>"remite","format"=>"text"),
                array("field"=>"destinatario","format"=>"text"),
                array("field"=>"subject","format"=>"text"),
                array("field"=>"body","format"=>"text"),                
                array("field"=>"fecha_envio","format"=>"date"),                
                array("field"=>"verified","format"=>"date"),                
                array("field"=>"id_pagador","format"=>"text"),                
                array("field"=>"anio","format"=>"text"),                
                array("field"=>"mes","format"=>"text"),                               
            );
   
            /*
            // Controles para los filtros?
            $values["controls"]=array(
                "<label>".lang('p_NumeroCliente')."</label><input type='text' id='browser_NumeroCliente' name='browser_NumeroCliente' class='form-control number'/>",
                "<label>".lang('p_cliente')."</label><input type='text' id='browser_cliente' name='browser_pagador' class='form-control text'/>",
                "<label>".lang('p_pagador')."</label><input type='text' id='browser_pagador' name='browser_pagador' class='form-control text'/>",
            );

            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name"=>"browser_NumeroCliente", "operator"=>"=","fields"=>array("NumeroCliente")),
                array("name"=>"browser_cliente", "operator"=>"like","fields"=>array("cliente")),
                array("name"=>"browser_pagador", "operator"=>"like","fields"=>array("pagador")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("NumeroCliente","cliente","pagador")),                
            );  
            */

            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }    
    public function GetEmailStatus($values){
	    try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
            $sql="SELECT anio, mes, count(*) as Total FROM emails group by anio, mes order by anio, mes";
            $sql2="select COUNT(*) as Enviados from emails where verified is not null";
            $tot = $this->execAdHocAsArray($sql);
            $env = $this->execAdHocAsArray($sql2);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "total"=>$tot,
                "enviados"=>$env,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}
    public function GetGenerarAvisosPDF($values){
	    try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
            // Si Britanico
            switch ((int) $values["id_sucursal"]) {
                case 1: // chacarita
                    $empresa = "B";
                    break;
                case 2: // nogues
                    $empresa = "N";
                    break;
                default:
                    $empresa = "B";
                    break;
            }
            $idPagador = 0;

            $this->load->helper('aviso_deuda'); // hay que revisar a ver si lo pasamos al model.
            $deuda = array();
            $deuda = generateAvisoResumenAsHtml($idPagador, $values["anio"], $values["mes"], $empresa, $values["filtro"]);

	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "total"=>0,
                "deudaTotal"=>$deuda,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}
    public function TransferenciaMensualNogues($values){
	    try {
            $date_eval=$values["anio"]."-".$values["mes"]."-01";
            $sqlResultadoDeuda="[dbo].[sp_TransferenciaMensual_Nogues_a_Chacarita] ?;";
            $prms = array("fecha"=>$date_eval);         
            $rc = $this->execAdHocWithParms($sqlResultadoDeuda, $prms);

            return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
    	    );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
    }


    /**
     * Genera Deuda Mensual
     *
     * Genera la deuda para un periodo, envia el mail y lo marca como enviado. 
     * json: {anio: anio, mes: mes, generarDeuda: "S", enviarMail: "S", test: "N"}
     * json de prueba: {anio: anio, mes: mes, generarDeuda: "N", enviarMail: "S", test: "S"}
     *
     * @param array Entre otros toma un json con {anio: anio, mes: mes, generarDeuda: "S", enviarMail: "S", test: "N"}
     * @return array Devuelve:
     *                  "datos"=>$datos,  son los parametros de input, con los que se genera la deuda para envio por mail
     *                  "deudaGenerada"=>$resultadoDeuda, Es el resultado del SP de generacion de deuda. id del unico asiento 
     *                                                    generado, registros procesados (en la CC nada que ver con la 
     *                                                    tabla de mails) ,y error (0 sin error)
     *                  "avisos"=>$avisos, es la salida de dbo.emails cuando voy a enviar mail
     */  
    public function GenerarDeudaMensual($values){
	    try {
            $sql="dbo.spGENERAR_DEUDA_MENSUAL @mes=". $values["mes"].", @anio=". $values["anio"];
            $this->execAdHoc($sql);
            return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "datos"=>null,
                "deudaGenerada"=>null,
                "avisos"=>null,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
            log_message("error", "RELATED ERROR " . json_encode($e, JSON_PRETTY_PRINT));
            return logError($e,__METHOD__ );
	    }
	}
    
    /**
     * GetDeudaPorPagador
     * Devuelve cantidad de items de deuda generadas y cantidad de destinatarios de mail seteados, supuestamente enviados
     *
     * @param  mixed $values
     * @return void
     */
    public function GetDeudaPorPagador($values){
        // Arma la deuda de un pagador
	    try {
	        

            $fechaAlta = $values["anio"] . "-" . $values["mes"] . "-01";

            $prmsDeuda = array(
                "idPagador" => $values["idPagador"],
                "fechaAlta" => $fechaAlta, 
            );

            $clase = "'C','A','E','O'";

            $sqlDatosPagador= " select COUNT(CC.id_Parcela) AS parcelas, " .
                       "        p.NumeroPagador, " .
                       "        p.id_pagador, " .
                       "        pagador, " .
                       "        p.domicilio, " .
                       "        p.CodigoPostal, " .
                       "        p.Provincia, " .
                       "        p.pais, " .
                       "        cliente AS titular, " .
                       "        id_cliente " .
                       " FROM " .
                       "          dbo.vw_CuentaCorriente_avisos as cc, " .
                       "          dbo.vw_pagador as p " .
                       " WHERE " .
                       "         p.id_pagador= ? AND " .
                        " p.id_pagador=cc.id_pagador AND " .
                        " (id_parcela is null OR id_parcela in (select distinct id_parcela FROM CuentaCorriente where fecha_alta= ? AND clase in (" . $clase . "))) AND " .
                        " id_cliente is not null AND Saldo > 0 AND clase IN (" . $clase . ")" .
                        " GROUP BY p.NumeroPagador,p.id_pagador,pagador,p.domicilio,p.CodigoPostal,p.Provincia,p.pais,cliente,id_cliente ";

            $sqlDeuda="select [dbo].[Get_DetalleDeudaHTML](?, ?) as detalle;";       
            
            $sqlDatosAlternativosPagador = "select * from dbo.vw_pagador where id_pagador=?";

            $prmsDatosAlternativosPagador = array(
                "idPagador" => $values["idPagador"],
            );

            
            // Datos Pagador
            $rc = $this->execAdHocWithParms($sqlDatosPagador, $prmsDeuda);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $datosPagador = $rc->result_array();
            }


            // Deuda
            /*
            exec [dbo].[spGENERAR_DEUDA_MENSUAL] @mes, @anio, @idModelo;                    
            */
            $rc = $this->execAdHocWithParms($sqlDeuda, $prmsDeuda);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $deuda = $rc->result_array();
            }
            // Datos Alternativos
            $rc = $this->execAdHocWithParms($sqlDatosAlternativosPagador, $prmsDatosAlternativosPagador);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $datosAlternativosPagador = $rc->result_array();
            }


            // Marcar aviso como descargado

            $sqlMarcarDescargado = " UPDATE emails SET verified=getdate() WHERE id_pagador= ? AND anio= ? AND mes= ?; ";
            $prmsMarcarDescargado = array(
                "id_pagador" => 11,
                "anio" => 11,
                "mes" => 11,
            );
			
            $rc = $this->execAdHocAsArray($sqlMarcarDescargado);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $MarcarDescargado = $rc->result_array();
            }

            return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "datosPagador"=>$datosPagador,
                "deudaGenerada"=>$deuda,
                "datosAlternativosPagador"=>$datosAlternativosPagador,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}    
    public function EnviarMailAvisoDeuda($values){
        // Arma la deuda de un pagador
	    try {
	        

            $sqlAvisos= " select * from dbo.emails order by destinatario; ";

            $rc = $this->execAdHocAsArray($sqlAvisos);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $avisos = $rc->result_array();
            }


            $sqlMarcarEnviado = " UPDATE emails SET fecha_envio=getdate() WHERE id = ?; ";
            $prmsMarcarEnviado = array(
                "id" => 11,
            );
            

            $rc = $this->execAdHocAsArray($sqlMarcarEnviado);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $MarcarEnviado = $rc->result_array();
            }
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "avisos"=>$avisos,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );            
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}    
    public function avisos($values){
        // Pantalla principal
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));

            $sqlPermisosAdicionales = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionales=array(
                "userId" => $values["id_user_active"],
                "perm" => "generar_avisos_mensuales"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionales, $prmsPermisosAdicionales);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionales = $rc->result_array();
            }  

            $permite = $this->trueFalseFromSN($permisosAdicionales[0]["permitido"]);

            if ($permite) {
                $data["parameters"]["permiteGenerar"] = "S";    
            } else {
                $data["parameters"]["permiteGenerar"] = "N";                    
            }

            $html=$this->load->view($values["interface"],$data,true);
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
