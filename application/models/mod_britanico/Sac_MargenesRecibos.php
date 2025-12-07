<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sac_MargenesRecibos extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    
    
    public function brow($values){
        try {
            $values["view"]="RecibosMargenes";
            $values["order"]="ReciboMargenClave ASC";
            $values["records"]=$this->get($values);
            $values["getters"]=array(
             "search"=>true,
             "googlesearch"=>true,
             "excel"=>false,
             "pdf"=>false,
            );
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"ID","format"=>"text"),
                array("field"=>"ReciboMargenClave","format"=>"text"),
                array("field"=>"ReciboMargenWidth","format"=>"text"),
                array("field"=>"ReciboMargenHeight","format"=>"text"),
                array("field"=>"ReciboMargenX","format"=>"text"),
                array("field"=>"ReciboMargenY","format"=>"text"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
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
            $values["view"]="RecibosMargenes";
            $values["delimiter"]=";";
            $values["pagesize"]=-1;
            $values["records"]=$this->get($values);
            $values["columns"]=array(
                array("field"=>"ID","format"=>"code"),
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
            $values["view"]="RecibosMargenes";
            $values["pagesize"]=-1;
            $values["order"]="ReciboMargenClave ASC";
            $values["records"]=$this->get($values);
            $values["title"]="Servicios: Altas, Bajas, Consultas y Modificaciones";
            $values["columns"]=array(
                array("field"=>"ID","format"=>"code"),
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
            $values["view"]="RecibosMargenes";
            $values["where"]=("ID=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        $message="";
        try {
            $values["view"]="RecibosMargenes";
            $values["table"]="RecibosMargenes";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'ReciboMargenClave' => $values["ReciboMargenClave"],
                        'ReciboMargenWidth'  => $values["ReciboMargenWidth"],
                        'ReciboMargenHeight'  => $values["ReciboMargenHeight"],
                        'ReciboMargenX'  => $values["ReciboMargenX"],
                        'ReciboMargenY'  => $values["ReciboMargenY"],
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'ReciboMargenClave' => $values["ReciboMargenClave"],
                        'ReciboMargenWidth'  => $values["ReciboMargenWidth"],
                        'ReciboMargenHeight'  => $values["ReciboMargenHeight"],
                        'ReciboMargenX'  => $values["ReciboMargenX"],
                        'ReciboMargenY'  => $values["ReciboMargenY"],
                    );
                }
            }
             $id7=$this->saveRecord($fields,$id,"RecibosMargenes");
             $data=array("ID"=>$id);
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
