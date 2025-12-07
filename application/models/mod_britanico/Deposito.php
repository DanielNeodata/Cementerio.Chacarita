<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Deposito extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {

            $values["view"]="vw_Salida_cheques";
            $values["order"]="nro_cuenta, Fecha desc";

            $values["records"]=$this->get($values);

            $values["getters"]=array(
                "search"=>true,
                //"googlesearch"=>true,
                "excel"=>true,
                "pdf"=>true,
            );
   
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field" => "confirmar", "format" => "html"),
                array("field" => "nro_cuenta", "format" => "text"),
                array("field"=>"Desc_Bancos","format"=>"text"),
                array("field"=>"Fecha","format"=>"date"),  
                array("field"=>"ImporteTotal","format"=>"text"),
            );
   
            // Controles para los filtros?
            $values["controls"]=array(
                '<a href="#" class="btn btn-raised btn-primary btn-menu-click btn-m_valor_cartera" data-alert="0" data-module="mod_britanico" data-model="ValorEnCartera" data-table="vw_valorEnCartera" data-action="cargardepositos" data-page="1"><i class="material-icons">note_add</i></button></a>',
                "<label>".lang('p_nro_cuenta')."</label><input type='text' id='browser_nro_cuenta' name='browser_nro_cuenta' class='form-control text'/>",
                "<label>".lang('p_Fecha_desde')."</label><input type='date' id='browser_fecha_desde' name='browser_fecha_desde' class='form-control date'/>",
                "<label>".lang('p_Fecha_hasta')."</label><input type='date' id='browser_fecha_hasta' name='browser_fecha_hasta' class='form-control date'/>",

            );

            $values["filters"]=array(
                array("name"=>"browser_fecha_desde", "operator"=>">=","fields"=>array("FECHA")),
                array("name"=>"browser_fecha_hasta", "operator"=>"<=","fields"=>array("FECHA")),
                array("name"=>"browser_nro_cuenta", "operator"=>"like","fields"=>array("nro_cuenta")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("nro_cuenta","FECHA")),        
            );  
   
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    

}
