<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Funciones_avanzadas extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function GetBalance($values){
	     try {
	        if (isset($values["view"])){$this->view=$values["view"];}
            $sql = "   sp_Balance '".$values["FDESDE"]."','".$values["FHASTA"]."','".$values["CDESDE"]."','".$values["CHASTA"]."','".$values["PREFIJO"]."'";
            $estadistica = $this->execAdHocAsArray($sql);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "estadistica"=>$estadistica,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}

    public function GetBalanceHistorico($values){
	     try {
	        if (isset($values["view"])){$this->view=$values["view"];}
            $sql = "   sp_BalanceHistorico '".$values["FDESDE"]."','".$values["FHASTA"]."','".$values["CDESDE"]."','".$values["CHASTA"]."','".$values["PREFIJO"]."',".$values["IDE"]."";
            $estadistica = $this->execAdHocAsArray($sql);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "estadistica"=>$estadistica,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}

    public function GetLibroDiario($values){
	    try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
            if ($values["MODO"]=="T")
            {
                $sql = " select '' as NUMERO, '' as FECHA, '' as COMENTARIO, ";
                $sql = $sql.= "    '' as ASIENTO, '' as qryTC, '' As qryNUM, r.CUENTA, c.NOMBRE as NOMBRE_CUENTA, CASE WHEN SUM(IMPORTE)>0 THEN SUM(IMPORTE) ELSE NULL END As qryDeb, CASE WHEN SUM(IMPORTE)<=0 THEN -SUM(IMPORTE) ELSE NULL END As qryCre ";
                $sql = $sql.= " from CON_Encabezados e inner join CON_Renglones r on (e.ID=r.idEncabezado ) inner join CON_Cuentas As C ON (c.NUMERO = r.CUENTA)";
                $sql = $sql.= " where  ";
                $sql = $sql.= " (R.FECHA >='".$values["DESDE"]." 00:00:00' AND R.FECHA <='".$values["HASTA"]." 23:59:59') ";
                $sql = $sql.= " and (e.NUMERO like '".$values["PREFIJO"]."%')";
                $sql = $sql.= " group by CUENTA,NOMBRE order by CUENTA ";
            }
            else
            {
                $sql = " select e.NUMERO, e.FECHA, e.COMENTARIO, ";
                $sql = $sql.= "    r.ASIENTO, Left(r.ASIENTO,2) As qryTC, Right(r.ASIENTO,5) As qryNUM, r.CUENTA, c.NOMBRE as NOMBRE_CUENTA, CASE WHEN IMPORTE>0 THEN IMPORTE ELSE NULL END As qryDeb, CASE WHEN IMPORTE<=0 THEN -IMPORTE ELSE NULL END As qryCre ";
                $sql = $sql.= " from CON_Encabezados e inner join CON_Renglones r on (e.ID=r.idEncabezado ) inner join CON_Cuentas As C ON (c.NUMERO = r.CUENTA)";
                $sql = $sql.= " where  ";
                $sql = $sql .= " (R.FECHA >='" . $values["DESDE"] . " 00:00:00' AND R.FECHA <='" . $values["HASTA"] . " 23:59:59') ";
                $sql = $sql.= " and (e.NUMERO like '".$values["PREFIJO"]."%')";
                $sql = $sql.= " order by e.FECHA,qryNUM,r.RENGLON ";
            }
            log_message("error", "RELATED " . json_encode($sql, JSON_PRETTY_PRINT));

            $estadistica = $this->execAdHocAsArray($sql);
            return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "estadistica"=>$estadistica,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
            log_message("error", "RELATED ERROR " . json_encode($e, JSON_PRETTY_PRINT));
            return logError($e,__METHOD__ );
	    }
	}

    public function GetLibroDiarioHistorico($values){
	    try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
            $sql="";
             $sql = " select e.NUMERO, e.FECHA, e.COMENTARIO,";
	         $sql = $sql.= "    r.ASIENTO, Left(r.ASIENTO,2) As qryTC, Right(r.ASIENTO,5) As qryNUM, r.CUENTA, c.NOMBRE as NOMBRE_CUENTA, CASE WHEN IMPORTE>0 THEN IMPORTE ELSE NULL END As qryDeb, CASE WHEN IMPORTE<=0 THEN -IMPORTE ELSE NULL END As qryCre ";
             $sql = $sql.= " from CON_Encabezados_Historico e inner join CON_Renglones_Historico r on (e.NUMERO=r.ASIENTO ) inner join CON_Cuentas_Historico As C ON (c.NUMERO = r.CUENTA)  inner join CON_Configuracion_Historico cfg on (cfg.INICIO=C.DESDE)";
             $sql = $sql.= " where  ";
            $sql = $sql .= " (R.FECHA >='" . $values["DESDE"] . " 00:00:00' AND R.FECHA <='" . $values["HASTA"] . " 23:59:59') ";
			 $sql = $sql.= " and cfg.ID=".$values["IDE"]."";
			 $sql = $sql.= " and e.DESDE=C.DESDE";
			 $sql = $sql.= " and r.DESDE=C.DESDE";
             $sql = $sql.= " and (e.NUMERO like '".$values["PREFIJO"]."%')";
             $sql = $sql.= " order by e.FECHA,qryNUM,r.RENGLON ";
            $estadistica = $this->execAdHocAsArray($sql);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "estadistica"=>$estadistica,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}

    public function GetSaldoCtasCtes($values){
	    try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
            $sql="select SUM(Saldo) as Saldo from dbo.vw_SaldosCuentasCorrientes a where a.Fecha_Emision<'".$values["FSALDO"]."'";
            $estadistica = $this->execAdHocAsArray($sql);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "estadistica"=>$estadistica,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}

    public function GetLibroMayor($values){
	    try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
            $sql="";
            $sql = "   sp_LibroMayor '".$values["FDESDE"]." 00:00:00','".$values["FHASTA"]." 00:00:00','".$values["CDESDE"]."','".$values["CHASTA"]."','".$values["PREFIJO"]."'";
            log_message("error", "RELATED " . json_encode($sql, JSON_PRETTY_PRINT));
            $estadistica = $this->execAdHocAsArray($sql);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "estadistica"=>$estadistica,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}

    public function GetLibroMayorHistorico($values){
	    try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
            $sql="";
            $sql = "   sp_LibroMayorHistorico '".$values["FDESDE"]."','".$values["FHASTA"]."','".$values["CDESDE"]."','".$values["CHASTA"]."','".$values["PREFIJO"]."',".$values["IDE"];
            $estadistica = $this->execAdHocAsArray($sql);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "estadistica"=>$estadistica,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}

    public function ajuste_inflacion($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));
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
    public function transferencia_resultados($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));
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
    public function cierre_apertura($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));
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
    public function revertir_asientos_automaticos($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));
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
    public function remuneracion($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));
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
    
    public function diario($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));
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
    public function mayor($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);

            $parameters_id_cuenta_desde = array(
                "model" => (MOD_BRITANICO . "/Con_cuentas"),
                "table" => "Con_cuentas",
                "name" => "TB-aDesde",  // aca va el codigo
                "class" => "form-control",
                "empty" => false,
                "id_actual" => 110100,
                "id_field" => "NUMERO",
                "description_field" => "descripcion",
                "get" => array("where" => "isnull(NOMBRE,'')!=''", "view" => "vw_Con_cuentas", "order" => "descripcion ASC", "pagesize" => -1),
            );
            $parameters_id_cuenta_hasta = array(
                "model" => (MOD_BRITANICO . "/Con_cuentas"),
                "table" => "Con_cuentas",
                "name" => "TB-aHasta",  // aca va el codigo
                "class" => "form-control",
                "empty" => false,
                "id_actual" => 526900,
                "id_field" => "NUMERO",
                "description_field" => "descripcion",
                "get" => array("where"=>"isnull(NOMBRE,'')!=''","view" => "vw_Con_cuentas","order" => "descripcion ASC", "pagesize" => -1),
            );

            $values["controls"] = array(
                "id_cuenta_desde" => getCombo($parameters_id_cuenta_desde, $this),
                "id_cuenta_hasta" => getCombo($parameters_id_cuenta_hasta, $this),
            );

            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));


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
    public function balance($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);

            $parameters_id_cuenta_desde = array(
                "model" => (MOD_BRITANICO . "/Con_cuentas"),
                "table" => "Con_cuentas",
                "name" => "TB-aDesde",  // aca va el codigo
                "class" => "form-control",
                "empty" => false,
                "id_actual" => 110100,
                "id_field" => "NUMERO",
                "description_field" => "descripcion",
                "get" => array("where" => "isnull(NOMBRE,'')!=''", "view" => "vw_Con_cuentas", "order" => "descripcion ASC", "pagesize" => -1),
            );
            $parameters_id_cuenta_hasta = array(
                "model" => (MOD_BRITANICO . "/Con_cuentas"),
                "table" => "Con_cuentas",
                "name" => "TB-aHasta",  // aca va el codigo
                "class" => "form-control",
                "empty" => false,
                "id_actual" => 526900,
                "id_field" => "NUMERO",
                "description_field" => "descripcion",
                "get" => array("where" => "isnull(NOMBRE,'')!=''", "view" => "vw_Con_cuentas", "order" => "descripcion ASC", "pagesize" => -1),
            );

            $values["controls"] = array(
                "id_cuenta_desde" => getCombo($parameters_id_cuenta_desde, $this),
                "id_cuenta_hasta" => getCombo($parameters_id_cuenta_hasta, $this),
            );

            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_" . strtolower($values["function"])));

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
