<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class cocherias extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["view"]="vwCocheria";
            $values["table"]="vwCocheria";
            $values["order"]="Nombre_cocheria ASC";
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
                "delete"=>true,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"ID","format"=>"code"),
                array("field"=>"Nombre_cocheria","format"=>"text"),
                array("field"=>"Domicilio","format"=>"text"),
                array("field"=>"Localidad","format"=>"text"),
                array("field"=>"Telefonos","format"=>"text"),
                array("field"=>"CodigoPostal","format"=>"text"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("Nombre_cocheria","Nombre_cocheria")),
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
            $values["view"]="vwCocheria";
            $values["delimiter"]=";";
            $values["pagesize"]=-1;
            $values["order"]=" Nombre_cocheria ASC";
            $values["records"]=$this->get($values);

            $values["columns"]=array(
               array("field"=>"id","format"=>"code"),
               array("field"=>"Nombre_cocheria","format"=>"text"),
                array("field"=>"Domicilio","format"=>"text"),
                array("field"=>"Localidad","format"=>"text"),
                array("field"=>"Telefonos","format"=>"text"),
                array("field"=>"CodigoPostal","format"=>"text"),

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
            $values["view"]="vwCocheria";
            $values["pagesize"]=-1;
            $values["order"]=" Nombre_cocheria ASC";
            $values["records"]=$this->get($values);
            
            $values["title"]="Cocherias: Altas, Bajas, Consultas y Modificaciones";
            $values["columns"]=array(
                array("field"=>"id","format"=>"code"),
                array("field"=>"Nombre_cocheria","format"=>"text"),
                array("field"=>"Domicilio","format"=>"text"),
                array("field"=>"Localidad","format"=>"text"),
                array("field"=>"Telefonos","format"=>"text"),
                array("field"=>"CodigoPostal","format"=>"text"),
            );
            return parent::pdf($values);
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
            
            $values["view"]="vwCocheria";

            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            //log_message("error", "RECORDS ".json_encode($values["records"],JSON_PRETTY_PRINT));
            
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        
        $values["view"]="cocheria";
        $values["table"]="cocheria";
        $values["id_cocheria"]=$values["id"];
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){

                if($fields==null) {
                    $fields = array(
                        'Nombre_cocheria' => $values["Nombre_cocheria"],
                        'Domicilio' => $values["Domicilio"],
                        'Telefonos' => $values["Telefonos"],
                        'CodigoPostal' => $values["CodigoPostal"],
                        'Localidad' => $values["Localidad"],

                        //'TITULAR' => $values["TITULAR"],

                        //{...more fields...}

                    );
                }
            } else {
                //log_message("error", "RELATED ".json_encode($values,JSON_PRETTY_PRINT));
                if($fields==null) {
                    $fields = array(
                        'Nombre_cocheria' => $values["Nombre_cocheria"],
                        'Domicilio' => $values["Domicilio"],
                        'Telefonos' => $values["Telefonos"],
                        'CodigoPostal' => $values["CodigoPostal"],
                        'Localidad' => $values["Localidad"],

                  
                    );
                }
            }
            $id = parent::saveRecordCustomKey($fields,$values["id"],"cocheria","id_cocheria");
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$message,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data,
                );
            
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
