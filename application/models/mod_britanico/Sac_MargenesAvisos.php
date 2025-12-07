<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sac_MargenesAvisos extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }


    public function brow($values){
        try {
            $values["view"]="AvisosMargenes";
            $values["order"]="AvisoMargenClave ASC";
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
                array("field"=>"AvisoMargenClave","format"=>"text"),
                array("field"=>"AvisoMargenWidth","format"=>"text"),
                array("field"=>"AvisoMargenHeight","format"=>"text"),
                array("field"=>"AvisoMargenX","format"=>"text"),
                array("field"=>"AvisoMargenY","format"=>"text"),
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
            $values["view"]="AvisosMargenes";
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
            $values["view"]="AvisosMargenes";
            $values["pagesize"]=-1;
            $values["order"]="AvisoMargenClave ASC";
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
            $values["view"]="AvisosMargenes";
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
            $values["view"]="AvisosMargenes";
            $values["table"]="AvisosMargenes";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'AvisoMargenClave' => $values["AvisoMargenClave"],
                        'AvisoMargenWidth'  => $values["AvisoMargenWidth"],
                        'AvisoMargenHeight'  => $values["AvisoMargenHeight"],
                        'AvisoMargenX'  => $values["AvisoMargenX"],
                        'AvisoMargenY'  => $values["AvisoMargenY"],
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'AvisoMargenClave' => $values["AvisoMargenClave"],
                        'AvisoMargenWidth'  => $values["AvisoMargenWidth"],
                        'AvisoMargenHeight'  => $values["AvisoMargenHeight"],
                        'AvisoMargenX'  => $values["AvisoMargenX"],
                        'AvisoMargenY'  => $values["AvisoMargenY"],
                    );
                }
            }
             $id7=$this->saveRecord($fields,$id,"AvisosMargenes");
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
