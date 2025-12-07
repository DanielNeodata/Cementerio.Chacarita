<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Con_cuentas extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            
            $values["table"]="CON_Cuentas";
            $values["view"]="vw_CON_Cuentas";
            $values["order"]="descripcion desc";
            log_message("error", "RELATED " . json_encode($values, JSON_PRETTY_PRINT));
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
                array("field"=>"ID","format"=>"code"),    // <----
                array("field"=>"NUMERO","format"=>"text"),
                array("field"=>"NOMBRE","format"=>"text"),
                //array("field"=>"TIPOAJUSTE","format"=>"text"),
                //array("field"=>"SALDONOMIN","format"=>"text"),
                //array("field"=>"SALDOAJUST","format"=>"text"),                
            );

            // Controles para los filtros?
            $values["controls"]=array(
                "<label>".lang('p_NUMERO')."</label><input type='text' id='browser_numero' name='browser_numero' class='form-control number'/>",
                "<label>".lang('p_NOMBRE')."</label><input type='text' id='browser_nombre' name='browser_nombre' class='form-control text'/>",
            );

            // Filtros y search
            $values["filters"]=array(
                array("name"=>"browser_numero", "operator"=>"=","fields"=>array("numero")),
                array("name"=>"browser_nombre", "operator"=>"like","fields"=>array("nombre")),
                array("name"=>"browser_tipoajuste", "operator"=>"like","fields"=>array("tipoajuste")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("numero","nombre")),                
            );            

            return parent::brow($values);

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
            
            //$values["table"]="vw_parcela"; // CCOO
            $values["table"]="CON_Cuentas"; // CCOO
            $values["view"]="CON_Cuentas";

            // CCOO
            $values["where"]=("id=".$values["id"]);
            //$values["where"]=("id_Parcela=".$values["id"]);
            
            $values["records"]=$this->get($values);
            //log_message("error", "RECORDS ".json_encode($values["records"],JSON_PRETTY_PRINT));
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    } 

    public function save($values,$fields=null){

        // Por aca paso cuando va a guardar lo que estoy editando
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){

                if($fields==null) {
                    $fields = array(
                        'NUMERO' => $values["NUMERO"],
                        'NOMBRE' => $values["NOMBRE"],
                        //'created' => $this->now,
                        //'verified' => $this->now,
                        //'offline' => null,
                        //'fum' => $this->now,                        
                    );
                }
            } else {
                if($fields==null) {

                    // CCOO
                    $fields = array(
                        'NUMERO' => $values["NUMERO"],
                        'NOMBRE' => $values["NOMBRE"],
                        //'offline' => null,
                        //'fum' => $this->now,                        

                    );
                }
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }


    
    public function excel($values){
        try {
            if ($values["where"]!=""){$values["where"]=base64_decode($values["where"]);}
            $values["view"]="CON_Cuentas";
            $values["delimiter"]=";";
            $values["pagesize"]=-1;
            $values["order"]=" NUMERO ASC";
            $values["records"]=$this->get($values);

            $values["columns"]=array(
               array("field"=>"ID","format"=>"code"),
               array("field"=>"NUMERO","format"=>"text"),
               array("field"=>"NOMBRE","format"=>"text"),

            );
            return parent::excel($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function pdf($values){
        try {
            if ($values["where"]!=""){$values["where"]=base64_decode($values["where"]);}
            $values["view"]="CON_Cuentas";
            $values["pagesize"]=-1;
            $values["order"]=" NUMERO ASC";
            $values["records"]=$this->get($values);
            $values["title"]="Cuentas: Altas, Bajas, Consultas y Modificaciones";
            $values["columns"]=array(
                //array("field"=>"ID","format"=>"code"),
                array("field"=>"NUMERO","format"=>"text"),
                array("field"=>"NOMBRE","format"=>"text"),
            );
            return parent::pdf($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }    

    public function listados($values){
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
    public function GetCuentasListados($values){
	    try {
	        

	        if (isset($values["view"])){$this->view=$values["view"];}

            $values["view"]="vw_SacLotes";
	        $values["pagesize"]=-1;
            $values["where"]=" ID = ".$values["ID"];

            $sql = "SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, CASE WHEN TIPOAJUSTE='A' THEN 'Automático' ELSE (CASE WHEN TIPOAJUSTE='D' THEN 'Directo' ELSE 'Sin Ajuste' END) END As qryTA ";
            $sql = $sql." FROM [CON_Cuentas] "; 

            $cuentas = $this->execAdHocAsArray($sql);

	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "cuentas"=>$cuentas,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}

    public function plan_de_cuentas($values){
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
    public function GetPlanDeCuentas($values){
	    try {
	        

	        if (isset($values["view"])){$this->view=$values["view"];}

	        $values["view"]="vw_SacLotes";
	        $values["pagesize"]=-1;
            $values["where"]=" ID = ".$values["ID"];

            $sql = "SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, isnull(qryTA,'') as TipoAjuste, ";
            $sql = $sql." REPLICATE(' ', (  ";
            $sql = $sql." SELECT Count(NRO) * 3 ";
            $sql = $sql." FROM ((SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Rubros]  WHERE NUMERO BETWEEN '0' AND '9999999999') UNION  ";
            $sql = $sql." (SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Cuentas] WHERE NUMERO BETWEEN '0' AND '9999999999')) As S  ";
            $sql = $sql." WHERE Left(NUMERO,Len(S.NRO))=S.NRO AND S.NRO<>NUMERO  ";
            $sql = $sql." )) As IndentBlank, ";
            $sql = $sql." REPLICATE('&nbsp;', ( "; 
            $sql = $sql." SELECT Count(NRO) * 3 ";
            $sql = $sql." FROM ((SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Rubros]  WHERE NUMERO BETWEEN '0' AND '9999999999') UNION  ";
            $sql = $sql." (SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Cuentas] WHERE NUMERO BETWEEN '0' AND '9999999999')) As S  ";
            $sql = $sql." WHERE Left(NUMERO,Len(S.NRO))=S.NRO AND S.NRO<>NUMERO  ";
            $sql = $sql." )) As IndentHtml  ";
            $sql = $sql." FROM ((SELECT NUMERO, NOMBRE, NULL As SALDONOMIN, NULL As SALDOAJUST, NULL As qryTA FROM [CON_Rubros]  WHERE NUMERO BETWEEN '0' AND '9999999999') UNION  ";
            $sql = $sql." (SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, CASE WHEN TIPOAJUSTE='A' THEN 'Automático' ELSE (CASE WHEN TIPOAJUSTE='D' THEN 'Directo' ELSE 'Sin Ajuste' END) END As qryTA  ";
            $sql = $sql." FROM [CON_Cuentas] WHERE NUMERO BETWEEN '0' AND '9999999999')) As U1  ";
            $sql = $sql." WHERE (U1.NUMERO BETWEEN '0' AND '9999999999')  ";

            $cuentas = $this->execAdHocAsArray($sql);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "cuentas"=>$cuentas,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}    


}
