<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sac_modelos_notificaciones extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["view"]="ModelosNotificaciones";
            $values["order"]="1 ASC";
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
                array("field"=>"ModeloNotificacionNombre","format"=>"text"),
                array("field"=>"ModeloNotificacionTitulo","format"=>"text"),
                array("field"=>"remitente","format"=>"text"),
                array("field"=>"NombreRemitente","format"=>"text"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
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
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/abm");
            $values["page"]=1;
            $values["view"]="ModelosNotificaciones";
            $values["where"]=("Id=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        $values["table"]="ModelosNotificaciones";
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'ModeloNotificacionNombre' => $values["ModeloNotificacionNombre"],
                        'ModeloNotificacionTitulo' => $values["ModeloNotificacionTitulo"],
                        'ModeloNotificacionHtml' => $values["ModeloNotificacionHtml"],
                        'remitente' => $values["remitente"],
                        'NombreRemitente' => $values["NombreRemitente"],
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'ModeloNotificacionNombre' => $values["ModeloNotificacionNombre"],
                        'ModeloNotificacionTitulo' => $values["ModeloNotificacionTitulo"],
                        'ModeloNotificacionHtml' => $values["ModeloNotificacionHtml"],
                        'remitente' => $values["remitente"],
                        'NombreRemitente' => $values["NombreRemitente"],
                    );
                }
            }
            $id7=$this->saveRecord($fields,$id,"ModelosNotificaciones");
            $data=array("ID"=>$id);
            $message="Registro guardado correctamente";
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

    public function delete($values){
        try {
			try
			{
				$this->db->where('id', $values["id"]);
				$this->db->delete('ModelosNotificaciones');
			}
			catch(Exception $ee){
			    return logError($ee,__METHOD__ );
			    throw $ee;
			}
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_delete'),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null
                );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
