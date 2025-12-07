<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Con_rubros extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["order"]="NUMERO ASC";
            //$values["top"]=3;
            $values["records"]=$this->get($values);

            $values["getters"]=array(
             "search"=>true,
             "googlesearch"=>true,
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
                //array("field"=>"ID","format"=>"code"),
                array("field"=>"NUMERO","format"=>"text"),
                array("field"=>"NOMBRE","format"=>"text"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("NUMERO","NOMBRE")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    
    public function excel($values){
        try {
            if ($values["where"]!=""){$values["where"]=base64_decode($values["where"]);}
            $values["view"]="vw_SacRubros";
            $values["delimiter"]=";";
            $values["pagesize"]=-1;
            $values["order"]=" NUMERO ASC";
            $values["records"]=$this->get($values);

            $values["columns"]=array(
               array("field"=>"ID","format"=>"code"),
               array("field"=>"NUMERO_BLANK","format"=>"text"),
               array("field"=>"NOMBRE_BLANK","format"=>"text"),

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
            $values["view"]="vw_SacRubros";
            $values["pagesize"]=-1;
            $values["order"]=" NUMERO ASC";
            $values["records"]=$this->get($values);
            $values["title"]="Cuentas: Altas, Bajas, Consultas y Modificaciones";
            $values["columns"]=array(
                array("field"=>"ID","format"=>"code"),
                array("field"=>"NUMERO_BLANK","format"=>"text"),
                array("field"=>"NOMBRE_BLANK","format"=>"text"),
            );
            return parent::pdf($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function GetRubrosByFilter($values){
	    try {
	        if (isset($values["view"])){$this->view=$values["view"];}
	        $values["view"]="vw_SacLotes";
	        $values["pagesize"]=-1;
            $values["where"]=" ID = ".$values["ID"];
            $sql = "  SELECT R1.NUMERO, ISNULL(R1.NOMBRE,'') as NOMBRE, REPLICATE('&nbsp;',(SELECT Count(NUMERO)*3 FROM [CON_Rubros] WHERE Left(R1.NUMERO,Len(NUMERO))=NUMERO AND NUMERO<>R1.NUMERO)) As qryIndent ";
            $sql = $sql." FROM [CON_Rubros] As R1 WHERE (R1.NUMERO BETWEEN '".$values["DESDE"]."' AND '".$values["HASTA"]."') ORDER BY R1.NUMERO";
            $rubros = $this->execAdHocAsArray($sql);

	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "rubros"=>$rubros,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
	        return logError($e,__METHOD__ );
	    }
	}

    public function GetRubrosHistoricosByFilter($values){
	    try {
	        
	        if (isset($values["view"])){$this->view=$values["view"];}
             $values["view"]="CON_Configuracion_Historico";
             $values["where"]=("id=".$values["ID"]);
             $info=$this->get($values);

             $val=$info["data"][0]["INICIO"];
             $fecha = explode(" ", $val);
            $sql = " SELECT R1.NUMERO, isnull(R1.NOMBRE,'') as NOMBRE, REPLICATE('&nbsp;',(SELECT Count(NUMERO)*2 FROM [CON_Rubros_Historico] WHERE Left(R1.NUMERO,Len(NUMERO))=NUMERO AND NUMERO<>R1.NUMERO AND DESDE={d '".$fecha[0]."'})) As qryIndent ";
            $sql = $sql." FROM [CON_Rubros_Historico] As R1 ";
            $sql = $sql."  WHERE (R1.NUMERO BETWEEN '".$values["DESDE"]."' AND '".$values["HASTA"]."' AND DESDE={d '".$fecha[0]."'}) ";
            $sql = $sql."  ORDER BY R1.NUMERO";
            $rubros = $this->execAdHocAsArray($sql);
	        return array(
	            "code"=>"2000",
	            "status"=>"OK",
	            "message"=>"Records",
                "rubros"=>$rubros,
	            "table"=>$this->table,
	            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
	    }
	    catch(Exception $e) {
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

    public function edit($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/abm");
            $values["page"]=1;
           
            $values["view"]="CON_Rubros";

            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){

                if($fields==null) {
                    $fields = array(
                        'NUMERO' => $values["NUMERO"],
                        'NOMBRE' => $values["NOMBRE"],

                        //'TITULAR' => $values["TITULAR"],

                        //{...more fields...}

                    );
                }
            } else {
                //log_message("error", "RELATED ".json_encode($values,JSON_PRETTY_PRINT));
                if($fields==null) {
                    $fields = array(
                        'NUMERO' => $values["NUMERO"],
                        'NOMBRE' => $values["NOMBRE"],
                  
                    );
                }
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
