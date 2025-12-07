<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Cocheria extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {

            $values["view"]="vw_cocherias_resuelto";
            //$values["order"]="id_inhumado desc";

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
                array("field"=>"Nombre_cocheria","format"=>"text"), 
                array("field"=>"FechaMovimiento","format"=>"text"),
                array("field"=>"numerocliente","format"=>"text"),               
            );
   
            // Controles para los filtros?
            $values["controls"]=array(
                "<label>".lang('p_NumeroCliente')."</label><input type='text' id='browser_NumeroCliente' name='browser_NumeroCliente' class='form-control number'/>",
                "<label>".lang('p_cliente')."</label><input type='text' id='browser_cliente' name='browser_pagador' class='form-control text'/>",
                "<label>".lang('p_pagador')."</label><input type='text' id='browser_pagador' name='browser_pagador' class='form-control text'/>",
            );

            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name"=>"browser_NumeroCliente", "operator"=>"=","fields"=>array("NumeroCliente")),
                array("name"=>"browser_cliente", "operator"=>"like","fields"=>array("cliente")),
                array("name"=>"browser_pagador", "operator"=>"like","fields"=>array("pagador")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("NumeroCliente","cliente","pagador")),                
            );  
   
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
