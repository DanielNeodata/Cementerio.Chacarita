<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class ValorEnCartera extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }


    public function GetValores($values){
	    try {
	        if (isset($values["view"])){$this->view=$values["view"];}
            $empresa=$values["EMPRE"];
            $caja=$values["CAJA"];
            $sql="";
            $sql = "   spValoresEnCartera '".$values["FDESDE"]."','".$values["FHASTA"]."','".$empresa."','".$caja."','".$values["LNAO"]."','".$values["VC"]."','".$values["VE"]."','".$values["VVFC"]."','".$values["VVFN"]."'";
            try
            {
                $cuentas = $this->execAdHocAsArray("SELECT * FROM dbo.vw_Cuenta_Bancaria ORDER BY detalle");
                $registros = $this->execAdHocAsArray($sql);
		        switch ((int)$empresa) {
			        case 1:
                        $sql="SELECT count(ID_ValorEnCartera) as total FROM neo_nogues.dbo.vw_valorEnCartera WHERE id_Salida_Cheques is null AND id_empresaSucursal=2 AND tipo_cheque IN ('P') AND ((Fecha_Vencimiento >= '".$values["FDESDE"]."' AND Fecha_Vencimiento <= '".$values["FHASTA"]."') OR Fecha_Vencimiento is null)";
				        break;
			        case 2:
                        $sql="SELECT count(ID_ValorEnCartera) as total FROM neo_nogues.dbo.vw_valorEnCartera WHERE id_Salida_Cheques is null AND id_empresaSucursal=1 AND tipo_cheque IN ('T') AND ((Fecha_Vencimiento >= '".$values["FDESDE"]."' AND Fecha_Vencimiento <= '".$values["FHASTA"]."') OR Fecha_Vencimiento is null)";
				        break;
		        }
                $ret=$this->getRecordsAdHoc($sql);
            } catch (Exception $k) {
                return array(
                    "cuantos"=>0,
                    "code"=>"4000",
                    "status"=>"ERROR",
                    "message"=>"Records",
                    "registros"=>"{}",
                    "data" => "{}",
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
                );
            }
	        return array(
                "cuantos"=>$ret[0]["total"],
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "registros"=>$registros,
                "data" => $cuentas,
                "_id_empresa_sucursal" => $values["id_sucursal"],
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}

    public function cargardepositos($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);
            
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));
            //vw_EmpresaSucursal_Combo

            $parameters_id_caja_tesoreria=array(
                "model"=>(MOD_BRITANICO."/vw_Caja_Tesoreria_Combo"),
                "table"=>"vw_Caja_Tesoreria_Combo",
                "name"=>"id_caja_tesoreria",
                "class"=>"form-control dbase",
                "empty"=>false,
                //"id_actual"=>1,
                "id_field"=>"id",
                "description_field"=>"descripcion",
                "get"=>array("order"=>"descripcion ASC","pagesize"=>-1),
            );
            $parameters_id_empresa=array(
                "model"=>(MOD_BRITANICO."/vw_EmpresaSucursal_Combo"),
                "table"=>"vw_EmpresaSucursal_Combo",
                "name"=>"id_empresa",
                "class"=>"form-control dbase",
                "empty"=>false,
                //"id_actual"=>1,
                "id_field"=>"id",
                "description_field"=>"descripcion",
                "get"=>array("order"=>"descripcion ASC","pagesize"=>-1),
            );

            $values["controls"]=array(
                "id_caja_tesoreria"=>getCombo($parameters_id_caja_tesoreria,$this),
                "id_empresa"=>getCombo($parameters_id_empresa,$this),
            );
            $data["parameters"] = $values;
            $html=$this->load->view($values["interface"],$data,true);
            
             return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "registros" => $registros,
                "_id_empresa_sucursal" => $values["id_sucursal"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            ); 
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function procesarDeposito($values){
        try {
            $depositosSQL = "spInsDepositoCheques ?, ?, ?, ?, ?";
            $prms=array(
                    "id_empresaSucursal" => $values["id_empresa_sucursal"],
                    "id_CajaTesoreria" => $values["ID_Caja_Tesoreria"],
                    "id_Cuenta_Bancaria" => $values["id_cuenta_bancaria"],
                    "fechaDeposito" => $values["fecha_deposito"],
                    "idConcatenado" => $values["valores"],
            );
            $rc = $this->execAdHocWithParms($depositosSQL, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
            } else {
                $depositos = $rc->result_array();
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                //"notificaciones"=>$notificaciones,
                //"emails"=>$emails,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }        
    }
    public function confirmarDeposito($values)
    {
        try {
            $depositosSQL = "spConfirmarDeposito ?";
            $prms = array("id_Salida_Cheques" => $values["id_salida_cheque"]);
            $rc = $this->execAdHocWithParms($depositosSQL, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
            } else {
                $depositos = $rc->result_array();
            }
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "Records",
                "table" => $this->table,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function revertirDeposito($values){
        try {
            $depositosSQL = "spRevertirMovimientoBancario ?, ?";
            $prms=array(
                    "id_valorencartera" => $values["id_valorencartera"],
                    "id_salida_cheques" => $values["id_salida_cheques"],
            );
            $rc = $this->execAdHocWithParms($depositosSQL, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
            } else {
                $depositos = $rc->result_array();
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                //"notificaciones"=>$notificaciones,
                //"emails"=>$emails,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }        
    }
}
