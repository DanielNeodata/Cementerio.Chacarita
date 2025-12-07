<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class parcelaCombo extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {

            $values["table"]="vw_parcelaCombo";
            //$values["view"]="Inhumado";
            $values["view"]="vw_parcelaCombo";
            //$values["where"]="Disponible='S'"; // el where lo seteo al armar el combo desde inhumados
            $values["order"]="NumeroCompacto ASC";
            $values["records"]=$this->get($values);
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}