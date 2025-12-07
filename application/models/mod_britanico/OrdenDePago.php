<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class OrdenDePago extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function GetEditBaseData($values)
    {
        try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
            $numop = $values["VAL_ID"];
            $suc = $this->execAdHocAsArray("SELECT * FROM dbo.vw_EmpresaSucursal ORDER BY descripcion");
            $caja = $this->execAdHocAsArray("SELECT id,descripcion,ID_EmpresaSucursal FROM dbo.vw_Caja_Tesoreria ORDER BY descripcion");
            $cuentas = $this->execAdHocAsArray("SELECT id,numero + ' ' + nombre as descripcion FROM dbo.con_cuentas ORDER BY 2");
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "suc"=>$suc,
                "caja"=>$caja,
                "cuentas"=>$cuentas,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
    }

    public function GetOrdenDePago($values)
    {
        try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
            $numop = $values["VAL_ID"];
            log_message("error", "RELATED " . json_encode("SELECT * FROM dbo.vw_OrdenPago WHERE ID_OrdenDePago=" . $numop, JSON_PRETTY_PRINT));
            log_message("error", "RELATED " . json_encode("SELECT * FROM dbo.vw_RelOPImputacionContable WHERE id_OrdenDePago=" . $numop, JSON_PRETTY_PRINT));
            log_message("error", "RELATED " . json_encode("SELECT * FROM dbo.vw_RelOPValor WHERE id_OrdenDePago=" . $numop, JSON_PRETTY_PRINT));

            $op = $this->execAdHocAsArray("SELECT * FROM dbo.vw_OrdenPago WHERE ID_OrdenDePago=".$numop);
            $ropic = $this->execAdHocAsArray("SELECT * FROM dbo.vw_RelOPImputacionContable WHERE id_OrdenDePago=".$numop);
            $ropvalor = $this->execAdHocAsArray("SELECT * FROM dbo.vw_RelOPValor WHERE id_OrdenDePago=".$numop);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "op"=>$op,
                "ropic"=>$ropic,
                "ropvalor"=>$ropvalor,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
    }

    public function edit($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/abm");
            $values["page"]=1;
            $values["view"]="vw_OrdenPago";
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $values["accept-class-name"]="btn-abm-accept-confirm";
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function generarOrdenDePago($values) {
        try {
            // Invalidado a proposito para que no grabe
            $sqlGenerarOP = "dbo.spInsOrdenDePago ?, ?, ?, ?, ?, ?, ?, ?, ?, ?;";
            if ($values["id_Proveedor"]=="" || $values["id_Proveedor"]=="0" || $values["id_Proveedor"]==0 ) {$values["id_Proveedor"]=null;}
            // parametros del SP
            $prms = array(
                "ID_EmpresaSucursal" => $values["ID_EmpresaSucursal"], 
                "ID_Caja_Tesoreria" => $values["ID_Caja_Tesoreria"], 
                "id_Proveedor" => $values["id_Proveedor"],
                "Fecha_Pago" => $values["Fecha_Pago"],
                "Fecha_Emision" => $values["Fecha_Emision"], 
                "ImporteTotalOP" => $values["ImporteTotalOP"], 
                "Observaciones" => $values["Observaciones"], 
                "cuentas_pagadas" => $values["cuentas_pagadas"], 
                "valores_entregados" => $values["valores_entregados"], 
                //"ImporteACuenta" => $values["ImporteACuenta"], 
                "idRecibo" => $values["idRecibo"], // No se usa porque devuelve result set
            );
            $rc = $this->execAdHocWithParms($sqlGenerarOP, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $ordenDePago = $rc->result_array();
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "recibo"=>$ordenDePago[0],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }    
    }

    public function brow($values){
        try {
            $values["view"]="vw_OrdenPago";
            $values["order"]="Fecha_OP DESC,Nro_OP";
            $values["records"]=$this->get($values);
            $values["getters"]=array(
             "search"=>true,
             //"googlesearch"=>true,
             "excel"=>true,
             "pdf"=>true,
            );
            $sqlPermisosAdicionales = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionales = array("userId" => $values["id_user_active"],"perm" => "abm_modificacion_itemctacte");
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionales, $prmsPermisosAdicionales);
            if (!$rc) {
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $permisosAdicionales = $rc->result_array();
            }
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>false,
                "delete"=>($permisosAdicionales[0]["permitido"] == "S"),
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"imprimirNew","format"=>"text"),
                array("field"=>"Codigo_Comprobante","format"=>"text"),
                array("field"=>"Nro_OP","format"=>"text"),
                array("field"=>"Fecha_OP","format"=>"date"),
                array("field"=>"Fecha_Pago","format"=>"date"),
                array("field"=>"empresa","format"=>"text"),
                array("field"=>"caja","format"=>"text"),
                array("field"=>"Importe","format"=>"text"),
                array("field" => "Observaciones", "format" => "text"),
                array("field" => "", "format" => "text"),
            );
            $values["controls"]=array(
                 "<label>".lang('p_Nro_OP')."</label><input type='text' id='browser_nro_op' name='browser_nro_op' class='form-control number'/>",
                 "<label>".lang('p_Fecha_Caja_desde')."</label><input type='date' id='browser_Fecha_Caja_desde' name='browser_Fecha_Caja_desde' class='form-control date'/>",
                 "<label>".lang('p_Fecha_Caja_hasta')."</label><input type='date' id='browser_Fecha_Caja_hasta' name='browser_Fecha_Caja_hasta' class='form-control date'/>",
                 "<label>".lang('p_Fecha_Op_desde')."</label><input type='date' id='browser_Fecha_Op_desde' name='browser_Fecha_Op_desde' class='form-control date'/>",
                 "<label>".lang('p_Fecha_Op_hasta')."</label><input type='date' id='browser_Fecha_Op_hasta' name='browser_Fecha_Op_hasta' class='form-control date'/>",
                "<label>".lang('p_caja')."</label>".comboCaja($this),
            );

            $values["filters"]=array(
                array("name"=>"browser_nro_op", "operator"=>"=","fields"=>array("Nro_OP")),
                array("name"=>"browser_Fecha_Caja_desde", "operator"=>">=","fields"=>array("Fecha_Pago")),
                array("name"=>"browser_Fecha_Caja_hasta", "operator"=>"<=","fields"=>array("Fecha_Pago")),
                array("name"=>"browser_Fecha_Op_desde", "operator"=>">=","fields"=>array("Fecha_OP")),
                array("name"=>"browser_Fecha_Op_hasta", "operator"=>"<=","fields"=>array("Fecha_OP")),
                array("name"=>"browser_caja", "operator"=>"=","fields"=>array("ID_Caja_Tesoreria")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("caja","Observaciones","Importe","empresa","Fecha_Pago","Fecha_OP","Nro_OP")),
            ); 
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function delete($values)
    {
        try {

            $sqlDelOp = "dbo.spDelOrdenDePago ?; ";
            $prms = array("id" => $values["id"]);
            $rc = $this->execAdHocWithParms($sqlDelOp, $prms);
            if (!$rc) {
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $delOp = $rc->result_array();
            }
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "Records",
                "delOp" => $delOp,
                "table" => $this->table,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
}
